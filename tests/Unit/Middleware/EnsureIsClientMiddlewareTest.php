<?php

use App\Http\Middleware\EnsureIsClientMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery;

test('it allows authenticated requests', function () {
    $middleware = new EnsureIsClientMiddleware();
    $request = Request::create('/test', 'GET');
    $user = \App\Models\User::factory()->make(['id' => 1]);
    
    Auth::guard('sanctum')->shouldReceive('user')->andReturn($user);
    
    $response = $middleware->handle($request, function ($req) {
        return response()->json(['success' => true]);
    });
    
    expect($response->getStatusCode())->toBe(200);
});

test('it returns 403 for unauthenticated requests', function () {
    $middleware = new EnsureIsClientMiddleware();
    $request = Request::create('/test', 'GET');
    
    Auth::guard('sanctum')->shouldReceive('user')->andReturn(null);
    
    $response = $middleware->handle($request, function ($req) {
        return response()->json(['success' => true]);
    });
    
    expect($response->getStatusCode())->toBe(403);
});

test('it sanitizes input by stripping HTML tags', function () {
    $middleware = new EnsureIsClientMiddleware();
    $request = Request::create('/test', 'POST', [
        'text' => '<script>alert("xss")</script>Hello',
        'nested' => [
            'value' => '<b>Bold</b>',
        ],
    ]);
    $user = \App\Models\User::factory()->make(['id' => 1]);
    
    Auth::guard('sanctum')->shouldReceive('user')->andReturn($user);
    
    $middleware->handle($request, function ($req) {
        expect($req->input('text'))->toBe('Hello');
        expect($req->input('nested.value'))->toBe('Bold');
        return response()->json(['success' => true]);
    });
});

