<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SUE') }} - Portal da Psicologia</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Manrope', sans-serif; }
            .font-fraunces { font-family: 'Fraunces', serif; }
            .psicologia-desktop-sidebar {
                display: block;
                width: 16rem;
                flex: 0 0 16rem;
            }

            .psicologia-desktop-toggle {
                display: inline-flex;
            }

            .psicologia-mobile-header,
            .psicologia-mobile-sidebar,
            .psicologia-mobile-overlay {
                display: none;
            }

            @media (max-width: 479px) {
                .psicologia-desktop-sidebar,
                .psicologia-desktop-toggle {
                    display: none !important;
                }

                .psicologia-mobile-header,
                .psicologia-mobile-sidebar {
                    display: flex !important;
                }
            }

            @media (min-width: 480px) {
                .psicologia-mobile-header,
                .psicologia-mobile-sidebar,
                .psicologia-mobile-overlay {
                    display: none !important;
                }
            }
        </style>
    </head>
    <body class="bg-[radial-gradient(circle_at_top,_#e7faf8_0%,_#edf4ff_42%,_#eef5ea_100%)] text-slate-900 antialiased" x-data="{ sidebarOpen: false, sidebarCollapsed: false, toggleSidebar() { this.sidebarCollapsed = !this.sidebarCollapsed; } }">
        <!-- Mobile overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition-opacity ease-linear duration-300" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="psicologia-mobile-overlay fixed inset-0 z-40 bg-slate-900/80 backdrop-blur-sm" 
             @click="sidebarOpen = false" 
             style="display: none;"></div>

        <div class="flex min-h-screen">
            <!-- Sidebar -->
            <aside class="psicologia-desktop-sidebar flex-shrink-0 overflow-hidden transition-[width,opacity] duration-300 ease-in-out"
                   :style="sidebarCollapsed
                        ? 'width: 0; flex-basis: 0; opacity: 0; pointer-events: none;'
                        : 'width: 16rem; flex-basis: 16rem; opacity: 1; pointer-events: auto;'">
                <x-sidebar-psicologia />
            </aside>

            <!-- Mobile Sidebar -->
            <div class="psicologia-mobile-sidebar fixed inset-y-0 left-0 z-50 w-64 -translate-x-full transition-transform duration-300 ease-in-out"
                 :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
                <x-sidebar-psicologia />
            </div>

            <div class="flex-1 flex flex-col min-w-0">
                <!-- Mobile Header -->
                <div class="psicologia-mobile-header flex items-center justify-between p-4 border-b border-white/10" style="background: linear-gradient(to right, #0f172a, #1e3a5f);">
                    <div class="font-fraunces font-bold text-xl text-white">Portal da Psicologia</div>
                    <button @click="sidebarOpen = true" class="p-2 -mr-2 text-white/80 hover:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-white/50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>

                <div class="flex-1 p-4 lg:p-5">
                    <div class="h-full overflow-hidden rounded-[2rem] border border-white/70 bg-white shadow-[0_30px_90px_rgba(15,23,42,0.14)]">
                        <header class="border-b border-blue-800 px-5 py-5 lg:px-10" style="background: linear-gradient(to right, #0f172a, #1e3a5f, #1e40af);">
                        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                            <div class="min-w-0">
                                <x-psicologia-breadcrumbs :items="$breadcrumbs" />
                                <h1 class="mt-3 text-3xl font-bold tracking-tight text-white font-fraunces" style="color: #ffffff !important;">{{ $titulo }}</h1>
                                @if ($subtitulo)
                                    <p class="mt-2 max-w-3xl text-sm lg:text-base" style="color: rgba(255,255,255,0.9) !important;">{{ $subtitulo }}</p>
                                @endif
                            </div>

                            <div class="flex flex-wrap items-center gap-3 self-start lg:self-auto">
                                <button type="button" @click="toggleSidebar()" class="psicologia-desktop-toggle inline-flex items-center gap-2 rounded-xl border border-white/30 bg-white/10 px-4 py-2 text-xs font-bold uppercase tracking-widest text-white transition hover:bg-white/20 backdrop-blur">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                    </svg>
                                    <span x-text="sidebarCollapsed ? 'Mostrar menu' : 'Ocultar menu'"></span>
                                </button>

                                <div class="rounded-2xl border border-white/30 bg-white/10 px-4 py-3 text-right shadow-sm backdrop-blur">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-white/80">Profissional logado</p>
                                    <p class="mt-1 text-sm font-bold text-white">{{ auth()->user()?->name }}</p>
                                    <p class="mt-1 text-xs text-white/60">{{ auth()->user()?->roles->first()?->name ?? 'Psicologia/Psicopedagogia' }}</p>
                                </div>

                                <a href="{{ route('hub') }}" class="inline-flex items-center rounded-xl border border-white/30 bg-white/10 px-4 py-2 text-xs font-bold uppercase tracking-widest text-white transition hover:bg-white/20 backdrop-blur">
                                    Trocar de portal
                                </a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center rounded-xl bg-white/20 px-4 py-2 text-xs font-bold uppercase tracking-widest text-white transition hover:bg-white/30 backdrop-blur">
                                        Sair
                                    </button>
                                </form>
                            </div>
                        </div>
                    </header>

                    <main class="px-6 py-6 lg:px-10 lg:py-8">
                        @if (session('success'))
                            <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-900 shadow-sm">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-5 py-4 text-rose-800 shadow-sm">
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
