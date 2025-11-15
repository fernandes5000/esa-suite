@extends('layouts.app')

@section('content')
    <div class="p-8 bg-white rounded-lg shadow-md w-full max-w-md mx-auto">
        
        <h1 class="text-2xl font-bold text-center text-gray-800 mb-6">
            Dashboard
        </h1>
        
        <p class="text-center text-gray-600 mb-6">
            You are logged in.
        </p>

        <a href="{{ route('pets.index') }}" 
           class="block w-full text-center bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mb-4 no-underline transition">
            My Pets
        </a>
    </div>
@endsection