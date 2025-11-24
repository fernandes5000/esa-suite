<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>
    
    <link href="/css/app.css" rel="stylesheet">
    <script src="/js/app.js" defer></script>
    @livewireStyles
</head>
<body class="font-sans bg-gray-100 text-gray-800 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <a href="/" class="text-3xl font-bold text-blue-600">
                {{ config('app.name') }}
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            @yield('content')
            {{ $slot ?? '' }}
        </div>
    </div>
    @livewireScripts
    <script>
        document.addEventListener('alpine:init', () => {
            window.Alpine = Alpine;
            Alpine.start();
        });
    </script>
</body>
</html>