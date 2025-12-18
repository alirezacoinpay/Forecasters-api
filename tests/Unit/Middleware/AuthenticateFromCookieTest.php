<?php

use App\Http\Middleware\AuthenticateFromCookie;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

test('it authenticates user from cookie token', function () {
    $middleware = new AuthenticateFromCookie();
    $user = User::factory()->make(['id' => 1]);
    $token = Mockery::mock(PersonalAccessToken::class);
    $token->tokenable = $user;
    
    $request = Request::create('/test', 'GET');
    $request->cookies->set('auth_user', 'test-token');
    
    PersonalAccessToken::shouldReceive('findToken')
        ->with('test-token')
        ->once()
        ->andReturn($token);
    
    Auth::shouldReceive('setUser')->with($user)->once();
    
    $response = $middleware->handle($request, function ($req) {
        return response()->json(['success' => true]);
    });
    
    expect($response->getStatusCode())->toBe(200);
});

test('it continues without authentication when no cookie', function () {
    $middleware = new AuthenticateFromCookie();
    $request = Request::create('/test', 'GET');
    
    $response = $middleware->handle($request, function ($req) {
        return response()->json(['success' => true]);
    });
    
    expect($response->getStatusCode())->toBe(200);
});

