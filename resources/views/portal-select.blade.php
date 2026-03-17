<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistema Educacional') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
        <div class="relative overflow-hidden">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(56,189,248,0.18),_transparent_32%),radial-gradient(circle_at_top_right,_rgba(16,185,129,0.16),_transparent_28%),linear-gradient(180deg,_#020617_0%,_#0f172a_45%,_#111827_100%)]"></div>
            <div class="absolute inset-x-0 top-0 h-40 bg-gradient-to-b from-white/5 to-transparent"></div>
            <div class="absolute -left-24 top-24 h-64 w-64 rounded-full bg-cyan-400/10 blur-3xl"></div>
            <div class="absolute -right-24 top-12 h-72 w-72 rounded-full bg-emerald-400/10 blur-3xl"></div>

            <div class="relative mx-auto flex min-h-screen w-full max-w-7xl flex-col px-6 py-8 lg:px-10">
                <header class="flex flex-col gap-6 rounded-[2rem] border border-white/10 bg-white/5 px-6 py-5 backdrop-blur xl:flex-row xl:items-center xl:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-cyan-200/80">Sistema Educacional Municipal</p>
                        <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-white md:text-5xl">
                            Escolha o portal de trabalho
                        </h1>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300 md:text-base">
                            Acesse o ambiente adequado ao seu perfil para operar a rede, a escola, o diario docente,
                            a alimentacao escolar e os modulos tecnicos com seguranca.
                        </p>
                    </div>

                    <div class="flex flex-col gap-3 rounded-2xl border border-white/10 bg-slate-950/40 px-5 py-4 text-sm text-slate-300 sm:min-w-[280px]">
                        <div>
                            <span class="text-slate-400">Conectado como</span>
                            <div class="mt-1 text-base font-bold text-white">{{ Auth::user()->name }}</div>
                        </div>

                        <div class="h-px bg-white/10"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="inline-flex w-full items-center justify-center rounded-xl border border-white/10 bg-white/5 px-4 py-3 font-semibold text-slate-100 transition hover:border-cyan-300/40 hover:bg-cyan-400/10 hover:text-white"
                            >
                                Sair do sistema
                            </button>
                        </form>
                    </div>
                </header>

                <main class="mt-8 flex-1">
                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 2xl:grid-cols-3">
                        @hasrole('Administrador da Rede')
                            <a href="{{ route('secretaria.dashboard') }}" class="group relative overflow-hidden rounded-[2rem] border border-indigo-400/20 bg-white/8 p-7 shadow-2xl shadow-slate-950/30 transition duration-300 hover:-translate-y-1 hover:border-indigo-300/40 hover:bg-white/10">
                                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-indigo-400 via-sky-300 to-cyan-300"></div>
                                <div class="flex items-start justify-between gap-4">
                                    <div class="inline-flex rounded-2xl bg-indigo-400/15 p-4 text-indigo-200 ring-1 ring-indigo-300/20">
                                        <svg class="h-9 w-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <span class="rounded-full border border-indigo-300/20 bg-indigo-400/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-indigo-100/80">
                                        Rede
                                    </span>
                                </div>

                                <h2 class="mt-8 text-2xl font-bold text-white">Secretaria de Educação</h2>
                                <p class="mt-3 text-sm leading-7 text-slate-300">
                                    Gestão estratégica da rede, dados institucionais, escolas, funcionários, base curricular,
                                    documentos, relatórios e auditoria ampla.
                                </p>

                                <div class="mt-8 flex items-center gap-3 text-sm font-semibold text-indigo-100">
                                    <span>Acessar portal</span>
                                    <span class="transition group-hover:translate-x-1">→</span>
                                </div>
                            </a>
                        @endhasrole

                        @hasanyrole('Administrador da Rede|Secretário Escolar|Administrador da Escola|Diretor Escolar|Coordenador Pedagógico|Psicologia/Psicopedagogia')
                            <a href="{{ route('secretaria-escolar.dashboard') }}" class="group relative overflow-hidden rounded-[2rem] border border-emerald-400/20 bg-white/8 p-7 shadow-2xl shadow-slate-950/30 transition duration-300 hover:-translate-y-1 hover:border-emerald-300/40 hover:bg-white/10">
                                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-300"></div>
                                <div class="flex items-start justify-between gap-4">
                                    <div class="inline-flex rounded-2xl bg-emerald-400/15 p-4 text-emerald-200 ring-1 ring-emerald-300/20">
                                        <svg class="h-9 w-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                                        </svg>
                                    </div>
                                    <span class="rounded-full border border-emerald-300/20 bg-emerald-400/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-100/80">
                                        Escola
                                    </span>
                                </div>

                                <h2 class="mt-8 text-2xl font-bold text-white">Secretaria Escolar</h2>
                                <p class="mt-3 text-sm leading-7 text-slate-300">
                                    Operação da unidade escolar com alunos, turmas, matrículas, horários, documentos,
                                    alimentação, relatórios e auditoria administrativa da escola.
                                </p>

                                <div class="mt-8 flex items-center gap-3 text-sm font-semibold text-emerald-100">
                                    <span>Acessar portal</span>
                                    <span class="transition group-hover:translate-x-1">→</span>
                                </div>
                            </a>
                        @endhasanyrole

                        @if (Auth::user()->can('criar diarios'))
                            <a href="{{ route('professor.dashboard') }}" class="group relative overflow-hidden rounded-[2rem] border border-amber-400/20 bg-white/8 p-7 shadow-2xl shadow-slate-950/30 transition duration-300 hover:-translate-y-1 hover:border-amber-300/40 hover:bg-white/10">
                                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-amber-400 via-orange-300 to-yellow-200"></div>
                                <div class="flex items-start justify-between gap-4">
                                    <div class="inline-flex rounded-2xl bg-amber-400/15 p-4 text-amber-200 ring-1 ring-amber-300/20">
                                        <svg class="h-9 w-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5 4.462 5 2 6.567 2 8.5v9c0-1.933 2.462-3.5 5.5-3.5 1.746 0 3.332.477 4.5 1.253m0-9C13.168 5.477 14.754 5 16.5 5 19.538 5 22 6.567 22 8.5v9c0-1.933-2.462-3.5-5.5-3.5-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                    <span class="rounded-full border border-amber-300/20 bg-amber-400/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-amber-100/80">
                                        Docência
                                    </span>
                                </div>

                                <h2 class="mt-8 text-2xl font-bold text-white">Portal do Professor</h2>
                                <p class="mt-3 text-sm leading-7 text-slate-300">
                                    Dashboard docente com minhas turmas, horário, diário eletrônico, frequência,
                                    planejamentos, avaliações e rastros do próprio trabalho.
                                </p>

                                <div class="mt-8 flex items-center gap-3 text-sm font-semibold text-amber-100">
                                    <span>Acessar portal</span>
                                    <span class="transition group-hover:translate-x-1">→</span>
                                </div>
                            </a>
                        @endif

                        @if (Auth::user()->can('acessar portal da nutricionista'))
                            <a href="{{ route('nutricionista.dashboard') }}" class="group relative overflow-hidden rounded-[2rem] border border-lime-400/20 bg-white/8 p-7 shadow-2xl shadow-slate-950/30 transition duration-300 hover:-translate-y-1 hover:border-lime-300/40 hover:bg-white/10">
                                <div class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-lime-400 via-emerald-300 to-teal-200"></div>
                                <div class="flex items-start justify-between gap-4">
                                    <div class="inline-flex rounded-2xl bg-lime-400/15 p-4 text-lime-200 ring-1 ring-lime-300/20">
                                        <svg class="h-9 w-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6c-3.5 0-6 2.239-6 5 0 1.722.973 3.241 2.463 4.132L8 19l3.002-1.501A7.154 7.154 0 0012 17c3.5 0 6-2.239 6-5s-2.5-6-6-6zm0 0V3m0 14v4m8-9h-3M7 12H4" />
                                        </svg>
                                    </div>
                                    <span class="rounded-full border border-lime-300/20 bg-lime-400/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-lime-100/80">
                                        Alimentação
                                    </span>
                                </div>

                                <h2 class="mt-8 text-2xl font-bold text-white">Portal da Nutricionista</h2>
                                <p class="mt-3 text-sm leading-7 text-slate-300">
                                    Visão técnica e gerencial da alimentação escolar com estoque, validade, cardápios,
                                    comparativos entre escolas, relatórios e auditoria do módulo.
                                </p>

                                <div class="mt-8 flex items-center gap-3 text-sm font-semibold text-lime-100">
                                    <span>Acessar portal</span>
                                    <span class="transition group-hover:translate-x-1">→</span>
                                </div>
                            </a>
                        @endif
                    </div>
                </main>

                <footer class="mt-10 flex flex-col gap-3 border-t border-white/10 pt-6 text-sm text-slate-400 md:flex-row md:items-center md:justify-between">
                    <p>Ambiente inicial de navegação entre portais do sistema.</p>
                    <p>Use o portal compatível com o seu perfil e permissões.</p>
                </footer>
            </div>
        </div>
    </body>
</html>
