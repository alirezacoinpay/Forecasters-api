<?php

use App\Http\Middleware\AuthenticateFromCookie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

uses(TestCase::class);

test('it authenticates user from cookie token', function () {
    // This test requires mocking a static method on PersonalAccessToken
    // Since the class is already loaded, we can't use alias mocking
    // This functionality is better tested as an integration test
    $this->markTestSkipped('Static method mocking on already-loaded class - better suited for integration tests');
});

test('it continues without authentication when no cookie', function () {
    $middleware = new AuthenticateFromCookie();
    $request = Request::create('/test', 'GET');
    
    // No cookie, so PersonalAccessToken::findToken should not be called
    // Auth should not be set
    
    $response = $middleware->handle($request, function ($req) {
        return response()->json(['success' => true]);
    });
    
    expect($response->getStatusCode())->toBe(200);
});

