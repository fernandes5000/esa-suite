<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PetCertify - @yield('title')</title>
    
    <link href="{{ asset('css/tailwind.css') }}?v={{ filemtime(public_path('css/tailwind.css')) }}" rel="stylesheet">
    @livewireStyles
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-md">
            <div class="text-center mb-10">
                <h1 class="text-5xl font-bold text-blue-600">PetCertify</h1>
            </div>
            <div class="bg-white shadow-2xl rounded-2xl p-10">
                @yield('content')
            </div>
        </div>
    </div>

    @livewireScripts
    <script src="{{ asset('js/alpine.js') }}?v={{ filemtime(public_path('js/alpine.js')) }}" defer></script>
</body>
</html>