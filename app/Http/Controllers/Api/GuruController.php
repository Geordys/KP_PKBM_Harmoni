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
        $validator = Validator::make($request->all(), [
            'foto_group' => 'required|image|mimes:jpeg,png,jpg|max:10240', // Max 10MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->hasFile('foto_group')) {
            $file = $request->file('foto_group');
            // Save directly to public/assets/guru-pkbm.jpg to overwrite the existing one
            // Note: We use move() to public_path() because this is a static asset not in storage link
            $destinationPath = public_path('assets');
            $fileName = 'guru-pkbm.jpg';
            
            $file->move($destinationPath, $fileName);

            return response()->json([
                'success' => true,
                'message' => 'Foto group berhasil diperbarui',
                'url' => asset('assets/guru-pkbm.jpg') . '?t=' . time()
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'File tidak ditemukan'
        ], 400);
    }
}
