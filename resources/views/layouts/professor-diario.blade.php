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
        </style>
    </head>
    <body class="h-full bg-amber-950 antialiased">
        <div class="min-h-screen flex">
            <x-sidebar-professor-diario />

            <div class="flex-1 min-w-0 p-4 lg:p-6">
                <div class="min-h-[calc(100vh-2rem)] rounded-[2.5rem] bg-stone-50 shadow-2xl border border-white/60 overflow-hidden">
                    <header class="px-6 lg:px-10 py-5 bg-white/90 backdrop-blur border-b border-stone-200 flex items-center justify-between gap-4">
                        <div>
                            <p class="text-[11px] uppercase tracking-[0.3em] text-amber-700 font-semibold">Modulo Operacional</p>
                            <h1 class="text-2xl font-bold font-outfit text-stone-900">Diario do Professor</h1>
                        </div>

                        <div class="flex items-center gap-4">
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

                    <main class="p-6 lg:p-10">
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
