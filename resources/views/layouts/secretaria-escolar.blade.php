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

            .secretaria-escolar-desktop-toggle {
                display: inline-flex;
            }

            .secretaria-escolar-mobile-header,
            .secretaria-escolar-mobile-sidebar {
                display: none;
            }

            /* Sidebar fixa desktop */
            .secretaria-escolar-desktop-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 16rem;
                z-index: 30;
                transition: width 0.3s ease-in-out, opacity 0.3s ease-in-out;
            }

            .secretaria-escolar-desktop-sidebar.collapsed {
                width: 0;
                opacity: 0;
                overflow: hidden;
            }

            /* Padding para o conteúdo não ficar atrás do sidebar */
            .secretaria-escolar-content-wrapper {
                margin-left: 16rem;
                transition: margin-left 0.3s ease-in-out;
            }

            .secretaria-escolar-content-wrapper.sidebar-collapsed {
                margin-left: 0;
            }

            @media (max-width: 1023px) {
                .secretaria-escolar-desktop-sidebar,
                .secretaria-escolar-desktop-toggle {
                    display: none !important;
                }

                .secretaria-escolar-mobile-header,
                .secretaria-escolar-mobile-sidebar {
                    display: flex !important;
                }

                .secretaria-escolar-content-wrapper {
                    margin-left: 0 !important;
                }
            }

            @media (min-width: 1024px) {
                .secretaria-escolar-mobile-header,
                .secretaria-escolar-mobile-sidebar,
                .secretaria-escolar-mobile-overlay {
                    display: none !important;
                }
            }
        </style>
    </head>
    <body @class([
        'portal-secretaria-escolar bg-emerald-900 text-slate-900 antialiased overflow-x-hidden',
        'portal-coordenacao-pedagogica' => request()->routeIs('secretaria-escolar.coordenacao.*'),
        'portal-direcao-escolar' => request()->routeIs('secretaria-escolar.direcao.*'),
    ]) x-data="{ sidebarOpen: false, sidebarCollapsed: false, toggleSidebar() { this.sidebarCollapsed = !this.sidebarCollapsed; } }" x-init="sidebarOpen = false; sidebarCollapsed = false" @keydown.escape.window="sidebarOpen = false">
        <!-- Mobile overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition-opacity ease-linear duration-300" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="secretaria-escolar-mobile-overlay fixed inset-0 z-40 bg-slate-900/80 backdrop-blur-sm" 
             @click="sidebarOpen = false" 
             style="display: none;"></div>

        <!-- Sidebar fixa (desktop) -->
        <aside class="secretaria-escolar-desktop-sidebar"
               :class="sidebarCollapsed ? 'collapsed' : ''">
            <div class="sticky top-0 h-screen w-64">
                <x-sidebar-secretaria-escolar />
            </div>
        </aside>

        <!-- Mobile Sidebar -->
        <div class="secretaria-escolar-mobile-sidebar fixed inset-y-0 left-0 z-50 w-64 -translate-x-full transition-transform duration-300 ease-in-out"
             :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <button type="button" @click="sidebarOpen = false" class="absolute right-3 top-3 z-10 inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-white ring-1 ring-white/20 transition hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/60" aria-label="Fechar menu">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <x-sidebar-secretaria-escolar />
        </div>

        <!-- Conteúdo principal com margem para o sidebar -->
        <div class="secretaria-escolar-content-wrapper"
             :class="sidebarCollapsed ? 'sidebar-collapsed' : ''">
            <div class="flex-1 flex flex-col min-w-0">
                <!-- Mobile Header -->
                <div class="secretaria-escolar-mobile-header flex items-center justify-between p-4 border-b border-white/20 bg-emerald-900 text-white w-full">
                    <div class="font-outfit font-bold text-xl">Secretaria Escolar</div>
                    <button @click="sidebarOpen = true" class="p-2 -mr-2 hover:opacity-80 rounded-lg focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>

                <div class="flex-1 flex flex-col min-w-0 min-h-screen">
                    <div class="flex-1 flex flex-col bg-slate-50 overflow-hidden relative">
                        <!-- Navbar / Header -->
                        <header class="no-print hidden h-20 flex-none items-center justify-between border-b border-emerald-100 bg-white/80 px-6 text-gray-800 shadow-sm backdrop-blur-md lg:flex lg:px-12 z-10">
                            <div class="flex items-center overflow-hidden">
                               <x-breadcrumbs />
                            </div>
                
                        <div class="flex items-center space-x-4 lg:space-x-6">
                            <button type="button" @click="toggleSidebar()" class="secretaria-escolar-desktop-toggle inline-flex items-center gap-2 px-4 py-2 border border-emerald-200 rounded-full text-xs font-bold uppercase tracking-widest text-emerald-700 bg-white shadow-sm hover:bg-emerald-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                                <span x-text="sidebarCollapsed ? 'Mostrar menu' : 'Ocultar menu'"></span>
                            </button>

                            <div class="h-8 w-[1px] bg-gray-100 hidden lg:block"></div>

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

                            <div class="h-8 w-[1px] bg-gray-100 hidden lg:block"></div>

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
                    <main class="flex-1 overflow-y-auto overflow-x-hidden px-4 py-6 sm:px-6 lg:px-12 lg:py-10 min-h-0">
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
        </div>

        @stack('scripts')
    </body>
</html>
