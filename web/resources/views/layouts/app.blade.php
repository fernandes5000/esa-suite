<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'ESA Project' }}</title>
    
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="font-sans bg-gray-100 text-gray-800 antialiased">
    <div class="min-h-screen flex flex-col">
        
        <nav class="bg-white border-b border-gray-100 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="text-xl font-bold text-blue-600">
                                US Service Animals
                            </a>
                        </div>
                        
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('dashboard') }}" 
                               class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium transition">
                                Dashboard
                            </a>
                            <a href="{{ route('pets.index') }}" 
                               class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('pets.index') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium transition">
                                My Pets
                            </a>
                        </div>
                    </div>
                    
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <span class="text-sm text-gray-700 mr-4">
                            Welcome, {{ session('user_name', 'User') }}
                        </span>
                        
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" 
                                    class="text-sm text-gray-500 hover:text-red-500 transition">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h1 class="text-3xl font-bold text-gray-900">
                        {{ $header }}
                    </h1>
                </div>
            </header>
        @endif

        <main class="flex-grow container mx-auto p-6">
            @yield('content')
            
            {{ $slot ?? '' }} 
        </main>
        
        <footer class="bg-gray-200 p-4 text-center text-sm text-gray-600 mt-auto">
            &copy; {{ date('Y') }} ESA Project Demo. All rights reserved.
        </footer>
    </div>
    
    @livewireScripts
</body>
</html>