<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ApiClient;

class LoginController extends Controller
{
    public function show()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required','email'],
            'password' => ['required'],
        ]);

        $api = new ApiClient();
        $response = $api->post('/v1/auth/login', $request->only('email','password'));


        if (!($response['ok'] ?? false)) {
            return back()->withErrors([
                'email' => $response['error'] ?? 'Invalid credentials.',
            ]);
        }

        session(['api_token' => $response['token']]);

        return redirect()->route('dashboard');
    }
}
