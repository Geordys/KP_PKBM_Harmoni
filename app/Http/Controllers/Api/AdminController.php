<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\RegistrationsExport;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function stats()
    {
        $total = (int) DB::table('registrations')->count();
        $paketB = (int) DB::table('registrations')->where('paket','B')->count();
        $paketC = (int) DB::table('registrations')->where('paket','C')->count();

        $statusRows = DB::table('registrations')
            ->select('status', DB::raw('COUNT(*) as c'))
            ->groupBy('status')
            ->get();

        $statusMap = ["MENUNGGU"=>0, "DIVERIFIKASI"=>0, "DITERIMA"=>0];
        foreach ($statusRows as $r) $statusMap[$r->status] = (int)$r->c;

        // Data chart per bulan (6 bulan terakhir)
        $chartData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->format('Y-m');
            $count = DB::table('registrations')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $chartData[$date->format('M Y')] = $count;
        }

        return response()->json([
            "total" => $total,
            "paket" => ["B"=>$paketB, "C"=>$paketC],
            "status" => $statusMap,
            "chartData" => $chartData,
        ]);
    }

    public function index(Request $request)
    {
        $query = DB::table('registrations');

        if ($request->has('paket') && $request->paket) {
            $query->where('paket', $request->paket);
        }

        if ($request->has('q') && $request->q) {
            $q = $request->q;
            $query->where(function($subQuery) use ($q) {
                $subQuery->where('nama', 'like', '%' . $q . '%')
                         ->orWhere('nisn', 'like', '%' . $q . '%');
            });
        }

        if ($request->has('bulan') && $request->bulan && $request->has('tahun') && $request->tahun) {
            $query->whereYear('created_at', (int)$request->tahun)
                  ->whereMonth('created_at', (int)$request->bulan);
        }

        $registrations = $query->orderBy('created_at', 'desc')->get();

        return response()->json(['registrations' => $registrations]);
    }

    public function updateStatus(Request $request)
    {
        $data = $request->validate([
            'nomor_pendaftaran' => 'required|string',
            'status' => 'required|in:MENUNGGU,DIVERIFIKASI,DITERIMA',
        ]);

        $updateData = [
            'status' => $data['status'],
            'updated_at' => now(),
        ];

        // Only set verified_at if the column exists in the table (defensive)
        try {
            if ($data['status'] === 'DIVERIFIKASI' && \Schema::hasColumn('registrations', 'verified_at')) {
                $updateData['verified_at'] = now();
            }
            if ($data['status'] === 'DITERIMA' && \Schema::hasColumn('registrations', 'accepted_at')) {
                $updateData['accepted_at'] = now();
            }

            DB::table('registrations')
                ->where('nomor_pendaftaran', $data['nomor_pendaftaran'])
                ->update($updateData);
        } catch (\Exception $e) {
            \Log::error('Failed to update registration status', ['error' => $e->getMessage(), 'payload' => $data]);
            return response()->json(['message' => 'Gagal memperbarui status: ' . $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Status berhasil diperbarui']);
    }

    public function exportExcel(Request $request)
    {
        $query = DB::table('registrations');

        if ($request->has('paket') && $request->paket) {
            $query->where('paket', $request->paket);
        }

        if ($request->has('q') && $request->q) {
            $q = $request->q;
            $query->where(function($subQuery) use ($q) {
                $subQuery->where('nama', 'like', '%' . $q . '%')
                         ->orWhere('nomor_pendaftaran', 'like', '%' . $q . '%');
            });
        }

        $registrations = $query->orderBy('created_at', 'desc')->get();

        return Excel::download(new RegistrationsExport($registrations), 'Data_Pendaftar_Siswa_PKBM Harmoni.xlsx');
    }

    public function destroy($nomorPendaftaran)
    {
        try {
            $registration = DB::table('registrations')->where('nomor_pendaftaran', $nomorPendaftaran)->first();
            
            if ($registration) {
                // Delete associated user if exists (to fully clear registration state)
                if (isset($registration->email)) {
                    DB::table('users')->where('email', $registration->email)->where('role', 'SISWA')->delete();
                }

                DB::table('registrations')
                    ->where('nomor_pendaftaran', $nomorPendaftaran)
                    ->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Data pendaftaran dan akun siswa berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pendaftaran tidak ditemukan'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadPoster(Request $request)
    {
        $request->validate([
            'poster' => 'required|image|mimes:jpeg,png,jpg,jfif|max:10240',
        ]);

        if ($request->hasFile('poster')) {
            try {
                $file = $request->file('poster');
                $filename = 'poster-pkbm-new.jfif';
                // Force overwrite by deleting existing first (optional, move usually overwrites)
                // if (file_exists(public_path('assets/' . $filename))) { unlink(public_path('assets/' . $filename)); }
                
                $file->move(public_path('assets'), $filename);

                return response()->json([
                    'success' => true,
                    'message' => 'Poster berhasil diupload. Silakan refresh halaman beranda siswa.'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal upload file: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'File tidak ditemukan'
        ], 400);
    }

    public function uploadDokumentasi(Request $request)
    {
        $request->validate([
            'number' => 'required|integer|min:1|max:25',
            'dokumentasi' => 'required|image|mimes:jpeg,png,jpg,jfif|max:10240',
        ]);

        $number = $request->input('number');

        if ($request->hasFile('dokumentasi')) {
            $file = $request->file('dokumentasi');
            $filename = 'foto-pkbm' . $number . '.jfif';
            $file->move(public_path('assets'), $filename);

            return response()->json([
                'success' => true,
                'message' => 'Foto dokumentasi ' . $number . ' berhasil diupload'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'File tidak ditemukan'
        ], 400);
    }
    public function getHomepageContent()
    {
        $content = \App\Models\HomepageContent::all();
        $formatted = [];
        foreach ($content as $item) {
            $formatted[$item->key] = $item->value;
        }
        return response()->json(['success' => true, 'data' => $formatted]);
    }

    public function updateHomepageContent(Request $request)
    {
        $data = $request->all(); // Expecting key => value pairs
        
        try {
            DB::beginTransaction();
            foreach ($data as $key => $value) {
                // Ignore internal parameters that shouldn't be saved
                if (in_array($key, ['auth_user_id', '_token'])) {
                    continue;
                }

                // Determine section based on key
                $section = 'other';
                if (str_starts_with($key, 'hero_')) $section = 'hero';
                elseif (str_starts_with($key, 'about_') || str_starts_with($key, 'stats_') || in_array($key, ['visi', 'misi'])) $section = 'about';
                elseif (str_starts_with($key, 'guru_') || $key === 'foto_group') $section = 'guru';
                elseif (str_starts_with($key, 'program_') || str_starts_with($key, 'paket_')) $section = 'program';
                elseif (str_starts_with($key, 'alur_')) $section = 'alur';
                elseif (str_starts_with($key, 'galeri_')) $section = 'galeri';
                elseif (str_starts_with($key, 'faq_')) $section = 'faq';
                elseif (str_starts_with($key, 'kontak_')) $section = 'kontak';
                
                // For existing records, we don't want to change the section if it's already set
                // But for new records, we MUST provide a section
                $existing = \App\Models\HomepageContent::where('key', $key)->first();
                
                \App\Models\HomepageContent::updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => is_string($value) ? $value : (is_array($value) ? json_encode($value) : (string)$value),
                        'section' => $existing ? $existing->section : $section
                    ]
                );
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Konten beranda berhasil diperbarui']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui konten: ' . $e->getMessage()], 500);
        }
    }

    public function uploadHeroBg(Request $request)
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,jfif|max:10240',
        ]);

        if ($request->hasFile('file')) {
             try {
                $file = $request->file('file');
                $filename = 'foto-pkbm7.jfif'; // Overwrite existing default or use unique name
                // To avoid caching issues if overwriting exact name, maybe append timestamp in DB value?
                // For now, let's keep simple overwrite but maybe different name to forced refresh?
                // User asked to replace background.
                
                $file->move(public_path('assets'), $filename);
                
                // Update DB just in case we change logic later to dynamic filename
                \App\Models\HomepageContent::updateOrCreate(
                    ['key' => 'hero_bg'],
                    ['value' => '/assets/' . $filename . '?t=' . time()] // Cache buster
                );

                return response()->json([
                    'success' => true,
                    'message' => 'Background berhasil diupload.'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal upload file: ' . $e->getMessage()
                ], 500);
            }
        }
        return response()->json(['success' => false, 'message' => 'No file'], 400);
    }

    public function update(Request $request, $nomorPendaftaran)
    {
        // Allow updating any field, but minimal validation
        $data = $request->except(['id', 'created_at', 'updated_at', 'nomor_pendaftaran']);
        $data['updated_at'] = now();

        // If admin provided a note, mark it as unread for the student
        if (array_key_exists('catatan', $data)) {
            // Only set unread if catatan is not empty
            if (is_string($data['catatan']) && trim($data['catatan']) !== '') {
                $data['catatan_read'] = false;
            } else {
                // If catatan is cleared, mark as read
                $data['catatan_read'] = true;
            }
        }

        // If admin approves edit request, set edit_allowed and clear minta_izin_edit
        if (array_key_exists('edit_allowed', $data)) {
            $data['edit_allowed'] = (bool)$data['edit_allowed'];
            // clear request when approved
            if ($data['edit_allowed']) {
                $data['minta_izin_edit'] = false;
            }
        }

        // Remove internal request keys that should not be written to registrations table
        if (array_key_exists('auth_user_id', $data)) unset($data['auth_user_id']);
        if (array_key_exists('_token', $data)) unset($data['_token']);

        try {
            $updated = DB::table('registrations')
                ->where('nomor_pendaftaran', $nomorPendaftaran)
                ->update($data);

            if ($updated > 0 || DB::table('registrations')->where('nomor_pendaftaran', $nomorPendaftaran)->exists()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil diperbarui'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan atau tidak ada perubahan'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui data: ' . $e->getMessage()
            ], 500);
        }
    }
}
