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

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => ['required','email'],
    //         'password' => ['required'],
    //     ]);

    //     $api = new ApiClient();
    //     $response = $api->post('/v1/auth/login', $request->only('email','password'));

    //     if (!($response['ok'] ?? false)) {
    //         return back()->withErrors([
    //             'email' => $response['error'] ?? 'Invalid credentials.',
    //         ]);
    //     }

    //     session([
    //         'api_token' => $response['data']['token'],
    //         'user_name' => $response['data']['user']['name'],
    //         'roles'     => $response['data']['roles'],
    //         'auth_id'   => $response['data']['user_id']
    //     ]);

    //     return redirect()->route('dashboard');
    // }

    public function login(Request $request)
{
    $request->validate([
        'email' => ['required','email'],
        'password' => ['required'],
    ]);

    $api = new ApiClient();
    $response = $api->post('/v1/auth/login', $request->only('email','password'));

    \Log::info('API Login Response', ['response' => $response]);

    if (!isset($response['ok']) || $response['ok'] !== true) {
        $error = $response['error'] ?? $response['message'] ?? 'Invalid credentials.';
        return back()->withErrors(['email' => $error])->withInput();
    }

    $data = $response['data'] ?? $response;

    $token = $data['token'] ?? $data['access_token'] ?? null;
    $user = $data['user'] ?? $data['user_data'] ?? null;

    if (!$token || !$user) {
        return back()->withErrors(['email' => 'Invalid response from server.']);
    }

    session([
        'api_token' => $token,
        'user_name' => $user['name'] ?? $user['full_name'] ?? 'User',
        'roles'     => $data['roles'] ?? [],
        'auth_id'   => $user['id'] ?? $data['user_id'] ?? null,
    ]);

    return redirect()->route('dashboard');
}
}
