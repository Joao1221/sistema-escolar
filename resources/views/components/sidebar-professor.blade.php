<aside class="w-full lg:w-[320px] p-3 lg:p-5 text-white">
    <div class="h-full rounded-[2rem] border border-[#8f5b36]/40 bg-[radial-gradient(circle_at_top,#7f4c2c_0%,#3c2117_45%,#1f110d_100%)] shadow-[0_18px_50px_rgba(0,0,0,0.35)] overflow-hidden">
        <div class="border-b border-white/10 px-6 pb-6 pt-8">
            <div class="flex items-center gap-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-[1.4rem] bg-[#f0b15b] text-[#2c170f] shadow-lg shadow-black/20">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422A12.083 12.083 0 0118 17.5c0 1.105-2.686 2-6 2s-6-.895-6-2c0-2.332.807-4.477 2.16-6.078L12 14z" />
                    </svg>
                </div>
                <div>
                    <p class="text-[11px] uppercase tracking-[0.34em] text-amber-200">Portal dedicado</p>
                    <h2 class="font-outfit text-2xl font-semibold">Professor</h2>
                    <p class="text-sm text-white/70">Rotina pedagógica e diário eletrônico</p>
                </div>
            </div>
        </div>

        <div class="px-4 py-6">
            <p class="px-3 text-[11px] font-bold uppercase tracking-[0.32em] text-white/45">Visão Geral</p>
            <div class="mt-3 space-y-2">
                <a href="{{ route('professor.dashboard') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('professor.dashboard') ? 'bg-[#f0b15b] text-[#2b1710] shadow-lg shadow-black/10' : 'text-white/85 hover:bg-white/10' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 13h8V3H3v10zm10 8h8V3h-8v18zm-10 0h8v-6H3v6z" />
                    </svg>
                    <span class="font-semibold">Dashboard</span>
                </a>
                <a href="{{ route('professor.turmas.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('professor.turmas.*') ? 'bg-[#f0b15b] text-[#2b1710] shadow-lg shadow-black/10' : 'text-white/85 hover:bg-white/10' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4a2 2 0 012-2m14 0V7a2 2 0 00-2-2H5a2 2 0 00-2 2v4" />
                    </svg>
                    <span class="font-semibold">Minhas turmas</span>
                </a>
                <a href="{{ route('professor.horarios.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('professor.horarios.*') ? 'bg-[#f0b15b] text-[#2b1710] shadow-lg shadow-black/10' : 'text-white/85 hover:bg-white/10' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-semibold">Meu horário</span>
                </a>
            </div>
        </div>

        <div class="border-t border-white/10 px-4 py-6">
            <p class="px-3 text-[11px] font-bold uppercase tracking-[0.32em] text-white/45">Diário Eletrônico</p>
            <div class="mt-3 space-y-2">
                <a href="{{ route('professor.diario.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('professor.diario.index') ? 'bg-[#f0b15b] text-[#2b1710] shadow-lg shadow-black/10' : 'text-white/85 hover:bg-white/10' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="font-semibold">Meus diários</span>
                </a>
                <a href="{{ route('professor.diario.create') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('professor.diario.create') ? 'bg-[#f0b15b] text-[#2b1710] shadow-lg shadow-black/10' : 'text-white/85 hover:bg-white/10' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
                    </svg>
                    <span class="font-semibold">Abrir diário</span>
                </a>
            </div>
        </div>

        @can('consultar documentos do professor')
        <div class="border-t border-white/10 px-4 py-6">
            <p class="px-3 text-[11px] font-bold uppercase tracking-[0.32em] text-white/45">Documentos</p>
            <div class="mt-3 space-y-2">
                <a href="{{ route('professor.documentos.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('professor.documentos.*') ? 'bg-[#f0b15b] text-[#2b1710] shadow-lg shadow-black/10' : 'text-white/85 hover:bg-white/10' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h10m-7 4h7M7 4h10a2 2 0 012 2v12a2 2 0 01-2 2H7a2 2 0 01-2-2V6a2 2 0 012-2z" />
                    </svg>
                    <span class="font-semibold">Documentos</span>
                </a>
            </div>
        </div>
        @endcan

        @can('consultar auditoria do proprio trabalho docente')
        <div class="border-t border-white/10 px-4 py-6">
            <p class="px-3 text-[11px] font-bold uppercase tracking-[0.32em] text-white/45">Auditoria</p>
            <div class="mt-3 space-y-2">
                <a href="{{ route('professor.auditoria.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('professor.auditoria.*') ? 'bg-[#f0b15b] text-[#2b1710] shadow-lg shadow-black/10' : 'text-white/85 hover:bg-white/10' }}">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-semibold">Meus rastros</span>
                </a>
            </div>
        </div>
        @endcan

        <div class="border-t border-white/10 px-6 py-6">
            <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
            <p class="mt-1 text-xs uppercase tracking-[0.2em] text-amber-200/80">Professor autenticado</p>
            <p class="mt-2 text-sm text-white/65 truncate">{{ Auth::user()->email }}</p>
        </div>
    </div>
</aside>
