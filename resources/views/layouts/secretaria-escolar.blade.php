<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SUE-Escolar') }} - Secretaria Escolar</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Inter:wght@100..900&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Inter', sans-serif; }
            .font-outfit { font-family: 'Outfit', sans-serif; }
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="h-full bg-emerald-900 antialiased overflow-hidden">
        <div class="flex h-full min-h-0 overflow-hidden" x-data="{ mobileMenu: false }" @keydown.window.escape="mobileMenu = false">
            
            <!-- Sidebar -->
            <x-sidebar-secretaria-escolar />

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 min-h-0 bg-slate-50 my-3 mr-[30px] ml-[30px] rounded-[3rem] overflow-hidden shadow-2xl relative border border-white/20">
                
                <!-- Navbar / Header -->
                <header class="bg-white/80 backdrop-blur-md border-b border-emerald-100 h-20 flex-none flex items-center justify-between px-12 z-10 shadow-sm text-gray-800">
                    <div class="flex items-center overflow-hidden">
                       <x-breadcrumbs />
                    </div>
                    
                    <div class="flex items-center space-x-6">
                        {{-- Notifications Placeholder --}}
                        <div class="h-8 w-[1px] bg-gray-100"></div>

                        {{-- Direct Logout Button --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center space-x-2 text-red-500 hover:text-red-700 font-bold text-xs uppercase transition p-2 hover:bg-red-50 rounded-xl">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span class="hidden md:inline">Sair</span>
                            </button>
                        </form>

                        <div class="h-8 w-[1px] bg-gray-100"></div>

                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-3 group outline-none">
                                <div class="flex flex-col text-right hidden lg:block">
                                    <span class="text-sm font-bold text-gray-700 leading-tight group-hover:text-emerald-600 transition">{{ Auth::user()->name }}</span>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">{{ Auth::user()->roles->first()?->name ?? 'Usuário' }}</span>
                                </div>
                                <div class="bg-emerald-600 text-white w-10 h-10 flex items-center justify-center rounded-xl font-bold font-outfit shadow-lg shadow-emerald-200 group-hover:scale-105 transition-transform duration-300">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 class="absolute right-0 mt-3 w-56 bg-white border border-gray-100 rounded-2xl shadow-2xl py-3 z-50 ring-1 ring-black/5">
                                <div class="px-4 py-2 border-b border-gray-50 mb-2">
                                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Sua Conta</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition space-x-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span>Meu Perfil</span>
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition space-x-3">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                        </svg>
                                        <span>Encerrar Sessão</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Scrollable Page Content -->
                <main class="flex-1 overflow-y-auto overflow-x-hidden px-12 py-10 min-h-0">
                    <div class="pb-12">
                    
                    @if (session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" 
                             class="mb-8 flex items-center p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-900 rounded-xl shadow-sm">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="font-medium font-outfit">{{ session('success') }}</span>
                        </div>
                    @endif

                    {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </body>
</html>
