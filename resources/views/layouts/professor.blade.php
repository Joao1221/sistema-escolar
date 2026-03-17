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
        </style>
    </head>
    <body class="min-h-full bg-[#20110c] text-stone-900 antialiased">
        <div class="min-h-screen lg:flex">
            <x-sidebar-professor />

            <div class="flex-1 min-w-0 p-3 lg:p-5">
                <div class="min-h-[calc(100vh-1.5rem)] overflow-hidden rounded-[2rem] border border-white/40 bg-[#f7f1e8] shadow-[0_25px_80px_rgba(0,0,0,0.35)]">
                    <header class="border-b border-[#e2d3bf] bg-[linear-gradient(135deg,#fffaf4_0%,#f4e8d7_100%)] px-6 py-5 lg:px-10">
                        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                            <div class="min-w-0">
                                <x-professor-breadcrumbs :items="$breadcrumbs" />
                                <h1 class="mt-3 text-3xl font-bold tracking-tight text-[#24120d] font-outfit">{{ $titulo }}</h1>
                                @if ($subtitulo)
                                    <p class="mt-2 max-w-3xl text-sm text-[#6f5648] lg:text-base">{{ $subtitulo }}</p>
                                @endif
                            </div>

                            <div class="flex items-center gap-3 self-start lg:self-auto">
                                <a href="{{ route('hub') }}" class="inline-flex items-center rounded-xl border border-[#c6a98f] bg-white/80 px-4 py-2 text-xs font-bold uppercase tracking-widest text-[#7b4b2a] transition hover:bg-white">
                                    Trocar de portal
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center rounded-xl bg-[#2b1710] px-4 py-2 text-xs font-bold uppercase tracking-widest text-white transition hover:bg-[#8b4d28]">
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
