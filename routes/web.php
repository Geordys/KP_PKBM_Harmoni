<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;

Route::get('/', function () {
    return view('siswa.beranda');
})->name('home');

Route::get('/login', function () {
    return view('auth.siswa-login');
})->name('login');

Route::get('/register', function () {
    return view('auth.siswa-register');
})->name('register');

Route::get('/pendaftaran', function () {
    $registration = auth()->check() ? auth()->user()->registrations()->latest()->first() : null;
    return view('siswa.pendaftaran', compact('registration'));
})->name('pendaftaran')->middleware(['auth', 'role:SISWA']);

use App\Http\Controllers\Api\AuthController;

Route::post('/login', [AuthController::class, 'webLoginSiswa'])->name('login.post');
Route::match(['get', 'post'], '/logout', [AuthController::class, 'webLogout'])->name('logout');

// Backward compatibility for old html links
$legacy_siswa_routes = [
    '/pendaftaran.html' => 'pendaftaran',
    '/register_siswa.html' => 'register',
    '/login_siswa.html' => 'login',
    '/beranda_siswa.html' => 'home'
];
foreach ($legacy_siswa_routes as $legacy => $new_route) {
    Route::get($legacy, function () use ($new_route) {
        return redirect()->route($new_route);
    });
}


// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/signin', function () {
        return view('auth.admin-login');
    })->name('admin.login');

    Route::post('/signin', [AuthController::class, 'webLoginAdmin'])->name('admin.login.post');

    Route::middleware(['role:ADMIN'])->group(function () {
        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        Route::get('/pendaftaran', function () {
            return view('admin.kelola-pendaftaran');
        })->name('admin.pendaftaran');

        Route::get('/beranda', function () {
            return view('admin.kelola-beranda');
        })->name('admin.beranda');

        Route::get('/pengaturan', function () {
            return view('admin.pengaturan-akun');
        })->name('admin.pengaturan');

        Route::post('/pengaturan/profil', [AuthController::class, 'updateAdminProfile'])->name('admin.pengaturan.profil');
        Route::post('/pengaturan/password', [AuthController::class, 'updateAdminPassword'])->name('admin.pengaturan.password');

        // Backward compatibility for admin html links
        $legacy_admin_routes = [
            '/dashboard_admin.html' => 'admin.dashboard',
            '/kelola_pendaftaran.html' => 'admin.pendaftaran',
            '/kelola_beranda.html' => 'admin.beranda',
            '/pengaturan_akun.html' => 'admin.pengaturan'
        ];
        foreach ($legacy_admin_routes as $legacy => $new_route) {
            Route::get($legacy, function () use ($new_route) {
                return redirect()->route($new_route);
            });
        }
    });
});


Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);


