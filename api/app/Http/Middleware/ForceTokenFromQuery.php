<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ForceTokenFromQuery
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('token') && !$request->header('Authorization')) {
            $request->headers->set('Authorization', 'Bearer ' . $request->input('token'));
        }

        return $next($request);
    }
}