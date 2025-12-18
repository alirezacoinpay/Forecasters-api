<?php

use App\Http\Middleware\EnsureIsClientMiddleware;
use App\Models\User;
use Illuminate\Http\Request;

test('it sanitizes input by stripping HTML tags', function () {
    $middleware = new EnsureIsClientMiddleware();
    $user = User::factory()->make(['id' => 1]);
    
    auth()->guard('sanctum')->shouldReceive('user')->andReturn($user);
    
    $request = Request::create('/test', 'POST', [
        'text' => '<script>alert("xss")</script>Hello',
    ]);
    
    $middleware->handle($request, function ($req) {
        expect($req->input('text'))->toBe('Hello');
        return response()->json(['success' => true]);
    });
});

test('it allows authenticated requests', function () {
    $middleware = new EnsureIsClientMiddleware();
    $user = User::factory()->make(['id' => 1]);
    $request = Request::create('/test', 'GET');
    
    auth()->guard('sanctum')->shouldReceive('user')->andReturn($user);
    
    $response = $middleware->handle($request, function ($req) {
        return response()->json(['success' => true]);
    });
    
    expect($response->getStatusCode())->toBe(200);
});

