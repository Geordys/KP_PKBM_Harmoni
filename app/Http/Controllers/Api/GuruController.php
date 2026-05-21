<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Log;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Sort by category (pimpinan first) and then by ID/created_at
        $guru = Guru::orderByRaw("FIELD(kategori, 'pimpinan', 'pendidik')")
                    ->orderBy('id', 'asc')
                    ->get();
                    
        // Add full URL to photo with cache buster
        $guru->transform(function ($item) {
            if ($item->foto) {
                // Use updated_at timestamp as cache buster to ensure latest image is shown
                $timestamp = $item->updated_at ? $item->updated_at->timestamp : time();
                $item->foto_url = '/api/guru/' . $item->id . '/photo?t=' . $timestamp;
            } else {
                $item->foto_url = null; 
            }
            return $item;
        });

        return response()->json([
            'success' => true,
            'data' => $guru
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('Store Guru Request:', $request->all());
        Log::info('Has File Foto: ' . ($request->hasFile('foto') ? 'Yes' : 'No'));

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'kategori' => 'required|in:pimpinan,pendidik',
            'mapel' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $data = $request->only(['nama', 'jabatan', 'kategori', 'mapel']);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('guru', 'public');
            $data['foto'] = $path;
        }

        $guru = Guru::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil ditambahkan.',
            'data' => $guru
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $guru = Guru::find($id);
        if (!$guru) return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
        
        if ($guru->foto) {
            $timestamp = $guru->updated_at ? $guru->updated_at->timestamp : time();
            $guru->foto_url = url('api/guru/' . $guru->id . '/photo?t=' . $timestamp);
        }

        return response()->json(['success' => true, 'data' => $guru]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $guru = Guru::find($id);
        if (!$guru) return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);

        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'kategori' => 'required|in:pimpinan,pendidik',
            'mapel' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        try {
            $data = $request->only(['nama', 'jabatan', 'kategori', 'mapel']);

            if ($request->hasFile('foto')) {
                // Delete old photo
                if ($guru->foto) {
                    Storage::disk('public')->delete($guru->foto);
                }
                $path = $request->file('foto')->store('guru', 'public');
                $data['foto'] = $path;
            }

            $guru->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Data guru berhasil diperbarui.',
                'data' => $guru
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating guru: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal update data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function photo(string $id)
    {
        $guru = Guru::find($id);
        if (!$guru || !$guru->foto) {
            abort(404);
        }
        $path = Storage::disk('public')->path($guru->foto);
        if (!is_file($path)) {
            abort(404);
        }
        return response()->file($path);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $guru = Guru::find($id);
        if (!$guru) return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);

        if ($guru->foto) {
            Storage::disk('public')->delete($guru->foto);
        }

        $guru->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data guru berhasil dihapus.'
        ]);
    }
    /**
     * Upload Group Photo
     */
    public function uploadGroupPhoto(Request $request)
    {
        $request->validate([
            'foto_group' => 'required|image|mimes:jpeg,png,jpg,jfif|max:10240',
        ]);

        if ($request->hasFile('foto_group')) {
            try {
                $file = $request->file('foto_group');
                $destinationPath = public_path('assets');
                $filename = 'guru-pkbm.jpg';
                
                // Log untuk debugging
                Log::info('Upload Group Photo', [
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'destination' => $destinationPath,
                    'destination_exists' => is_dir($destinationPath),
                    'destination_writable' => is_writable($destinationPath)
                ]);
                
                // Pastikan directory ada
                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                
                // Check if writable
                if (!is_writable($destinationPath)) {
                    Log::error('Directory not writable', ['path' => $destinationPath]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Direktori tidak memiliki izin tulis. Hubungi administrator.'
                    ], 500);
                }
                
                $file->move($destinationPath, $filename);
                
                Log::info('Group photo uploaded successfully', ['filename' => $filename]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Foto group berhasil diperbarui.',
                    'url' => asset('assets/' . $filename . '?t=' . time())
                ], 200);
            } catch (\Exception $e) {
                Log::error('Error uploading group photo', [
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal upload file: ' . $e->getMessage()
                ], 500);
            }
        }
        return response()->json(['success' => false, 'message' => 'No file'], 400);
    }
}