<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full lg:overflow-hidden">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SUE') }} - Portal da Nutricionista</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fraunces:wght@500;600;700&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Manrope', sans-serif; }
            .font-fraunces { font-family: 'Fraunces', serif; }
        </style>
    </head>
    <body class="min-h-full bg-[radial-gradient(circle_at_top,_#fff5dd_0%,_#f4efe8_40%,_#e7f1ec_100%)] text-slate-900 antialiased" x-data="{ sidebarOpen: false }">
        <!-- Mobile overlay -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="transition-opacity ease-linear duration-300" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 z-40 bg-slate-900/80 backdrop-blur-sm lg:hidden" 
             @click="sidebarOpen = false" 
             style="display: none;"></div>

        <div class="flex min-h-screen">
            <!-- Sidebar -->
            <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" 
                 class="fixed inset-y-0 left-0 z-50 w-64 -translate-x-full transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 flex-shrink-0 lg:block">
                <x-sidebar-nutricionista />
            </div>

            <div class="flex-1 flex flex-col min-w-0">
                <!-- Mobile Header -->
                <div class="flex items-center justify-between p-4 lg:hidden border-b border-white/40 bg-[#f4efe8]">
                    <div class="font-fraunces font-bold text-xl text-[#17332a]">Portal da Nutricionista</div>
                    <button @click="sidebarOpen = true" class="p-2 -mr-2 text-[#17332a] hover:opacity-75 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#17332a]/50">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>

                <div class="flex-1 p-4 lg:p-5">
                    <div class="h-full overflow-hidden rounded-[2rem] border border-white/60 bg-white/85 shadow-[0_25px_80px_rgba(29,53,40,0.18)] backdrop-blur lg:flex lg:flex-col">
                        <header class="border-b border-emerald-100 bg-[linear-gradient(135deg,#fffef9_0%,#f5f4eb_48%,#edf8f2_100%)] px-5 py-5 lg:px-10">
                        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                            <div class="min-w-0">
                                <x-nutricionista-breadcrumbs :items="$breadcrumbs" />
                                <h1 class="mt-3 text-3xl font-bold tracking-tight text-[#17332a] font-fraunces">{{ $titulo }}</h1>
                                @if ($subtitulo)
                                    <p class="mt-2 max-w-3xl text-sm text-[#496357] lg:text-base">{{ $subtitulo }}</p>
                                @endif
                            </div>

                            <div class="flex items-center gap-3 self-start lg:self-auto">
                                <div class="rounded-2xl border border-emerald-100 bg-white/80 px-4 py-3 text-right shadow-sm">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-emerald-700">Usuaria logada</p>
                                    <p class="mt-1 text-sm font-bold text-[#17332a]">{{ auth()->user()?->name }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ auth()->user()?->roles->first()?->name ?? 'Nutricionista' }}</p>
                                </div>
                                <a href="{{ route('hub') }}" class="inline-flex items-center rounded-xl border border-[#cfd9cf] bg-white/80 px-4 py-2 text-xs font-bold uppercase tracking-widest text-[#3d5b4e] transition hover:bg-white">
                                    Trocar de portal
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center rounded-xl bg-[#18352c] px-4 py-2 text-xs font-bold uppercase tracking-widest text-white transition hover:bg-[#c96c2b]">
                                        Sair
                                    </button>
                                </form>
                            </div>
                        </div>
                    </header>

                    <main class="px-6 py-6 lg:flex-1 lg:min-h-0 lg:overflow-y-auto lg:px-10 lg:py-8">
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
