<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Portal da Secretaria') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 overflow-x-hidden">
        <div class="min-h-screen flex overflow-hidden">
            <!-- Sidebar -->
            @include('components.sidebar-secretaria')

            <!-- Page Content -->
            <div class="flex-1 flex flex-col min-h-screen">
                <!-- Top Navigation -->
                <nav class="bg-white border-b border-gray-100 flex items-center justify-between h-16" style="padding-left: 32px; padding-right: 48px;">
                    <div class="flex items-center min-w-0 flex-1 mr-4">
                        <span class="text-gray-500 font-medium mr-4 flex-shrink-0">Portal da Secretaria</span>
                        <div class="truncate">
                            <x-breadcrumbs />
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4 flex-shrink-0" style="margin-right: 24px;">
                        <div class="flex flex-col items-end leading-tight">
                            <span class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</span>
                            <span class="text-[10px] text-gray-400 capitalize">{{ Auth::user()->roles->first()?->name ?? 'Usuário' }}</span>
                        </div>
                        
                        <div class="h-8 w-px bg-gray-100 mx-2"></div>

                        <form method="POST" action="{{ route('logout') }}" class="mr-4">
                            @csrf
                            <button type="submit" class="flex items-center space-x-1 text-sm text-red-600 hover:text-red-800 font-semibold transition py-2 px-3 rounded-lg hover:bg-red-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span>Sair</span>
                            </button>
                        </form>
                    </div>
                </nav>

                <!-- Header -->
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="py-6" style="padding-left: 32px; padding-right: 32px;">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Main Content -->
                <main class="flex-grow py-12" style="padding-left: 32px; padding-right: 32px;">
                    @if (session('success'))
                        <div class="mb-6">
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6">
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline">{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="w-full">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
