<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ApiClient;

class LogoutController extends Controller
{
    public function logout(Request $request, ApiClient $api)
    {
        $api->authedPost('/v1/auth/logout');

        $request->session()->flush();

        return redirect()->route('login');
    }
}