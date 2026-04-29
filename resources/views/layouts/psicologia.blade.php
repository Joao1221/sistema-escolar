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

            .psicologia-desktop-toggle {
                display: inline-flex;
            }

            .psicologia-mobile-header,
            .psicologia-mobile-sidebar {
                display: none;
            }

            /* Sidebar fixa desktop */
            .psicologia-desktop-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 16rem;
                z-index: 30;
                transition: width 0.3s ease-in-out, opacity 0.3s ease-in-out;
            }

            .psicologia-desktop-sidebar.collapsed {
                width: 0;
                opacity: 0;
                overflow: hidden;
            }

            /* Padding para o conteúdo não ficar atrás do sidebar */
            .psicologia-content-wrapper {
                margin-left: 16rem;
                transition: margin-left 0.3s ease-in-out;
            }

            .psicologia-content-wrapper.sidebar-collapsed {
                margin-left: 0;
            }

            @media (max-width: 1023px) {
                .psicologia-desktop-sidebar,
                .psicologia-desktop-toggle {
                    display: none !important;
                }

                .psicologia-mobile-header,
                .psicologia-mobile-sidebar {
                    display: flex !important;
                }

                .psicologia-content-wrapper {
                    margin-left: 0 !important;
                }
            }

            @media (min-width: 1024px) {
                .psicologia-mobile-header,
                .psicologia-mobile-sidebar,
                .psicologia-mobile-overlay {
                    display: none !important;
                }
            }
        </style>
    </head>
    <body class="portal-psicologia min-h-screen overflow-x-hidden bg-[radial-gradient(circle_at_top,_#e7faf8_0%,_#edf4ff_42%,_#eef5ea_100%)] text-slate-900 antialiased" x-data="{ sidebarOpen: false, sidebarCollapsed: false, toggleSidebar() { this.sidebarCollapsed = !this.sidebarCollapsed; } }" @keydown.escape.window="sidebarOpen = false">
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

        <!-- Sidebar fixa (desktop) -->
        <aside class="psicologia-desktop-sidebar"
               :class="sidebarCollapsed ? 'collapsed' : ''">
            <x-sidebar-psicologia />
        </aside>

        <!-- Mobile Sidebar -->
        <div class="psicologia-mobile-sidebar fixed inset-y-0 left-0 z-50 w-64 -translate-x-full transition-transform duration-300 ease-in-out"
             :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <button type="button" @click="sidebarOpen = false" class="absolute right-3 top-3 z-10 inline-flex h-9 w-9 items-center justify-center rounded-xl bg-white/10 text-white ring-1 ring-white/20 transition hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/60" aria-label="Fechar menu">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            <x-sidebar-psicologia />
        </div>

        <!-- Conteúdo principal com margem para o sidebar -->
        <div class="psicologia-content-wrapper"
             :class="sidebarCollapsed ? 'sidebar-collapsed' : ''">
            <div class="flex-1 flex flex-col min-w-0">
                <!-- Mobile Header -->
                <div class="psicologia-mobile-header flex items-center justify-between p-4 border-b border-white/10 w-full" style="background: linear-gradient(to right, #0f172a, #1e3a5f);">
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
                                <h1 class="mt-3 text-2xl font-bold tracking-tight text-white font-fraunces sm:text-3xl" style="color: #ffffff !important;">{{ $titulo }}</h1>
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

                    <main class="px-4 py-5 sm:px-6 lg:px-10 lg:py-8">
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
