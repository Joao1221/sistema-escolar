{{-- Logo / Branding --}}
<div class="p-6">
    <div class="flex items-center space-x-3">
        <div class="bg-emerald-500 p-2 rounded-lg text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </div>
        <span class="text-xl font-bold tracking-wider uppercase text-white">SUE-Escolar</span>
    </div>
    <p class="text-xs text-emerald-300 mt-1 uppercase font-semibold">Secretaria Escolar</p>
</div>

{{-- Navigation Items --}}
<div class="flex-grow mt-0.5 overflow-y-auto custom-scrollbar">
    <div class="px-4 space-y-3 pb-6">

        {{-- Gestão Principal --}}
        <div>
            <p class="text-[12px] text-emerald-400 font-bold uppercase tracking-widest pl-2 mb-1" style="font-size: 12px;">Gestão Principal</p>
            <div class="space-y-0.5">
                <a href="{{ route('secretaria-escolar.dashboard') }}"
                   class="flex items-center space-x-3 px-3 py-1.5 rounded-xl transition duration-200 {{ request()->routeIs('secretaria-escolar.dashboard') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/40' : 'text-emerald-100 hover:bg-emerald-800/40 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span class="text-sm font-medium font-outfit tracking-wide">Dashboard</span>
                </a>

                @can('visualizar alunos')
                <a href="{{ route('secretaria-escolar.alunos.index') }}"
                   class="flex items-center space-x-3 px-3 py-1.5 rounded-xl transition duration-200 {{ request()->routeIs('secretaria-escolar.alunos.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/40' : 'text-emerald-100 hover:bg-emerald-800/40 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="text-sm font-medium font-outfit tracking-wide">Alunos</span>
                </a>
                @endcan

                @can('consultar turmas')
                <a href="{{ route('secretaria-escolar.turmas.index') }}"
                   class="flex items-center space-x-3 px-3 py-1.5 rounded-xl transition duration-200 {{ request()->routeIs('secretaria-escolar.turmas.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/40' : 'text-emerald-100 hover:bg-emerald-800/40 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                    <span class="text-sm font-medium font-outfit tracking-wide">Turmas</span>
                </a>
                @endcan

                @can('consultar matrÃ­culas')
                <a href="{{ route('secretaria-escolar.matriculas.index') }}"
                   class="flex items-center space-x-3 px-3 py-1.5 rounded-xl transition duration-200 {{ request()->routeIs('secretaria-escolar.matriculas.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/40' : 'text-emerald-100 hover:bg-emerald-800/40 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="text-sm font-medium font-outfit tracking-wide">Matrículas</span>
                </a>
                @endcan

                @can('consultar matrizes')
                <a href="{{ route('secretaria-escolar.curriculo.index') }}"
                   class="flex items-center space-x-3 px-3 py-1.5 rounded-xl transition duration-200 {{ request()->routeIs('secretaria-escolar.curriculo.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/40' : 'text-emerald-100 hover:bg-emerald-800/40 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01" />
                    </svg>
                    <span class="text-sm font-medium font-outfit tracking-wide">Base Curricular</span>
                </a>
                @endcan

                @can('ver horarios')
                <a href="{{ route('secretaria-escolar.horarios.index') }}"
                   class="flex items-center space-x-3 px-3 py-1.5 rounded-xl transition duration-200 {{ request()->routeIs('secretaria-escolar.horarios.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/40' : 'text-emerald-100 hover:bg-emerald-800/40 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm font-medium font-outfit tracking-wide">Horários</span>
                </a>
                @endcan

                @can('consultar diarios')
                <a href="{{ route('secretaria-escolar.diarios.index') }}"
                   class="flex items-center space-x-3 px-3 py-1.5 rounded-xl transition duration-200 {{ request()->routeIs('secretaria-escolar.diarios.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/40' : 'text-emerald-100 hover:bg-emerald-800/40 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="text-sm font-medium font-outfit tracking-wide">Diários</span>
                </a>
                @endcan

                @can('acompanhar diarios pedagogicamente')
                <a href="{{ route('secretaria-escolar.coordenacao.diarios.index') }}"
                   class="flex items-center space-x-3 px-3 py-1.5 rounded-xl transition duration-200 {{ request()->routeIs('secretaria-escolar.coordenacao.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/40' : 'text-emerald-100 hover:bg-emerald-800/40 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2a4 4 0 014-4h6m-6 6v2m0-8V9m-7 8h.01M7 17h.01M7 13h.01M7 9h.01M7 5h.01M3 17h.01M3 13h.01M3 9h.01M3 5h.01" />
                    </svg>
                    <span class="text-sm font-medium font-outfit tracking-wide">Coordenacao Pedagogica</span>
                </a>
                @endcan

                @can('consultar horarios pedagogicamente')
                <a href="{{ route('secretaria-escolar.coordenacao.horarios.index') }}"
                   class="flex items-center space-x-3 px-3 py-1.5 rounded-xl transition duration-200 {{ request()->routeIs('secretaria-escolar.coordenacao.horarios.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/40' : 'text-emerald-100 hover:bg-emerald-800/40 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-11 9h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v11a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-medium font-outfit tracking-wide">Horarios Pedagogicos</span>
                </a>
                @endcan

                @can('acompanhar diarios da direcao')
                <a href="{{ route('secretaria-escolar.direcao.diarios.index') }}"
                   class="flex items-center space-x-3 px-3 py-1.5 rounded-xl transition duration-200 {{ request()->routeIs('secretaria-escolar.direcao.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/40' : 'text-emerald-100 hover:bg-emerald-800/40 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6l7 4v4c0 5-3.5 7.5-7 8-3.5-.5-7-3-7-8v-4l7-4zm0 5v4m0 4h.01" />
                    </svg>
                    <span class="text-sm font-medium font-outfit tracking-wide">Direcao Escolar</span>
                </a>
                @endcan

                @can('consultar horarios da direcao')
                <a href="{{ route('secretaria-escolar.direcao.horarios.index') }}"
                   class="flex items-center space-x-3 px-3 py-1.5 rounded-xl transition duration-200 {{ request()->routeIs('secretaria-escolar.direcao.horarios.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/40' : 'text-emerald-100 hover:bg-emerald-800/40 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-11 9h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v11a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm font-medium font-outfit tracking-wide">Horarios da Direcao</span>
                </a>
                @endcan

                @can('consultar demandas psicossociais escolares')
                <a href="{{ route('secretaria-escolar.demandas-psicossociais.index') }}"
                   class="flex items-center space-x-3 px-3 py-1.5 rounded-xl transition duration-200 {{ request()->routeIs('secretaria-escolar.demandas-psicossociais.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/40' : 'text-emerald-100 hover:bg-emerald-800/40 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="text-sm font-medium font-outfit tracking-wide">Demandas Psicossociais</span>
                </a>
                @endcan

                @can('consultar alimentacao escolar')
                <a href="{{ route('secretaria-escolar.alimentacao.index') }}"
                   class="flex items-center space-x-3 px-3 py-1.5 rounded-xl transition duration-200 {{ request()->routeIs('secretaria-escolar.alimentacao.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/40' : 'text-emerald-100 hover:bg-emerald-800/40 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2v10zm-7-8h.01M8 9h8m-8 4h5" />
                    </svg>
                    <span class="text-sm font-medium font-outfit tracking-wide">Alimentacao Escolar</span>
                </a>
                @endcan

                @can('consultar documentos escolares')
                <a href="{{ route('secretaria-escolar.documentos.index') }}"
                   class="flex items-center space-x-3 px-3 py-1.5 rounded-xl transition duration-200 {{ request()->routeIs('secretaria-escolar.documentos.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/40' : 'text-emerald-100 hover:bg-emerald-800/40 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h10m-7 4h7M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                    </svg>
                    <span class="text-sm font-medium font-outfit tracking-wide">Documentos Escolares</span>
                </a>
                @endcan

                @can('consultar documentos pedagogicos')
                <a href="{{ route('secretaria-escolar.coordenacao.documentos.index') }}"
                   class="flex items-center space-x-3 px-3 py-1.5 rounded-xl transition duration-200 {{ request()->routeIs('secretaria-escolar.coordenacao.documentos.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/40' : 'text-emerald-100 hover:bg-emerald-800/40 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6l8 4-8 4-8-4 8-4zm0 6l8 4-8 4-8-4 8-4z" />
                    </svg>
                    <span class="text-sm font-medium font-outfit tracking-wide">Documentos da Coordenacao</span>
                </a>
                @endcan

                @can('consultar documentos da direcao escolar')
                <a href="{{ route('secretaria-escolar.direcao.documentos.index') }}"
                   class="flex items-center space-x-3 px-3 py-1.5 rounded-xl transition duration-200 {{ request()->routeIs('secretaria-escolar.direcao.documentos.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/40' : 'text-emerald-100 hover:bg-emerald-800/40 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4z" />
                    </svg>
                    <span class="text-sm font-medium font-outfit tracking-wide">Documentos da Direcao</span>
                </a>
                @endcan

                @can('consultar relatorios escolares')
                <a href="{{ route('secretaria-escolar.relatorios.index') }}"
                   class="flex items-center space-x-3 px-3 py-1.5 rounded-xl transition duration-200 {{ request()->routeIs('secretaria-escolar.relatorios.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/40' : 'text-emerald-100 hover:bg-emerald-800/40 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6m3 6V7m3 10v-3m3 3H6a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2v10a2 2 0 01-2 2z" />
                    </svg>
                    <span class="text-sm font-medium font-outfit tracking-wide">Relatorios da Escola</span>
                </a>
                @endcan

                @can('consultar relatorios pedagogicos')
                <a href="{{ route('secretaria-escolar.coordenacao.relatorios.index') }}"
                   class="flex items-center space-x-3 px-3 py-1.5 rounded-xl transition duration-200 {{ request()->routeIs('secretaria-escolar.coordenacao.relatorios.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/40' : 'text-emerald-100 hover:bg-emerald-800/40 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3a1 1 0 012 0v1a1 1 0 11-2 0V3zm4.95 2.05a1 1 0 011.414 1.414l-.707.707a1 1 0 11-1.414-1.414l.707-.707zM21 11a1 1 0 010 2h-1a1 1 0 110-2h1zM7 12a5 5 0 1110 0 5 5 0 01-10 0zm-4 0a1 1 0 011-1h1a1 1 0 110 2H4a1 1 0 01-1-1zm3.343-5.243a1 1 0 010 1.414l-.707.707A1 1 0 114.222 7.48l.707-.707a1 1 0 011.414 0zM12 19a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zm5.657-1.757a1 1 0 011.414 0l.707.707a1 1 0 01-1.414 1.414l-.707-.707a1 1 0 010-1.414zm-11.314 0a1 1 0 010 1.414l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 0z" />
                    </svg>
                    <span class="text-sm font-medium font-outfit tracking-wide">Relatorios Pedagogicos</span>
                </a>
                @endcan

                @can('consultar relatorios da direcao escolar')
                <a href="{{ route('secretaria-escolar.direcao.relatorios.index') }}"
                   class="flex items-center space-x-3 px-3 py-1.5 rounded-xl transition duration-200 {{ request()->routeIs('secretaria-escolar.direcao.relatorios.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/40' : 'text-emerald-100 hover:bg-emerald-800/40 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4zM9 12h6" />
                    </svg>
                    <span class="text-sm font-medium font-outfit tracking-wide">Relatorios da Direcao</span>
                </a>
                @endcan

                @can('consultar auditoria escolar')
                <a href="{{ route('secretaria-escolar.auditoria.index') }}"
                   class="flex items-center space-x-3 px-3 py-1.5 rounded-xl transition duration-200 {{ request()->routeIs('secretaria-escolar.auditoria.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/40' : 'text-emerald-100 hover:bg-emerald-800/40 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm font-medium font-outfit tracking-wide">Auditoria da Escola</span>
                </a>
                @endcan

                @can('consultar auditoria pedagogica')
                <a href="{{ route('secretaria-escolar.coordenacao.auditoria.index') }}"
                   class="flex items-center space-x-3 px-3 py-1.5 rounded-xl transition duration-200 {{ request()->routeIs('secretaria-escolar.coordenacao.auditoria.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/40' : 'text-emerald-100 hover:bg-emerald-800/40 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19a8 8 0 100-16 8 8 0 000 16zm0 0c.34 0 .676-.021 1.006-.062L20 21" />
                    </svg>
                    <span class="text-sm font-medium font-outfit tracking-wide">Auditoria Pedagogica</span>
                </a>
                @endcan

                @can('consultar auditoria da direcao escolar')
                <a href="{{ route('secretaria-escolar.direcao.auditoria.index') }}"
                   class="flex items-center space-x-3 px-3 py-1.5 rounded-xl transition duration-200 {{ request()->routeIs('secretaria-escolar.direcao.auditoria.*') ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-900/40' : 'text-emerald-100 hover:bg-emerald-800/40 hover:text-white' }}">
                    <svg class="w-5 h-5 opacity-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3l7 4v5c0 5-3.5 8-7 9-3.5-1-7-4-7-9V7l7-4zM12 11v3" />
                    </svg>
                    <span class="text-sm font-medium font-outfit tracking-wide">Auditoria da Direcao</span>
                </a>
                @endcan

            </div>
        </div>

        {{-- Administração Escolar --}}
        <div>
            <p class="text-[12px] text-emerald-400/60 font-bold uppercase tracking-widest pl-2 mb-1" style="font-size: 12px;">Administrativo</p>
            <div class="space-y-0.5 opacity-60">
                <div class="flex items-center justify-between px-3 py-1.5 rounded-xl text-emerald-200/50 cursor-not-allowed">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-11 9h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v11a2 2 0 002 2z" />
                        </svg>
                        <span class="text-sm font-medium font-outfit">Historico Escolar</span>
                    </div>
                    <span class="text-[9px] bg-emerald-800/60 font-bold text-emerald-400 px-1.5 py-0.5 rounded border border-emerald-700/50 uppercase tracking-tighter">Breve</span>
                </div>
                <div class="flex items-center justify-between px-3 py-1.5 rounded-xl text-emerald-200/50 cursor-not-allowed">
                    <div class="flex items-center space-x-3">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19h16M4 15h10M4 11h16M4 7h10" />
                        </svg>
                        <span class="text-sm font-medium font-outfit">Relatorios Administrativos</span>
                    </div>
                    <span class="text-[9px] bg-emerald-800/60 font-bold text-emerald-400 px-1.5 py-0.5 rounded border border-emerald-700/50 uppercase tracking-tighter">Breve</span>
                </div>
            </div>
        </div>

        {{-- Atalhos --}}
        <div>
            <p class="text-[12px] text-emerald-400 font-bold uppercase tracking-widest pl-2 mb-1" style="font-size: 12px;">Atalhos</p>
            <div class="space-y-0.5">
                <a href="{{ route('hub') }}"
                   class="flex items-center space-x-3 px-3 py-1.5 rounded-xl text-emerald-300 hover:bg-emerald-800/40 hover:text-white transition group border border-emerald-800/30">
                    <svg class="w-5 h-5 opacity-60 group-hover:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                    </svg>
                    <span class="text-sm font-medium italic font-outfit tracking-wide">Trocar de Portal</span>
                </a>
            </div>
        </div>

    </div>
</div>

{{-- User footer --}}
<div class="p-6 border-t border-emerald-800 bg-emerald-900/50">
    <div class="flex items-center justify-between">
        <div class="overflow-hidden">
            <p class="text-sm font-bold text-white leading-tight truncate">{{ Auth::user()->name }}</p>
            <p class="text-[12px] text-emerald-400 mt-1 truncate" style="font-size: 12px;">{{ Auth::user()->email }}</p>
        </div>
        <form method="POST" action="{{ route('logout') }}" class="ml-2">
            @csrf
            <button type="submit" class="p-2 text-emerald-400 hover:text-white hover:bg-emerald-800 rounded-lg transition" title="Sair">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </button>
        </form>
    </div>
</div>




