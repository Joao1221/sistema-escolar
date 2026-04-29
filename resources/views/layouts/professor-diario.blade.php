<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SUE') }} - Diario do Professor</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=Source+Sans+3:wght@200..900&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Source Sans 3', sans-serif; }
            .font-outfit { font-family: 'Outfit', sans-serif; }
            .professor-diario-desktop-sidebar {
                display: block;
                width: 18rem;
                flex: 0 0 18rem;
            }

            .professor-diario-desktop-toggle {
                display: inline-flex;
            }

            .professor-diario-mobile-header,
            .professor-diario-mobile-sidebar {
                display: none;
            }

            @media (max-width: 1023px) {
                .professor-diario-desktop-sidebar,
                .professor-diario-desktop-toggle {
                    display: none !important;
                }

                .professor-diario-mobile-header,
                .professor-diario-mobile-sidebar {
                    display: flex !important;
                }
            }

            @media (min-width: 1024px) {
                .professor-diario-mobile-header,
                .professor-diario-mobile-sidebar,
                .professor-diario-mobile-overlay {
                    display: none !important;
                }
            }
        </style>
    </head>
    <body class="portal-professor portal-professor-diario h-full overflow-x-hidden bg-amber-950 antialiased" x-data="{ sidebarOpen: false, sidebarCollapsed: false, toggleSidebar() { this.sidebarCollapsed = !this.sidebarCollapsed; } }" @keydown.escape.window="sidebarOpen = false">
        <!-- Mobile overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition-opacity ease-linear duration-300" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="professor-diario-mobile-overlay fixed inset-0 z-40 bg-stone-900/80 backdrop-blur-sm" 
             @click="sidebarOpen = false" 
             style="display: none;"></div>

        <div class="flex min-h-screen flex-col lg:flex-row">
            <!-- Sidebar -->
            <aside class="professor-diario-desktop-sidebar flex-shrink-0 overflow-hidden transition-[width,opacity] duration-300 ease-in-out"
                   :style="sidebarCollapsed
                        ? 'width: 0; flex-basis: 0; opacity: 0; pointer-events: none;'
                        : 'width: 18rem; flex-basis: 18rem; opacity: 1; pointer-events: auto;'">
                <x-sidebar-professor-diario />
            </aside>

            <!-- Mobile Sidebar -->
            <div class="professor-diario-mobile-sidebar fixed inset-y-0 left-0 z-50 w-72 max-w-[80vw] -translate-x-full transition-transform duration-300 ease-in-out"
                 :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
                <button type="button" @click="sidebarOpen = false" class="absolute right-3 top-3 z-10 inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-white ring-1 ring-white/20 transition hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/60" aria-label="Fechar menu">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
                <x-sidebar-professor-diario />
            </div>

            <div class="flex flex-1 flex-col min-w-0 p-3 sm:p-4 lg:p-6 lg:ml-0">
                <!-- Mobile Header -->
                <div class="professor-diario-mobile-header flex items-center justify-between p-4 mb-4 rounded-2xl border border-white/10 bg-gradient-to-b from-amber-900 via-amber-950 to-stone-950 text-white">
                    <div class="font-outfit font-bold text-xl">Diário Eletrônico</div>
                    <button @click="sidebarOpen = true" class="p-2 -mr-2 text-amber-200 hover:text-white rounded-lg focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>
                <div class="min-h-[calc(100vh-2rem)] overflow-hidden rounded-[1.5rem] border border-white/60 bg-stone-50 shadow-2xl sm:rounded-[2rem] lg:rounded-[2.5rem]">
                    <header class="flex flex-col items-start gap-4 border-b border-stone-200 bg-white/90 px-4 py-5 backdrop-blur sm:flex-row sm:items-center sm:justify-between sm:px-6 lg:px-10">
                        <div>
                            <p class="text-[11px] uppercase tracking-[0.3em] text-amber-700 font-semibold">Modulo Operacional</p>
                            <h1 class="text-2xl font-bold font-outfit text-stone-900">Diario do Professor</h1>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                            <button type="button" @click="toggleSidebar()" class="professor-diario-desktop-toggle inline-flex items-center gap-2 rounded-xl border border-stone-300 bg-white px-4 py-2 text-xs font-bold uppercase tracking-widest text-stone-600 hover:bg-stone-100 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                                <span x-text="sidebarCollapsed ? 'Mostrar menu' : 'Ocultar menu'"></span>
                            </button>

                            <a href="{{ route('hub') }}" class="text-sm font-semibold text-stone-600 hover:text-amber-700 transition">
                                Trocar de acesso
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="inline-flex items-center rounded-xl bg-stone-900 px-4 py-2 text-xs font-bold uppercase tracking-widest text-white hover:bg-amber-700 transition">
                                    Sair
                                </button>
                            </form>
                        </div>
                    </header>

                    <main class="p-4 sm:p-6 lg:p-10">
                        @if (session('success'))
                            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-900">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-800">
                                {{ session('error') }}
                            </div>
                        @endif

                        {{ $slot }}
                    </main>
                </div>
            </div>
        </div>
    </body>
</html>
