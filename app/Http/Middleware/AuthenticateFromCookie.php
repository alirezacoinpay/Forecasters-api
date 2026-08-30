<?php

namespace App\Http\Middleware;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

            if ($accessToken) {
                Auth::setUser($accessToken->tokenable);
            }
        }

        return $next($request);
    }
}
