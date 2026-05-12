<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\RegistrationController;

Route::get('/ping', fn() => response()->json(['ok' => true]));

Route::post('/auth/login-admin', [AuthController::class, 'loginAdmin']);
Route::post('/auth/register-siswa', [AuthController::class, 'registerSiswa']);
Route::post('/auth/login-siswa', [AuthController::class, 'loginSiswa']);
Route::middleware('admin.token')->post('/auth/logout', [AuthController::class, 'logout']);
Route::middleware('admin.token')->post('/auth/change-password', [AuthController::class, 'changePassword']);
Route::middleware('admin.token')->group(function () {
    Route::get('/admin/stats', [AdminController::class, 'stats']);
    Route::get('/admin/registrations', [AdminController::class, 'index']);
    Route::get('/admin/registrations/excel', [AdminController::class, 'exportExcel']);
    Route::patch('/admin/registrations/status', [AdminController::class, 'updateStatus']);
    Route::delete('/admin/registrations/{nomorPendaftaran}', [AdminController::class, 'destroy']);
    Route::put('/admin/registrations/{nomorPendaftaran}', [AdminController::class, 'update']);
    Route::post('/admin/upload-poster', [AdminController::class, 'uploadPoster']);
    Route::post('/admin/upload-dokumentasi', [AdminController::class, 'uploadDokumentasi']);
    Route::get('/admin/content', [AdminController::class, 'getHomepageContent']);
    Route::post('/admin/content', [AdminController::class, 'updateHomepageContent']);
    Route::post('/admin/upload-hero-bg', [AdminController::class, 'uploadHeroBg']);
    
    // Kelola Guru Routes
    Route::post('/admin/guru/upload-group', [App\Http\Controllers\Api\GuruController::class, 'uploadGroupPhoto']);
    Route::get('/admin/guru', [App\Http\Controllers\Api\GuruController::class, 'index']);
    Route::post('/admin/guru', [App\Http\Controllers\Api\GuruController::class, 'store']);
    Route::get('/admin/guru/{id}', [App\Http\Controllers\Api\GuruController::class, 'show']);
    Route::post('/admin/guru/{id}', [App\Http\Controllers\Api\GuruController::class, 'update']); // Using POST for file upload support in update
    Route::delete('/admin/guru/{id}', [App\Http\Controllers\Api\GuruController::class, 'destroy']);
});

// Public Guru Route for Beranda
Route::get('/guru', [App\Http\Controllers\Api\GuruController::class, 'index']);
Route::get('/guru/{id}/photo', [App\Http\Controllers\Api\GuruController::class, 'photo']);
Route::post('/registrations', [RegistrationController::class, 'store']);
Route::get('/registrations/status', [RegistrationController::class, 'getStatus']);
Route::post('/registrations/check-status', [RegistrationController::class, 'checkStatus']);
// Mark admin note as read by student
Route::post('/registrations/{id}/note-read', [RegistrationController::class, 'markNoteRead']);
Route::post('/registrations/{id}/request-edit', [RegistrationController::class, 'requestEdit']);
Route::get('/registrations/by-email', [RegistrationController::class, 'findByEmail']);
Route::get('/homepage-content', [AdminController::class, 'getHomepageContent']);
