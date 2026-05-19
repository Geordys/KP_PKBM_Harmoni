<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1) Try session-based auth first (web login via webLoginAdmin)
        if (auth()->check()) {
            $user = auth()->user();
            if (strtoupper($user->role ?? '') === 'ADMIN') {
                $request->merge(['auth_user_id' => $user->id]);
                return $next($request);
            }
            return response()->json(['message' => 'Akses ditolak: Anda bukan admin'], 403);
        }

        // 2) Fallback: Bearer token (legacy API clients)
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Token tidak ditemukan atau akses ditolak'], 403);
        }

        $auth = \DB::table('auth_tokens')
            ->where('token', $token)
            ->first();

        if (!$auth) {
            return response()->json(['message' => 'Token tidak valid, expired, atau akses ditolak'], 403);
        }

        $user = \DB::table('users')->where('id', $auth->user_id)->first();
        if (!$user || strtoupper($user->role ?? '') !== 'ADMIN') {
            return response()->json(['message' => 'Akses ditolak: Anda bukan admin'], 403);
        }

        $request->merge(['auth_user_id' => $auth->user_id]);

        return $next($request);
    }
}
