<aside class="w-72 bg-transparent text-white p-4 lg:p-6">
    <div class="h-full rounded-[2rem] border border-amber-800/40 bg-gradient-to-b from-amber-900 via-amber-950 to-stone-950 shadow-2xl overflow-hidden">
        <div class="px-6 pt-8 pb-6 border-b border-amber-800/40">
            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-500 text-stone-950 shadow-lg shadow-amber-950/40">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5 4.462 5 2 6.567 2 8.5v9c0-1.933 2.462-3.5 5.5-3.5 1.746 0 3.332.477 4.5 1.253m0-9C13.168 5.477 14.754 5 16.5 5 19.538 5 22 6.567 22 8.5v9c0-1.933-2.462-3.5-5.5-3.5-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.35em] text-amber-300">Acesso Docente</p>
                    <h2 class="text-xl font-outfit font-semibold">Diario</h2>
                </div>
            </div>
        </div>

        <div class="px-4 py-6 space-y-2">
            <a href="{{ route('professor.diario.index') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('professor.diario.index') ? 'bg-amber-500 text-stone-950 font-semibold' : 'text-amber-100 hover:bg-white/10' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-6h13M9 5v6h13M5 5h.01M5 12h.01M5 19h.01" />
                </svg>
                <span>Meus diarios</span>
            </a>

            @can('criar diarios')
            <a href="{{ route('professor.diario.create') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 transition {{ request()->routeIs('professor.diario.create') ? 'bg-amber-500 text-stone-950 font-semibold' : 'text-amber-100 hover:bg-white/10' }}">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
                </svg>
                <span>Novo diario</span>
            </a>
            @endcan

            <a href="{{ route('hub') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-amber-100 hover:bg-white/10 transition">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                <span>Trocar de portal</span>
            </a>
        </div>

        <div class="mt-auto px-6 py-6 border-t border-amber-800/40">
            <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
            <p class="text-xs text-amber-300 truncate">{{ Auth::user()->email }}</p>
        </div>
    </div>
</aside>
