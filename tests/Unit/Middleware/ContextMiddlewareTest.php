<?php

use App\Http\Middleware\ContextMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Context;
use Tests\TestCase;

uses(TestCase::class);

test('it adds UUID to context', function () {
    $middleware = new ContextMiddleware();
    $request = Request::create('/test', 'GET');
    
    Context::shouldReceive('add')->with('uuid', Mockery::type('string'))->once();
    
    $response = $middleware->handle($request, function ($req) {
        return response()->json(['success' => true]);
    });
    
    expect($response->getStatusCode())->toBe(200);
});

