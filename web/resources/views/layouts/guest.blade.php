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
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        
        <div>
            <a href="/" class="text-3xl font-bold text-blue-600">
                US Service Animals
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            @yield('content')
            
            {{ $slot ?? '' }}
        </div>
    </div>
    @livewireScripts
</body>
</html>