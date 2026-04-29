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
        <style>
            .secretaria-desktop-toggle {
                display: inline-flex;
            }

            .secretaria-mobile-header,
            .secretaria-mobile-sidebar {
                display: none;
            }

            /* Sidebar fixa desktop */
            .secretaria-desktop-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 16rem;
                z-index: 30;
            }

            .secretaria-desktop-sidebar.collapsed {
                width: 0;
                overflow: hidden;
            }

            .secretaria-content {
                margin-left: 16rem;
                transition: margin-left 0.3s ease-in-out;
            }

            .secretaria-content.collapsed {
                margin-left: 0;
            }

            @media (max-width: 1023px) {
                .secretaria-desktop-sidebar,
                .secretaria-desktop-toggle {
                    display: none !important;
                }

                .secretaria-mobile-header,
                .secretaria-mobile-sidebar {
                    display: flex !important;
                }

                .secretaria-content {
                    margin-left: 0 !important;
                }
            }

            @media (min-width: 1024px) {
                .secretaria-mobile-header,
                .secretaria-mobile-sidebar,
                .secretaria-mobile-overlay {
                    display: none !important;
                }
            }
        </style>
    </head>
    <body class="portal-secretaria-educacao font-sans antialiased bg-gray-50 overflow-x-hidden" x-data="{ sidebarOpen: false, sidebarCollapsed: false, toggleSidebar() { this.sidebarCollapsed = !this.sidebarCollapsed; } }" x-init="sidebarOpen = false; sidebarCollapsed = false" @keydown.escape.window="sidebarOpen = false">
        <!-- Mobile overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition-opacity ease-linear duration-300" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="secretaria-mobile-overlay fixed inset-0 z-40 bg-gray-900/80 backdrop-blur-sm" 
             @click="sidebarOpen = false" 
             style="display: none;"></div>

        <!-- Sidebar fixa (desktop) -->
        <aside class="secretaria-desktop-sidebar hidden lg:block"
               :class="sidebarCollapsed ? 'collapsed' : ''">
            <div class="sticky top-0 h-screen w-64">
                @include('components.sidebar-secretaria')
            </div>
        </aside>

        <!-- Mobile Sidebar -->
        <div class="secretaria-mobile-sidebar fixed inset-y-0 left-0 z-50 w-64 -translate-x-full transition-transform duration-300 ease-in-out lg:hidden"
             :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <button type="button" @click="sidebarOpen = false" class="absolute right-3 top-3 z-10 inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-white ring-1 ring-white/20 transition hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/60" aria-label="Fechar menu">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            @include('components.sidebar-secretaria')
        </div>

        <!-- Conteúdo principal -->
        <main class="secretaria-content flex-1 flex flex-col"
             :class="sidebarCollapsed ? 'collapsed' : ''">
            <div class="min-h-screen flex flex-col">
                <!-- Mobile Header -->
                <div class="secretaria-mobile-header flex items-center justify-between px-6 py-4 bg-white border-b border-gray-100 w-full">
                    <div class="font-bold text-gray-800 text-lg">Portal da Secretaria</div>
                    <button @click="sidebarOpen = true" class="p-2 -mr-2 text-gray-600 hover:text-gray-900 rounded-lg focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>

                <!-- Top Navigation (Desktop) -->
                <nav class="no-print hidden h-16 w-full items-center justify-between border-b border-gray-100 bg-white px-4 sm:px-6 lg:flex lg:px-8 xl:px-12">
                    <div class="flex items-center min-w-0 flex-1 mr-4">
                        <span class="text-gray-500 font-medium mr-4 flex-shrink-0">Portal da Secretaria</span>
                        <div class="truncate">
                            <x-breadcrumbs />
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4 flex-shrink-0" style="margin-right: 24px;">
                        <button type="button" @click="toggleSidebar()" class="secretaria-desktop-toggle inline-flex items-center gap-2 rounded-full border border-indigo-200 bg-indigo-50 px-4 py-2 text-xs font-bold uppercase tracking-widest text-indigo-700 hover:bg-indigo-100 transition shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                            <span x-text="sidebarCollapsed ? 'Mostrar menu' : 'Ocultar menu'"></span>
                        </button>

                        <div class="flex flex-col items-end leading-tight">
                            <span class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</span>
                            <span class="text-[10px] text-gray-400 capitalize">{{ Auth::user()->roles->first()?->name ?? 'Usuário' }}</span>
                        </div>
                        
                        <div class="h-8 w-px bg-gray-100 mx-2 hidden lg:block"></div>

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
                    <header class="no-print bg-white shadow">
                        <div class="px-4 py-6 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Main Content -->
                <main class="flex-grow px-4 py-6 sm:px-6 lg:px-8 lg:py-12">
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
