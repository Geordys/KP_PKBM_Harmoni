<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function loginAdmin(Request $request)
    {
        $username = trim($request->input('username', ''));
        $password = $request->input('password', '');

        if ($username === '' || $password === '') {
            return response()->json(['message' => 'username dan password wajib diisi'], 422);
        }

        $user = DB::table('users')
            ->where('username', $username)
            ->orWhere('email', $username)
            ->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Username/Email atau password salah'], 401);
        }

        if (($user->role ?? '') !== 'ADMIN') {
            return response()->json(['message' => 'akun ini bukan admin'], 403);
        }

        // token sederhana
        $token = bin2hex(random_bytes(32));

        DB::table('auth_tokens')->insert([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->addHours(2),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'login admin berhasil',
            'token' => $token,
            'expires_at' => now()->addHours(2)->toDateTimeString(),
        ]);
    }

    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        if ($token) {
            DB::table('auth_tokens')->where('token', $token)->delete();
        }

        return response()->json(['message' => 'logout berhasil']);
    }

    public function changePassword(Request $request)
    {
        // 1. Validate
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi',
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.min' => 'Password baru minimal 8 karakter',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        // 2. Get User
        $userId = $request->input('auth_user_id');
        $user = DB::table('users')->where('id', $userId)->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        // 3. Check Current Password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Password lama salah'], 400);
        }

        // 4. Update Password
        DB::table('users')->where('id', $userId)->update([
            'password' => Hash::make($request->new_password),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Password berhasil diubah']);
    }

    public function registerSiswa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        $user = DB::table('users')->insertGetId([
            'name' => $request->nama_lengkap, // also store in generic name
            'email' => $request->email,
            'username' => $request->email, // Use email as username
            'password' => Hash::make($request->password),
            'role' => 'SISWA',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Pendaftaran berhasil',
            'user' => [
                'id' => $user,
                'email' => $request->email,
                'nama_lengkap' => $request->nama_lengkap,
                'role' => 'SISWA'
            ]
        ], 201);
    }

    public function loginSiswa(Request $request)
    {
        $email = trim($request->input('email', ''));
        $password = $request->input('password', '');

        if ($email === '' || $password === '') {
            return response()->json(['message' => 'Email dan password wajib diisi'], 422);
        }

        $user = DB::table('users')
            ->where('email', $email)
            ->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        if (($user->role ?? '') !== 'SISWA' && ($user->role ?? '') !== 'USER') {
            return response()->json(['message' => 'Akun ini bukan siswa'], 403);
        }

        $token = bin2hex(random_bytes(32));

        DB::table('auth_tokens')->insert([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->addHours(2),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Login siswa berhasil',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'role' => $user->role
            ],
            'expires_at' => now()->addHours(2)->toDateTimeString(),
        ]);
    }

    public function webLoginSiswa(Request $request)
    {
        $email = trim($request->input('email', ''));
        $password = $request->input('password', '');

        if ($email === '' || $password === '') {
            return response()->json(['message' => 'Email dan password wajib diisi'], 422);
        }

        $user = DB::table('users')->where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        if (($user->role ?? '') !== 'SISWA' && ($user->role ?? '') !== 'USER') {
            return response()->json(['message' => 'Akun ini bukan siswa'], 403);
        }

        // Login using Laravel Auth session
        \Illuminate\Support\Facades\Auth::loginUsingId($user->id);
        $request->session()->regenerate();

        return response()->json([
            'message' => 'Login siswa berhasil',
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'role' => $user->role
            ]
        ]);
    }

    public function webLoginAdmin(Request $request)
    {
        $username = trim($request->input('username', ''));
        $password = $request->input('password', '');

        if ($username === '' || $password === '') {
            return response()->json(['message' => 'Username/Email dan password wajib diisi'], 422);
        }

        $user = DB::table('users')
            ->where('username', $username)
            ->orWhere('email', $username)
            ->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json(['message' => 'Username atau password salah'], 401);
        }

        if (strtoupper($user->role ?? '') !== 'ADMIN') {
            return response()->json(['message' => 'Akun ini bukan admin'], 403);
        }

        // Login using Laravel Auth session
        \Illuminate\Support\Facades\Auth::loginUsingId($user->id);
        $request->session()->regenerate();

        // Also generate API token for dashboard components that use API
        $token = bin2hex(random_bytes(32));
        DB::table('auth_tokens')->insert([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->addHours(2),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Login admin berhasil',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'role' => $user->role
            ]
        ]);
    }

    public function webLogout(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        
        // Deteksi apakah ini logout admin: 
        // 1. Berdasarkan role user saat ini
        // 2. Berdasarkan asal halaman (referer)
        $isAdmin = ($user && strtoupper(trim($user->role ?? '')) === 'ADMIN') || 
                   str_contains($request->header('referer', ''), '/admin');

        \Illuminate\Support\Facades\Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($isAdmin) {
            return redirect()->route('admin.login');
        }

        return redirect('/');
    }

    public function updateAdminProfile(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user || strtoupper($user->role) !== 'ADMIN') return response()->json(['message' => 'Unauthorized'], 403);

        $request->validate([
            'username' => 'required|string|unique:users,username,' . $user->id,
        ], [
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan'
        ]);

        DB::table('users')->where('id', $user->id)->update([
            'username' => $request->username,
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Profil berhasil diperbarui']);
    }

    public function updateAdminPassword(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        if (!$user || strtoupper($user->role) !== 'ADMIN') return response()->json(['message' => 'Unauthorized'], 403);

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8',
            'new_password_confirmation' => 'required|same:new_password',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi',
            'new_password.required' => 'Password baru wajib diisi',
            'new_password.min' => 'Password baru minimal 8 karakter',
            'new_password_confirmation.required' => 'Konfirmasi password wajib diisi',
            'new_password_confirmation.same' => 'Konfirmasi password tidak cocok',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Password lama salah'], 400);
        }

        DB::table('users')->where('id', $user->id)->update([
            'password' => Hash::make($request->new_password),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Password berhasil diubah']);
    }
}
