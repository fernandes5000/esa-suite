<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? config('app.name') }}</title>
    
    <link href="{{ asset('css/tailwind.css') }}?v={{ filemtime(public_path('css/tailwind.css')) }}" rel="stylesheet">
    @livewireStyles
</head>
<body class="font-sans bg-gray-100 text-gray-800 antialiased">
    <div class="min-h-screen flex flex-col">
        
        <nav x-data="{ mobileOpen: false }" class="bg-white border-b border-gray-100 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <a href="{{ route('dashboard') }}" class="text-xl font-bold text-blue-600">
                                {{ config('app.name') }}
                            </a>
                        </div>
                        
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('dashboard') }}" 
                               class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium transition">
                                {{ __('Dashboard') }}
                            </a>
                            <a href="{{ route('pets.index') }}" 
                               class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('pets.index') ? 'border-blue-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium transition">
                                {{ __('My Pets') }}
                            </a>

                            @if(session()->has('roles') && in_array('admin', session('roles')))
                                <div class="relative" x-data="{ adminOpen: false }">
                                    <button @click="adminOpen = !adminOpen" 
                                            class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.*') ? 'border-red-500 text-gray-900' : 'border-transparent text-red-500 hover:text-red-700 hover:border-red-300' }} text-sm font-medium transition h-full">
                                        <span>{{ __('Admin') }}</span>
                                        <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    
                                    <div x-show="adminOpen" 
                                         @click.away="adminOpen = false"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="transform opacity-0 scale-95"
                                         x-transition:enter-end="transform opacity-100 scale-100"
                                         x-transition:leave="transition ease-in duration-75"
                                         x-transition:leave-start="transform opacity-100 scale-100"
                                         x-transition:leave-end="transform opacity-0 scale-95"
                                         class="absolute z-50 mt-2 w-48 rounded-md shadow-lg origin-top-right right-0"
                                         style="display: none;">
                                        <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
                                            <a href="{{ route('admin.users.index') }}" 
                                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                               {{ __('User Management') }}
                                            </a>
                                            </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <div class="relative ml-3">
                            @php
                                $locales = ['en' => '🇺🇸', 'es' => '🇪🇸', 'pt_BR' => '🇧🇷'];
                                $currentLocale = app()->getLocale();
                            @endphp
                            
                            <button class="flex items-center text-xl" onclick="this.nextElementSibling.classList.toggle('hidden')">
                                {{ $locales[$currentLocale] }}
                            </button>
                            
                            <div class="absolute right-0 mt-2 w-20 bg-white rounded-md shadow-lg py-1 z-10 hidden" onclick="this.classList.toggle('hidden')">
                                @foreach ($locales as $locale => $flag)
                                    @if ($locale != $currentLocale)
                                        <a href="{{ route('language.switch', $locale) }}" 
                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 text-center text-xl">
                                            {{ $flag }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        
                        <span class="text-sm text-gray-700 mr-4 ml-4">
                            {{ __('Welcome') }}, {{ session('user_name', 'User') }}
                        </span>
                        
                        <form method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button type="submit" 
                                    class="text-sm text-gray-500 hover:text-red-500 transition">
                                {{ __('Logout') }}
                            </button>
                        </form>
                    </div>

                    <div class="-mr-2 flex items-center sm:hidden">
                        <button @click="mobileOpen = !mobileOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{'hidden': mobileOpen, 'inline-flex': !mobileOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{'hidden': !mobileOpen, 'inline-flex': mobileOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                </div>
            </div>

            <div x-show="mobileOpen" class="sm:hidden" style="display: none;">
                <div class="pt-2 pb-3 space-y-1">
                    <a href="{{ route('dashboard') }}" 
                       class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('dashboard') ? 'border-blue-500 text-blue-700 bg-blue-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium transition">
                        {{ __('Dashboard') }}
                    </a>
                    <a href="{{ route('pets.index') }}" 
                       class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('pets.index') ? 'border-blue-500 text-blue-700 bg-blue-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium transition">
                        {{ __('My Pets') }}
                    </a>
                </div>

                @if(session()->has('roles') && in_array('admin', session('roles')))
                    <div class="pt-4 pb-3 border-t border-gray-200">
                        <div class="px-4">
                            <span class="text-xs text-gray-400 uppercase font-medium">{{ __('Admin Panel') }}</span>
                        </div>
                        <div class="mt-3 space-y-1">
                            <a href="{{ route('admin.users.index') }}" 
                               class="block pl-3 pr-4 py-2 border-l-4 {{ request()->routeIs('admin.*') ? 'border-red-500 text-red-700 bg-red-50' : 'border-transparent text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800' }} text-base font-medium transition">
                                {{ __('User Management') }}
                            </a>
                        </div>
                    </div>
                @endif

                <div class="pt-4 pb-3 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">{{ session('user_name', 'User') }}</div>
                        </div>
                    <div class="mt-3 space-y-1">
                        @foreach ($locales as $locale => $flag)
                            @if ($locale != $currentLocale)
                                <a href="{{ route('language.switch', $locale) }}" 
                                   class="block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 transition">
                                    <span class="mr-2">{{ $flag }}</span> 
                                    @if($locale == 'es') Español @elseif($locale == 'pt_BR') Português @else English @endif
                                </a>
                            @endif
                        @endforeach
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 transition">
                                {{ __('Logout') }}
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
            {{ __('© :year ESA Project Demo. All rights reserved.', ['year' => date('Y')]) }}
        </footer>
    </div>
    
    @livewireScripts
    <script src="{{ asset('js/alpine.js') }}?v={{ filemtime(public_path('js/alpine.js')) }}" defer></script>
</body>
</html>