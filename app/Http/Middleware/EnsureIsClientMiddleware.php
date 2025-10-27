<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureIsClientMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->guard('sanctum')->user() ?? null;
        if ($user) {

            $input = $request->all();

            array_walk_recursive($input, function (&$value) {
                $value = strip_tags($value);
            });

            $request->merge($input);
            return $next($request);
        }

        return response()->json(["success" => false, 'message' => __('Unauthorized')], 403);
    }
}
