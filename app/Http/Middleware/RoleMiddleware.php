<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return $request->is('admin') || $request->is('admin/*') 
                ? redirect()->route('admin.login') 
                : redirect()->route('login');
        }

        $user = auth()->user();
        $userRole = strtoupper(trim($user->role ?? ''));
        $targetRole = strtoupper(trim($role));

        if ($userRole !== $targetRole) {
            // Jika user adalah SISWA tapi mencoba akses ADMIN, tendang ke beranda siswa
            if ($userRole === 'SISWA' && $targetRole === 'ADMIN') {
                return redirect()->route('home')->with('error', 'Akses ditolak: Anda tidak memiliki akses ke area Admin.');
            }
            
            // Jika user adalah ADMIN tapi mencoba akses area SISWA (yang diproteksi), tendang ke dashboard admin
            if ($userRole === 'ADMIN' && $targetRole === 'SISWA') {
                return redirect()->route('admin.dashboard');
            }

            // Default fallback
            abort(403, 'Forbidden: Akses ditolak.');
        }

        return $next($request);
    }
}
