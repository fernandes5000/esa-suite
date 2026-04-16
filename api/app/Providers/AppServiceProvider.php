<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Sanctum::getAccessTokenFromRequestUsing(function ($request) {
            if ($request->has('token') && ! $request->bearerToken()) {
                return $request->query('token');
            }

            return $request->bearerToken();
        });
    }
}