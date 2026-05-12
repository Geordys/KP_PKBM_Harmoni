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
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Token tidak ditemukan'], 401);
        }

        $auth = \DB::table('auth_tokens')
            ->where('token', $token)
            // ->where('expires_at', '>', now())
            ->first();

        if (!$auth) {
            return response()->json(['message' => 'Token tidak valid atau expired'], 401);
        }

        // Set user
        $request->merge(['auth_user_id' => $auth->user_id]);

        return $next($request);
    }
}
