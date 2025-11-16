<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next)
    {
        if (!session()->has('roles') || !in_array('admin', session('roles'))) {
            
            return redirect('/dashboard')->with('error', __('Unauthorized access.'));
        }

        return $next($request);
    }
}