<?php

namespace Tests\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthHelpers
{
    /**
     * Create a test user
     */
    public static function createUser(array $attributes = []): User
    {
        return User::factory()->make($attributes);
    }

    /**
     * Create an authenticated user with token
     */
    public static function createAuthenticatedUser(array $attributes = []): User
    {
        $user = self::createUser($attributes);
        $user->createToken('test-token');
        
        return $user;
    }

    /**
     * Get authentication headers for a user
     */
    public static function authHeaders(User $user): array
    {
        $token = $user->createToken('test-token')->plainTextToken;
        
        return [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
    }
}

