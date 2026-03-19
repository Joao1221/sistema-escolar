<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full lg:overflow-hidden">
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
        </style>
    </head>
    <body class="min-h-full bg-[radial-gradient(circle_at_top,_#e7faf8_0%,_#edf4ff_42%,_#eef5ea_100%)] text-slate-900 antialiased lg:h-full lg:overflow-hidden">
        <div class="min-h-screen flex">
            <x-sidebar-psicologia />
            <div class="w-64 flex-shrink-0"></div>

            <div class="flex-1 min-w-0 p-3 lg:flex lg:h-full lg:min-h-0 lg:flex-col lg:overflow-hidden lg:p-5">
                <div class="min-h-[calc(100vh-1.5rem)] overflow-hidden rounded-[2rem] border border-white/70 bg-white/90 shadow-[0_30px_90px_rgba(15,23,42,0.14)] backdrop-blur lg:flex lg:h-full lg:min-h-0 lg:flex-col">
                    <header class="border-b border-cyan-100 bg-[linear-gradient(135deg,#fbfffe_0%,#eef8fb_45%,#f3f8ef_100%)] px-6 py-5 lg:px-10">
                        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                            <div class="min-w-0">
                                <x-psicologia-breadcrumbs :items="$breadcrumbs" />
                                <h1 class="mt-3 text-3xl font-bold tracking-tight text-[#17353a] font-fraunces">{{ $titulo }}</h1>
                                @if ($subtitulo)
                                    <p class="mt-2 max-w-3xl text-sm text-[#52686f] lg:text-base">{{ $subtitulo }}</p>
                                @endif
                            </div>

                            <div class="flex flex-wrap items-center gap-3 self-start lg:self-auto">
                                <div class="rounded-2xl border border-cyan-100 bg-white/90 px-4 py-3 text-right shadow-sm">
                                    <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-cyan-700">Profissional logado</p>
                                    <p class="mt-1 text-sm font-bold text-[#17353a]">{{ auth()->user()?->name }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ auth()->user()?->roles->first()?->name ?? 'Psicologia/Psicopedagogia' }}</p>
                                </div>

                                <a href="{{ route('hub') }}" class="inline-flex items-center rounded-xl border border-[#b8d7d7] bg-white/80 px-4 py-2 text-xs font-bold uppercase tracking-widest text-[#2b5f64] transition hover:bg-white">
                                    Trocar de portal
                                </a>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center rounded-xl bg-[#14363a] px-4 py-2 text-xs font-bold uppercase tracking-widest text-white transition hover:bg-[#2b6f73]">
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
