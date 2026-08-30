<?php

namespace App\Http\Middleware;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;

class AuthenticateFromCookie
{
    public function handle($request, \Closure $next)
    {
        if ($request->is('admin')) {
            return $next($request);
        }

        $token = $request->cookie('auth_user');

        if ($token) {
            $accessToken = PersonalAccessToken::findToken($token);

            // Check if the token is valid
            if ($accessToken && $accessToken->tokenable) {
                Auth::setUser($accessToken->tokenable);
            } else {
                // Log the invalid token attempt (optional)
                Log::warning('Invalid token attempt: ' . $token);
            }
        }

        return $next($request);
    }
}
