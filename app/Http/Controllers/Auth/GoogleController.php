<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User; // Assuming User model exists
use Illuminate\Support\Facades\Auth;
use Exception;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function handleGoogleCallback()
    {
        try {
            // Use stateless() to prevent session state mismatch issues
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // Cari user berdasarkan email
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Jika belum ada, buat user baru
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(24)),
                    'role' => 'SISWA',
                    'username' => 'GOOGLE-' . substr(time(), -8),
                ]);
            }

            // Login user via web session
            Auth::login($user);

            // Redirect ke beranda
            return redirect('/');
            
        } catch (Exception $e) {
            // Dump full exception for debugging
            dd('Google Login Error:', $e->getMessage(), $e);
        }
    }
}
