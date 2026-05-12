<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RegistrationController extends Controller
{
    public function store(Request $request)
    {
        // Log incoming payload for debugging
        Log::info('RegistrationController@store incoming payload', $request->all());

        // Validate input and return clear validation errors when present
        try {
            $data = $request->validate([
                'nama' => 'required|string|max:255',
                'paket' => 'required|in:B,C',
                'jk' => 'required|in:L,P',
                'tanggal_lahir' => 'required|date',
                'alamat' => 'required|string',
                'desa' => 'required|string|max:255',
                'kecamatan' => 'required|string|max:255',
                'sekolah_asal' => 'required|string|max:255',
                'hp' => 'required|string|max:30',

                // optional
                'nisn' => 'nullable|string|max:255',
                'nik' => 'nullable|string|max:255',
                'tempat_lahir' => 'nullable|string|max:255',
                'agama' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'rt' => 'nullable|string|max:255',
                'rw' => 'nullable|string|max:255',
                'dusun' => 'nullable|string|max:255',
                'kode_pos' => 'nullable|string|max:10',
                'jenis_tinggal' => 'nullable|string|max:255',
                'alat_transportasi' => 'nullable|string|max:255',
                'telepon' => 'nullable|string|max:30',
                'jenis_bank' => 'nullable|string|max:255',
                'no_rekening' => 'nullable|string|max:255',
                'skhun' => 'nullable|string|max:255',
                'penerima_kps' => 'nullable|in:Ya,Tidak',
                'catatan_tambahan' => 'nullable|string',
                'nama_ayah' => 'nullable|string|max:255',
                'tanggal_lahir_ayah' => 'nullable|integer|min:1900|max:' . (date('Y') - 18),
                'pendidikan_ayah' => 'nullable|string|max:255',
                'pekerjaan_ayah' => 'nullable|string|max:255',
                'penghasilan_ayah' => 'nullable|string|max:255',
                'nik_ayah' => 'nullable|string|max:255',
                'nama_ibu' => 'nullable|string|max:255',
                'tanggal_lahir_ibu' => 'nullable|integer|min:1900|max:' . (date('Y') - 18),
                'pendidikan_ibu' => 'nullable|string|max:255',
                'pekerjaan_ibu' => 'nullable|string|max:255',
                'penghasilan_ibu' => 'nullable|string|max:255',
                'nik_ibu' => 'nullable|string|max:255',
                'ayah_alamat' => 'nullable|string',
                'ibu_alamat' => 'nullable|string',
            ]);

        } catch (ValidationException $ve) {
            Log::warning('Registration validation failed', ['errors' => $ve->errors(), 'payload' => $request->all()]);
            return response()->json(['message' => 'Validasi gagal', 'errors' => $ve->errors()], 422);
        }

        try {
            $nomor = 'PKBM-' . date('Ymd') . '-' . str_pad((string)random_int(1, 9999), 4, '0', STR_PAD_LEFT);

            $id = DB::table('registrations')->insertGetId([
                'nomor_pendaftaran' => $nomor,
                'nama' => $data['nama'],
                'paket' => $data['paket'],
                'jk' => $data['jk'],
                'nisn' => $data['nisn'] ?? null,
                'nik' => $data['nik'] ?? null,
                'tempat_lahir' => $data['tempat_lahir'] ?? null,
                'tanggal_lahir' => $data['tanggal_lahir'],
                'agama' => $data['agama'] ?? null,
                'hp' => $data['hp'],
                'email' => $data['email'] ?? null,
                'alamat' => $data['alamat'],
                'rt_rw' => ($data['rt'] ?? '') . '/' . ($data['rw'] ?? ''),
                'dusun' => $data['dusun'] ?? null,
                'kelurahan_desa' => $data['desa'],
                'kecamatan' => $data['kecamatan'],
                'kode_pos' => $data['kode_pos'] ?? null,
                'jenis_tinggal' => $data['jenis_tinggal'] ?? null,
                'transportasi' => $data['alat_transportasi'] ?? null,
                'telepon' => $data['telepon'] ?? null,
                'jenis_bank' => $data['jenis_bank'] ?? null,
                'no_rekening' => $data['no_rekening'] ?? null,
                'sekolah_asal' => $data['sekolah_asal'],
                'skhun' => $data['skhun'] ?? null,
                'penerima_kps_kip_pkh' => $data['penerima_kps'] ?? null,
                'catatan' => $data['catatan_tambahan'] ?? null,
                'ayah_nama' => $data['nama_ayah'] ?? null,
                'ayah_tahun_lahir' => $data['tanggal_lahir_ayah'] ?? null,
                'ayah_pendidikan' => $data['pendidikan_ayah'] ?? null,
                'ayah_pekerjaan' => $data['pekerjaan_ayah'] ?? null,
                'ayah_penghasilan' => $data['penghasilan_ayah'] ?? null,
                'ayah_nik' => $data['nik_ayah'] ?? null,
                'ibu_nama' => $data['nama_ibu'] ?? null,
                'ibu_tahun_lahir' => $data['tanggal_lahir_ibu'] ?? null,
                'ibu_pendidikan' => $data['pendidikan_ibu'] ?? null,
                'ibu_pekerjaan' => $data['pekerjaan_ibu'] ?? null,
                'ibu_penghasilan' => $data['penghasilan_ibu'] ?? null,
                'ibu_nik' => $data['nik_ibu'] ?? null,
                'ayah_alamat' => $data['ayah_alamat'] ?? null,
                'ibu_alamat' => $data['ibu_alamat'] ?? null,
                'status' => 'MENUNGGU',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            \Log::info('Registration created', ['id' => $id, 'nomor' => $nomor]);

            // Fetch the created registration to return complete data
            $registration = DB::table('registrations')->where('id', $id)->first();

            return response()->json([
                'message' => 'pendaftaran berhasil',
                'id' => $registration->id,
                'nomor_pendaftaran' => $registration->nomor_pendaftaran,
                'status' => $registration->status,
                'nama' => $registration->nama,
                'paket' => $registration->paket,
                'jk' => $registration->jk,
                'nisn' => $registration->nisn,
                'nik' => $registration->nik,
                'tempat_lahir' => $registration->tempat_lahir,
                'tanggal_lahir' => $registration->tanggal_lahir,
                'agama' => $registration->agama,
                'hp' => $registration->hp,
                'email' => $registration->email,
                'alamat' => $registration->alamat,
                'rt_rw' => $registration->rt_rw,
                'dusun' => $registration->dusun,
                'kelurahan_desa' => $registration->kelurahan_desa,
                'desa' => $registration->kelurahan_desa,
                'kecamatan' => $registration->kecamatan,
                'kode_pos' => $registration->kode_pos,
                'jenis_tinggal' => $registration->jenis_tinggal,
                'transportasi' => $registration->transportasi,
                'telepon' => $registration->telepon,
                'jenis_bank' => $registration->jenis_bank ?? null,
                'no_rekening' => $registration->no_rekening ?? null,
                'sekolah_asal' => $registration->sekolah_asal,
                'skhun' => $registration->skhun,
                'penerima_kps_kip_pkh' => $registration->penerima_kps_kip_pkh,
                'catatan' => $registration->catatan,
                'ayah_nama' => $registration->ayah_nama,
                'nama_ayah' => $registration->ayah_nama,
                'ayah_tahun_lahir' => $registration->ayah_tahun_lahir,
                'ayah_pendidikan' => $registration->ayah_pendidikan,
                'ayah_pekerjaan' => $registration->ayah_pekerjaan,
                'pekerjaan_ayah' => $registration->ayah_pekerjaan,
                'ayah_penghasilan' => $registration->ayah_penghasilan,
                'ayah_nik' => $registration->ayah_nik,
                'ibu_nama' => $registration->ibu_nama,
                'nama_ibu' => $registration->ibu_nama,
                'ibu_tahun_lahir' => $registration->ibu_tahun_lahir,
                'ibu_pendidikan' => $registration->ibu_pendidikan,
                'ibu_pekerjaan' => $registration->ibu_pekerjaan,
                'pekerjaan_ibu' => $registration->ibu_pekerjaan,
                'ibu_penghasilan' => $registration->ibu_penghasilan,
                'ibu_nik' => $registration->ibu_nik,
                'created_at' => $registration->created_at,
                'updated_at' => $registration->updated_at,
                'verified_at' => $registration->verified_at ?? null,
                'accepted_at' => $registration->accepted_at ?? null,
                'catatan_read' => isset($registration->catatan_read) ? (bool)$registration->catatan_read : true,
                'minta_izin_edit' => isset($registration->minta_izin_edit) ? (bool)$registration->minta_izin_edit : false,
                'edit_allowed' => isset($registration->edit_allowed) ? (bool)$registration->edit_allowed : false,
            ]);

        } catch (\Exception $e) {
            \Log::error('Registration store error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Gagal menyimpan pendaftaran: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getStatus(Request $request)
    {
        $registrationId = $request->query('registration_id') ?: $request->input('registration_id');

        if (!$registrationId) {
            return response()->json([
                'error' => 'Parameter registration_id diperlukan.',
            ], 400);
        }

        $registration = DB::table('registrations')
            ->where('id', $registrationId)
            ->orWhere('nomor_pendaftaran', $registrationId)
            ->first();


        if (!$registration) {
            return response()->json([
                'error' => 'Data pendaftaran tidak ditemukan.',
            ], 404);
        }

        // Explicitly list all fields to ensure no missing data for PDF/Frontend
        return response()->json([
            'id' => $registration->id,
            'debug_v' => 'v2_explicit', // Debug tag
            'nomor_pendaftaran' => $registration->nomor_pendaftaran,
            'status' => $registration->status,
            'nama' => $registration->nama,
            'paket' => $registration->paket,
            'jk' => $registration->jk,
            'nisn' => $registration->nisn,
            'nik' => $registration->nik,
            'tempat_lahir' => $registration->tempat_lahir,
            'tanggal_lahir' => $registration->tanggal_lahir,
            'agama' => $registration->agama,
            'hp' => $registration->hp,
            'email' => $registration->email,
            'alamat' => $registration->alamat,
            'rt_rw' => $registration->rt_rw,
            'dusun' => $registration->dusun,
            'kelurahan_desa' => $registration->kelurahan_desa,
            'desa' => $registration->kelurahan_desa, // frontend mapping
            'kecamatan' => $registration->kecamatan,
            'kode_pos' => $registration->kode_pos,
            'jenis_tinggal' => $registration->jenis_tinggal,
            'transportasi' => $registration->transportasi,
            'telepon' => $registration->telepon,
            'jenis_bank' => $registration->jenis_bank ?? null,
            'no_rekening' => $registration->no_rekening ?? null,
            'sekolah_asal' => $registration->sekolah_asal,
            'skhun' => $registration->skhun,
            'penerima_kps_kip_pkh' => $registration->penerima_kps_kip_pkh,
            'catatan' => $registration->catatan,
            'ayah_nama' => $registration->ayah_nama,
            'nama_ayah' => $registration->ayah_nama, // frontend mapping
            'ayah_tahun_lahir' => $registration->ayah_tahun_lahir,
            'ayah_pendidikan' => $registration->ayah_pendidikan,
            'ayah_pekerjaan' => $registration->ayah_pekerjaan,
            'pekerjaan_ayah' => $registration->ayah_pekerjaan, // frontend mapping
            'ayah_penghasilan' => $registration->ayah_penghasilan,
            'ayah_nik' => $registration->ayah_nik,
            'ibu_nama' => $registration->ibu_nama,
            'nama_ibu' => $registration->ibu_nama, // frontend mapping
            'ibu_tahun_lahir' => $registration->ibu_tahun_lahir,
            'ibu_pendidikan' => $registration->ibu_pendidikan,
            'ibu_pekerjaan' => $registration->ibu_pekerjaan,
            'pekerjaan_ibu' => $registration->ibu_pekerjaan, // frontend mapping
            'ibu_penghasilan' => $registration->ibu_penghasilan,
            'ibu_nik' => $registration->ibu_nik,
            'ayah_alamat' => $registration->ayah_alamat,
            'ibu_alamat' => $registration->ibu_alamat,
            'created_at' => $registration->created_at,
            'updated_at' => $registration->updated_at,
            'verified_at' => $registration->verified_at ?? null,
            'accepted_at' => $registration->accepted_at ?? null,
            'catatan_read' => isset($registration->catatan_read) ? ($registration->catatan_read == 1 || $registration->catatan_read === true || $registration->catatan_read === "1") : true,
            'minta_izin_edit' => isset($registration->minta_izin_edit) ? (bool)$registration->minta_izin_edit : false,
            'edit_allowed' => isset($registration->edit_allowed) ? (bool)$registration->edit_allowed : false,
        ]);


    }

    public function markNoteRead(Request $request, $registrationId)
    {
        try {
            $updated = DB::table('registrations')
                ->where('id', $registrationId)
                ->update(['catatan_read' => true, 'updated_at' => now()]);

            if ($updated > 0) {
                return response()->json(['success' => true, 'message' => 'Catatan ditandai sudah dibaca']);
            }

            return response()->json(['success' => false, 'message' => 'Pendaftaran tidak ditemukan'], 404);
        } catch (\Exception $e) {
            \Log::error('Failed to mark note read', ['error' => $e->getMessage(), 'id' => $registrationId]);
            return response()->json(['success' => false, 'message' => 'Gagal menandai catatan: ' . $e->getMessage()], 500);
        }
    }

    // Student requests permission to edit again
    public function requestEdit(Request $request, $registrationId)
    {
        try {
            $updated = DB::table('registrations')
                ->where('id', $registrationId)
                ->update(['minta_izin_edit' => true, 'updated_at' => now()]);

            if ($updated > 0) {
                return response()->json(['success' => true, 'message' => 'Permintaan edit dikirim ke admin']);
            }

            return response()->json(['success' => false, 'message' => 'Pendaftaran tidak ditemukan'], 404);
        } catch (\Exception $e) {
            \Log::error('Failed to request edit', ['error' => $e->getMessage(), 'id' => $registrationId]);
            return response()->json(['success' => false, 'message' => 'Gagal mengirim permintaan edit: ' . $e->getMessage()], 500);
        }
    }

    // Find registration by email (student lookup)
    // Find registration by email (student lookup)
    public function findByEmail(Request $request)
    {
        $email = $request->query('email');
        $nik = $request->query('nik');

        if (!$email && !$nik) {
            return response()->json(['message' => 'Parameter email atau nik diperlukan'], 400);
        }

        $registration = DB::table('registrations')
            ->where(function($query) use ($email, $nik) {
                if ($email) {
                    $query->whereRaw('LOWER(email) = ?', [strtolower(trim($email))]);
                }
                if ($nik) {
                    $query->orWhere('nik', $nik);
                }
            })
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$registration) {
             \Log::warning('findByEmail/NIK not found', ['email_queried' => $email, 'nik_queried' => $nik]);
            return response()->json(['message' => 'Tidak ditemukan'], 404);
        }

        // Explicitly list all fields to ensure no missing data for PDF/Frontend
        return response()->json([
            'id' => $registration->id,
            'debug_v' => 'v2_explicit', // Debug tag
            'nomor_pendaftaran' => $registration->nomor_pendaftaran,
            'status' => $registration->status,
            'nama' => $registration->nama,
            'paket' => $registration->paket,
            'jk' => $registration->jk,
            'nisn' => $registration->nisn,
            'nik' => $registration->nik,
            'tempat_lahir' => $registration->tempat_lahir,
            'tanggal_lahir' => $registration->tanggal_lahir,
            'agama' => $registration->agama,
            'hp' => $registration->hp,
            'email' => $registration->email,
            'alamat' => $registration->alamat,
            'rt_rw' => $registration->rt_rw,
            'dusun' => $registration->dusun,
            'kelurahan_desa' => $registration->kelurahan_desa,
            'desa' => $registration->kelurahan_desa, // frontend mapping
            'kecamatan' => $registration->kecamatan,
            'kode_pos' => $registration->kode_pos,
            'jenis_tinggal' => $registration->jenis_tinggal,
            'transportasi' => $registration->transportasi,
            'telepon' => $registration->telepon,
            'jenis_bank' => $registration->jenis_bank ?? null,
            'no_rekening' => $registration->no_rekening ?? null,
            'sekolah_asal' => $registration->sekolah_asal,
            'skhun' => $registration->skhun,
            'penerima_kps_kip_pkh' => $registration->penerima_kps_kip_pkh,
            'catatan' => $registration->catatan,
            'ayah_nama' => $registration->ayah_nama,
            'nama_ayah' => $registration->ayah_nama, // frontend mapping
            'ayah_tahun_lahir' => $registration->ayah_tahun_lahir,
            'ayah_pendidikan' => $registration->ayah_pendidikan,
            'ayah_pekerjaan' => $registration->ayah_pekerjaan,
            'pekerjaan_ayah' => $registration->ayah_pekerjaan, // frontend mapping
            'ayah_penghasilan' => $registration->ayah_penghasilan,
            'ayah_nik' => $registration->ayah_nik,
            'ibu_nama' => $registration->ibu_nama,
            'nama_ibu' => $registration->ibu_nama, // frontend mapping
            'ibu_tahun_lahir' => $registration->ibu_tahun_lahir,
            'ibu_pendidikan' => $registration->ibu_pendidikan,
            'ibu_pekerjaan' => $registration->ibu_pekerjaan,
            'pekerjaan_ibu' => $registration->ibu_pekerjaan, // frontend mapping
            'ibu_penghasilan' => $registration->ibu_penghasilan,
            'ibu_nik' => $registration->ibu_nik,
            'ayah_alamat' => $registration->ayah_alamat,
            'ibu_alamat' => $registration->ibu_alamat,
            'created_at' => $registration->created_at,
            'updated_at' => $registration->updated_at,
            'verified_at' => $registration->verified_at ?? null,
            'accepted_at' => $registration->accepted_at ?? null,
            'catatan_read' => isset($registration->catatan_read) ? ($registration->catatan_read == 1 || $registration->catatan_read === true || $registration->catatan_read === "1") : true,
            'minta_izin_edit' => isset($registration->minta_izin_edit) ? (bool)$registration->minta_izin_edit : false,
            'edit_allowed' => isset($registration->edit_allowed) ? (bool)$registration->edit_allowed : false,
        ]);


    }
}