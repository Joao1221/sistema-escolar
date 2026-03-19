<nav class="fixed inset-y-0 left-0 w-64 bg-slate-900 text-white flex flex-col flex-shrink-0 shadow-xl" style="background-color: #0f172a;">
    {{-- Logo / Branding --}}
    <div class="p-6">
        <div class="flex items-center space-x-3">
            <div class="bg-indigo-500 p-2 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <span class="text-xl font-bold tracking-wider">SUE-EDU</span>
        </div>
        <p class="text-xs text-indigo-300 mt-1 uppercase font-semibold">Secretaria de Educação</p>
    </div>

    {{-- Navigation Items --}}
    <div class="flex-grow mt-0.5 overflow-y-auto">
        <div class="px-4 space-y-0">

            {{-- Visão Geral --}}
            <p class="text-[10px] text-indigo-400 font-bold uppercase tracking-widest pl-2 mb-1">Visão Geral</p>

            <a href="{{ route('secretaria.dashboard') }}"
               class="flex items-center space-x-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('secretaria.dashboard') ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-800/60 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span class="text-sm font-medium">Dashboard</span>
            </a>

            <a href="{{ route('hub') }}" 
               class="flex items-center space-x-3 px-3 py-1.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition group">
                <svg class="w-5 h-5 opacity-60 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                <span class="text-sm font-medium italic">Trocar de Portal</span>
            </a>

            {{-- Gestão --}}
            <p class="text-[10px] text-indigo-400 font-bold uppercase tracking-widest pl-2 mt-5 mb-2">Gestão</p>

            @can('visualizar escolas')
            <a href="{{ route('secretaria.escolas.index') }}"
               class="flex items-center space-x-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('secretaria.escolas.*') ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-800/60 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span class="text-sm font-medium">Escolas</span>
            </a>
            @endcan

            @can('visualizar funcionarios')
            <a href="{{ route('secretaria.funcionarios.index') }}"
               class="flex items-center space-x-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('secretaria.funcionarios.*') ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-800/60 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="text-sm font-medium">Funcionários</span>
            </a>
            @endcan

            @can('visualizar usuarios')
            <a href="{{ route('secretaria.usuarios.index') }}"
               class="flex items-center space-x-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('secretaria.usuarios.*') ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-800/60 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="text-sm font-medium">Usuários</span>
            </a>
            @endcan

            @can('visualizar alunos')
            <a href="{{ route('secretaria.alunos.index') }}"
               class="flex items-center space-x-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('secretaria.alunos.*') ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-800/60 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="text-sm font-medium">Alunos</span>
            </a>
            @endcan

            @can('consultar matrículas')
            <a href="{{ route('secretaria.matriculas.index') }}"
               class="flex items-center space-x-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('secretaria.matriculas.*') ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-800/60 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="text-sm font-medium">Matrículas</span>
            </a>
            @endcan

            @can('consultar turmas')
            <a href="{{ route('secretaria.turmas.index') }}"
               class="flex items-center space-x-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('secretaria.turmas.*') ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-800/60 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span class="text-sm font-medium">Turmas</span>
            </a>
            @endcan

            {{-- Base Curricular --}}
            <p class="text-[10px] text-indigo-400 font-bold uppercase tracking-widest pl-2 mt-5 mb-2">Base Curricular</p>

            @can('gerenciar disciplinas')
            <a href="{{ route('secretaria.disciplinas.index') }}"
               class="flex items-center space-x-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('secretaria.disciplinas.*') ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-800/60 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 
6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span class="text-sm font-medium">Disciplinas</span>
            </a>
            @endcan

            @can('consultar matrizes')
            <a href="{{ route('secretaria.matrizes.index') }}"
               class="flex items-center space-x-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('secretaria.matrizes.*') ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-800/60 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01" />
                </svg>
                <span class="text-sm font-medium">Matrizes</span>
            </a>
            @endcan
            {{-- Configurações --}}
            <p class="text-[10px] text-indigo-400 font-bold uppercase tracking-widest pl-2 mt-5 mb-2">Configurações</p>

            @can('visualizar instituicao')
            <a href="{{ route('secretaria.instituicao.show') }}"
               class="flex items-center space-x-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('secretaria.instituicao.*') ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-800/60 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-sm font-medium">Dados Institucionais</span>
            </a>
            @endcan

            @can('visualizar configuracoes')
            <a href="{{ route('secretaria.configuracoes.index') }}"
               class="flex items-center space-x-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('secretaria.configuracoes.*') ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-800/60 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="text-sm font-medium">Parâmetros Globais</span>
            </a>
            @endcan

            @can('consultar documentos institucionais da rede')
            <a href="{{ route('secretaria.documentos.index') }}"
               class="flex items-center space-x-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('secretaria.documentos.*') ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-800/60 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h10m-7 4h7M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                </svg>
                <span class="text-sm font-medium">Documentos da Rede</span>
            </a>
            @endcan

            @can('consultar relatorios da rede')
            <a href="{{ route('secretaria.relatorios.index') }}"
               class="flex items-center space-x-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('secretaria.relatorios.*') ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-800/60 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6m3 6V7m3 10v-3m3 3H6a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2z" />
                </svg>
                <span class="text-sm font-medium">Relatorios da Rede</span>
            </a>
            @endcan

            @can('consultar auditoria da rede')
            <a href="{{ route('secretaria.auditoria.index') }}"
               class="flex items-center space-x-3 px-3 py-1.5 rounded-lg transition {{ request()->routeIs('secretaria.auditoria.*') ? 'bg-indigo-700 text-white' : 'text-indigo-100 hover:bg-indigo-800/60 hover:text-white' }}">
                <svg class="w-5 h-5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0zm-7 9h4" />
                </svg>
                <span class="text-sm font-medium">Auditoria da Rede</span>
            </a>
            @endcan

        </div>
    </div>

    {{-- User footer --}}
    <div class="p-6 border-t border-slate-800 bg-slate-900/50">
        <div class="overflow-hidden">
            <p class="text-sm font-bold text-white leading-tight truncate">{{ Auth::user()->name }}</p>
            <p class="text-xs text-slate-400 mt-1 truncate">{{ Auth::user()->email }}</p>
        </div>
    </div>
</nav>
