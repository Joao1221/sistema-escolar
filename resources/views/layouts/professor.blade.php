<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SUE') }} - Portal do Professor</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&family=IBM+Plex+Sans:wght@100..700&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'IBM Plex Sans', sans-serif; }
            .font-outfit { font-family: 'Outfit', sans-serif; }

            .professor-desktop-toggle {
                display: inline-flex;
            }

            .professor-mobile-header,
            .professor-mobile-sidebar,
            .professor-mobile-overlay {
                display: none;
            }

            /* Sidebar fixa desktop */
            .professor-desktop-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 20rem;
                z-index: 30;
            }

            .professor-desktop-sidebar.collapsed {
                width: 0;
                overflow: hidden;
            }

            .professor-content {
                margin-left: 20rem;
                transition: margin-left 0.3s ease-in-out;
            }

            .professor-content.collapsed {
                margin-left: 0;
            }

            @media (max-width: 1023px) {
                .professor-desktop-sidebar,
                .professor-desktop-toggle {
                    display: none !important;
                }

                .professor-mobile-header,
                .professor-mobile-sidebar {
                    display: flex !important;
                }

                .professor-content {
                    margin-left: 0 !important;
                }
            }

            @media (min-width: 1024px) {
                .professor-mobile-header,
                .professor-mobile-sidebar,
                .professor-mobile-overlay {
                    display: none !important;
                }
            }
        </style>
    </head>
    <body class="min-h-full text-stone-100 antialiased" x-data="{ sidebarOpen: false, sidebarCollapsed: false, toggleSidebar() { this.sidebarCollapsed = !this.sidebarCollapsed; } }" style="background: radial-gradient(circle at top, #2b1f3a 0%, #1e142d 45%, #120b1f 100%);">
        @php
            $theme = auth()->user()?->theme ?? 'lilas';
            $pal = match ($theme) {
                'grafite' => [
                    'bodyBg' => 'radial-gradient(circle at top, #182334 0%, #0f1826 45%, #0b1220 100%)',
                    'cardBg' => '#101727',
                    'cardBorder' => 'rgba(255,255,255,0.12)',
                    'cardShadow' => '0 25px 80px rgba(8,12,24,0.55)',
                    'headerBg' => 'linear-gradient(135deg,#111c2e 0%,#0f1a2a 55%,#0d1623_100%)',
                    'headerBorder' => 'rgba(59,130,246,0.35)',
                    'heading' => '#e5edff',
                    'text' => '#d6e2ff',
                    'muted' => '#9fb4d6',
                    'chipBorder' => 'rgba(59,130,246,0.45)',
                    'chipBg' => 'rgba(255,255,255,0.08)',
                    'chipText' => '#e5edff',
                    'buttonPrimary' => '#3b82f6',
                    'buttonPrimaryText' => '#0b1220',
                    'buttonSecondaryBorder' => 'rgba(59,130,246,0.45)',
                    'buttonSecondaryText' => '#e5edff',
                ],
                'verde' => [
                    'bodyBg' => 'radial-gradient(circle at top, #0f2a23 0%, #0a1e18 45%, #071510 100%)',
                    'cardBg' => '#0f1f1a',
                    'cardBorder' => 'rgba(255,255,255,0.10)',
                    'cardShadow' => '0 25px 80px rgba(5,18,13,0.55)',
                    'headerBg' => 'linear-gradient(135deg,#0f2f26 0%,#0c261f 55%,#0a201b 100%)',
                    'headerBorder' => 'rgba(52,211,153,0.35)',
                    'heading' => '#e7fff3',
                    'text' => '#d1f7e6',
                    'muted' => '#9dd9be',
                    'chipBorder' => 'rgba(52,211,153,0.45)',
                    'chipBg' => 'rgba(255,255,255,0.06)',
                    'chipText' => '#e7fff3',
                    'buttonPrimary' => '#10b981',
                    'buttonPrimaryText' => '#071510',
                    'buttonSecondaryBorder' => 'rgba(52,211,153,0.45)',
                    'buttonSecondaryText' => '#e7fff3',
                ],
                default => [
                    'bodyBg' => 'radial-gradient(circle at top, #2b1f3a 0%, #1e142d 45%, #120b1f 100%)',
                    'cardBg' => '#f7f3ff',
                    'cardBorder' => 'rgba(255,255,255,0.2)',
                    'cardShadow' => '0 25px 80px rgba(18,12,35,0.35)',
                    'headerBg' => 'linear-gradient(135deg,#f9f6ff 0%,#eee7ff 55%,#e9ddff 100%)',
                    'headerBorder' => '#d9c7f7',
                    'heading' => '#1f1230',
                    'text' => '#1f1230',
                    'muted' => '#554168',
                    'chipBorder' => '#c9b4ff',
                    'chipBg' => 'rgba(255,255,255,0.9)',
                    'chipText' => '#3b2458',
                    'buttonPrimary' => '#4c2c78',
                    'buttonPrimaryText' => '#ffffff',
                    'buttonSecondaryBorder' => '#c9b4ff',
                    'buttonSecondaryText' => '#3b2458',
                ],
            };
        @endphp

        <!-- Mobile overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition-opacity ease-linear duration-300" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="professor-mobile-overlay fixed inset-0 z-40 bg-gray-900/80 backdrop-blur-sm" 
             @click="sidebarOpen = false" 
             style="display: none;"></div>

        <!-- Sidebar fixa (desktop) -->
        <aside class="professor-desktop-sidebar hidden lg:block"
               :class="sidebarCollapsed ? 'collapsed' : ''">
            <x-sidebar-professor :theme="$theme" />
        </aside>

        <!-- Mobile Sidebar -->
        <div class="professor-mobile-sidebar fixed inset-y-0 left-0 z-50 w-80 max-w-[80vw] -translate-x-full transition-transform duration-300 ease-in-out lg:hidden"
             :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <x-sidebar-professor :theme="$theme" />
        </div>

        <!-- Conteúdo principal -->
        <main class="professor-content flex-1 flex flex-col min-w-0"
             :class="sidebarCollapsed ? 'collapsed' : ''">
            <div class="flex-1 flex flex-col min-h-screen" style="background: {{ $pal['bodyBg'] }};">
                <!-- Mobile Header -->
                <div class="professor-mobile-header flex items-center justify-between p-4 mb-3 rounded-2xl border border-white/10 w-full" style="background: {{ $pal['cardBg'] }};">
                    <div class="font-outfit font-bold text-xl" style="color: {{ $pal['heading'] }};">Portal do Professor</div>
                    <button @click="sidebarOpen = true" class="p-2 -mr-2 rounded-lg focus:outline-none" style="color: {{ $pal['muted'] }};">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>

                <div class="flex-1 p-3 lg:p-5">
                    <div class="h-full rounded-[2rem] border shadow-[0_25px_80px_rgba(18,12,35,0.35)]"
                         style="background: {{ $pal['cardBg'] }}; border-color: {{ $pal['cardBorder'] }}; box-shadow: {{ $pal['cardShadow'] }};">
                    <header class="px-6 py-5 lg:px-10" style="border-bottom:1px solid {{ $pal['headerBorder'] }}; background: {{ $pal['headerBg'] }};">
                        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                            <div class="min-w-0">
                                <x-professor-breadcrumbs :items="$breadcrumbs" />
                                <h1 class="mt-3 text-3xl font-bold tracking-tight font-outfit" style="color: {{ $pal['heading'] }};">{{ $titulo }}</h1>
                                @if ($subtitulo)
                                    <p class="mt-2 max-w-3xl text-sm lg:text-base" style="color: {{ $pal['muted'] }};">{{ $subtitulo }}</p>
                                @endif
                            </div>

                            <div class="flex items-start gap-4 self-start lg:self-auto">
                                <button type="button" @click="toggleSidebar()" class="professor-desktop-toggle inline-flex items-center gap-2 rounded-xl border px-4 py-2 text-xs font-bold uppercase tracking-widest transition"
                                        style="border-color: {{ $pal['chipBorder'] }}; background: {{ $pal['chipBg'] }}; color: {{ $pal['chipText'] }};">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                                    </svg>
                                    <span x-text="sidebarCollapsed ? 'Mostrar menu' : 'Ocultar menu'"></span>
                                </button>

                                <div class="rounded-2xl border px-4 py-3" style="border-color: {{ $pal['chipBorder'] }}; background: {{ $pal['chipBg'] }};">
                                    <p class="text-xs font-semibold uppercase tracking-[0.22em]" style="color: {{ $pal['chipText'] }};">Professor autenticado</p>
                                    <p class="mt-1 text-sm font-bold" style="color: {{ $pal['heading'] }};">{{ auth()->user()?->name }}</p>
                                    <p class="text-xs" style="color: {{ $pal['muted'] }};">{{ auth()->user()?->email }}</p>
                                </div>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('hub') }}" class="inline-flex items-center rounded-xl px-4 py-2 text-xs font-bold uppercase tracking-widest transition"
                                       style="border:1px solid {{ $pal['chipBorder'] }}; background: {{ $pal['chipBg'] }}; color: {{ $pal['chipText'] }};">
                                        Trocar de portal
                                    </a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center rounded-xl px-4 py-2 text-xs font-bold uppercase tracking-widest transition"
                                                style="background: {{ $pal['buttonPrimary'] }}; color: {{ $pal['buttonPrimaryText'] }};">
                                            Sair
                                        </button>
                                    </form>
                                </div>
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
                            <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 px-5 py-4 text-red-800 shadow-sm">
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
