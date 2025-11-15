<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-800">

    <div class="min-h-screen flex items-center justify-center">
        <div class="p-8 bg-white rounded-lg shadow-md w-full max-w-md">
            
            <h1 class="text-3xl font-bold text-center text-blue-600 mb-4">
                Welcome, {{ session('user_name', 'User') }}
            </h1>
            
            <p class="text-center text-gray-600 mb-6">You are logged in.</p>

            <a href="{{ route('pets.index') }}" 
               class="block w-full text-center bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded mb-4 no-underline">
                My Pets
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="block w-full text-center bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                    Logout
                </button>
            </form>
        </div>
    </div>
</body>
</html>