<?php

use App\Http\Middleware\EnsureIsClientMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

uses(TestCase::class);

test('it allows authenticated requests', function () {
    $middleware = new EnsureIsClientMiddleware();
    $request = Request::create('/test', 'GET');
    $user = \App\Models\User::factory()->make(['id' => 1]);
    
    $guard = Mockery::mock(\Illuminate\Contracts\Auth\Guard::class);
    $guard->shouldReceive('user')->andReturn($user);
    Auth::shouldReceive('guard')->with('sanctum')->andReturn($guard);
    
    $response = $middleware->handle($request, function ($req) {
        return response()->json(['success' => true]);
    });
    
    expect($response->getStatusCode())->toBe(200);
});

test('it returns 403 for unauthenticated requests', function () {
    $middleware = new EnsureIsClientMiddleware();
    $request = Request::create('/test', 'GET');
    
    $guard = Mockery::mock(\Illuminate\Contracts\Auth\Guard::class);
    $guard->shouldReceive('user')->andReturn(null);
    Auth::shouldReceive('guard')->with('sanctum')->andReturn($guard);
    
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
    
    $guard = Mockery::mock(\Illuminate\Contracts\Auth\Guard::class);
    $guard->shouldReceive('user')->andReturn($user);
    Auth::shouldReceive('guard')->with('sanctum')->andReturn($guard);
    
    $middleware->handle($request, function ($req) {
        // strip_tags() removes HTML tags but keeps text content
        // So <script>alert("xss")</script>Hello becomes alert("xss")Hello
        expect($req->input('text'))->toBe('alert("xss")Hello');
        expect($req->input('nested.value'))->toBe('Bold');
        return response()->json(['success' => true]);
    });
});

