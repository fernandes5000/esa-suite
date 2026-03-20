<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ApiClient;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function register(Request $request, ApiClient $api)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $response = $api->post('/v1/auth/register', [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation,
        ]);

        if (!($response['ok'] ?? false)) {
            throw ValidationException::withMessages([
                'email' => $response['error'] ?? __('Registration failed.'),
            ]);
        }

        session([
            'api_token' => $response['data']['token'],
            'user_name' => $response['data']['user']['name'],
            'roles'     => $response['data']['roles'],
            'auth_id'   => $response['data']['user_id']
        ]);

        session()->save();

        return redirect()->route('dashboard');
    }
}