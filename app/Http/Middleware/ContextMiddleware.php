<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Context;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ContextMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $uuid = Str::uuid()->toString();
        Context::add('uuid', $uuid);
        return $next($request);
    }
}
