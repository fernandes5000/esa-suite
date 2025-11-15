<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    protected $supportedLocales = ['en', 'es', 'pt_BR'];

    public function handle(Request $request, Closure $next)
    {
        if (Session::has('locale') && in_array(Session::get('locale'), $this->supportedLocales)) {
            App::setLocale(Session::get('locale'));
        } else {
            $browserLang = $request->getPreferredLanguage($this->supportedLocales);
            
            $locale = $browserLang ?? config('app.fallback_locale'); // 'en'

            App::setLocale($locale);
            Session::put('locale', $locale);
        }

        return $next($request);
    }
}