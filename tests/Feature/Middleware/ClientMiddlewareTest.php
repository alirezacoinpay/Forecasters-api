<?php

use App\Http\Middleware\EnsureIsClientMiddleware;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

test('it sanitizes input by stripping HTML tags', function () {
    $middleware = new EnsureIsClientMiddleware();
    $user = User::factory()->make(['id' => 1]);
    
    $guard = Mockery::mock(\Illuminate\Contracts\Auth\Guard::class);
    $guard->shouldReceive('user')->andReturn($user);
    Auth::shouldReceive('guard')->with('sanctum')->andReturn($guard);
    
    $request = Request::create('/test', 'POST', [
        'text' => '<script>alert("xss")</script>Hello',
    ]);
    
    $middleware->handle($request, function ($req) {
        // strip_tags() removes HTML tags but keeps text content
        expect($req->input('text'))->toBe('alert("xss")Hello');
        return response()->json(['success' => true]);
    });
});

test('it allows authenticated requests', function () {
    $middleware = new EnsureIsClientMiddleware();
    $user = User::factory()->make(['id' => 1]);
    
    $guard = Mockery::mock(\Illuminate\Contracts\Auth\Guard::class);
    $guard->shouldReceive('user')->andReturn($user);
    Auth::shouldReceive('guard')->with('sanctum')->andReturn($guard);
    
    $request = Request::create('/test', 'GET');
    
    $response = $middleware->handle($request, function ($req) {
        return response()->json(['success' => true]);
    });
    
    expect($response->getStatusCode())->toBe(200);
});

