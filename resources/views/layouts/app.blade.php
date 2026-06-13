<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: false, collapsed: window.innerWidth >= 1024 ? false : true }" 
             @resize.window="if(window.innerWidth >= 1024) { sidebarOpen = false; } collapsed = window.innerWidth < 1024"
             class="flex h-screen bg-gray-100 overflow-hidden relative">
             
            <!-- Overlay para móviles -->
            <div x-show="sidebarOpen" 
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900/80 z-20 lg:hidden" 
                 @click="sidebarOpen = false"></div>

            <!-- Sidebar -->
            @include('layouts.sidebar')

            <!-- Contenido Principal -->
            <div class="flex-1 flex flex-col overflow-hidden w-full relative z-0">
                <!-- Mobile Header -->
                <div class="lg:hidden bg-indigo-600 shadow flex items-center justify-between p-4 z-10">
                    <div class="flex items-center">
                        <span class="text-white font-bold text-xl tracking-wider">CUP FICCT</span>
                    </div>
                    <button @click="sidebarOpen = true" class="text-white hover:text-indigo-200 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white rounded-md">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                <!-- Page Heading (Si es necesario, como topbar simple) -->
                @isset($header)
                    <header class="bg-white shadow z-10 relative">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Contenido scrolleable -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
