@extends('layouts.guest')

@section('content')

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">{{ __('Email') }}</label>
            <input id="email" type="email" name="email"
                   class="block w-full mt-1 px-3 py-2 border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50" 
                   required autofocus>
        </div>

        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>
            <input id="password" type="password" name="password"
                   class="block w-full mt-1 px-3 py-2 border-gray-300 rounded-md shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                   required>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="text-sm text-gray-600 hover:text-gray-900 underline" href="{{ route('register') }}">
                {{ __('Need an account?') }}
            </a>

            <button type="submit" 
                    class="ml-4 inline-flex items-center ...">
                {{ __('Log in') }}
            </button>
        </div>
    </form>
@endsection