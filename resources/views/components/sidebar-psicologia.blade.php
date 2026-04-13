<aside class="flex h-full w-full flex-col overflow-y-auto bg-slate-950 shadow-xl" style="color: #ffffff;">
    <div class="absolute inset-0 pointer-events-none bg-[radial-gradient(circle_at_top,_rgba(34,211,238,0.16),_transparent_42%),linear-gradient(180deg,_rgba(2,6,23,1)_0%,_rgba(15,23,42,1)_100%)]"></div>

    <div class="relative flex min-h-full flex-col">
        <div class="border-b border-white/10 px-5 py-5">
            <p class="text-xs font-semibold uppercase tracking-[0.35em] text-cyan-300">Ambiente Restrito</p>
            <h2 class="mt-3 max-w-[11.5rem] text-xl font-bold leading-tight font-fraunces xl:text-2xl text-white">Portal da Psicologia</h2>
            <p class="mt-2 max-w-[15rem] text-sm leading-6 text-slate-300">Rotina tecnica sigilosa com agenda, atendimentos, planos, encaminhamentos e relatorios restritos.</p>
        </div>

        <div class="px-3 py-4">
            <div class="space-y-4">
                <nav class="space-y-1 mb-4">
                    <a href="{{ route('psicologia.dashboard') }}" class="flex items-center justify-between rounded-2xl px-4 py-2 text-xs font-semibold transition {{ request()->routeIs('psicologia.dashboard') ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                        <span>Dashboard</span>
                        <span class="text-[10px] uppercase tracking-[0.25em] {{ request()->routeIs('psicologia.dashboard') ? 'text-slate-500' : 'text-slate-400' }}">Abrir</span>
                    </a>
                </nav>

                <section>
                    <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-[0.35em] text-cyan-300">Fluxo de trabalho</p>
                    <nav class="space-y-1">
                        @php
                            $linksFluxo = [
                                ['rota' => 'psicologia.demandas.index', 'label' => 'Demandas'],
                                ['rota' => 'psicologia.demandas.create', 'label' => 'Nova demanda'],
                                ['rota' => 'psicologia.historico.index', 'label' => 'Historico'],
                            ];
                        @endphp

                        @foreach ($linksFluxo as $link)
                            <a href="{{ route($link['rota']) }}" class="flex items-center justify-between rounded-2xl px-4 py-2 text-xs font-semibold transition {{ request()->routeIs(str_replace('.index', '.*', $link['rota'])) || request()->routeIs($link['rota']) ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                                <span>{{ $link['label'] }}</span>
                                <span class="text-[10px] uppercase tracking-[0.25em] {{ request()->routeIs(str_replace('.index', '.*', $link['rota'])) || request()->routeIs($link['rota']) ? 'text-slate-500' : 'text-slate-400' }}">Abrir</span>
                            </a>
                        @endforeach
                    </nav>
                </section>

                <section>
                    <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-[0.35em] text-cyan-300">Trabalho tecnico</p>
                    <nav class="space-y-1">
                        @php
                            $linksTecnicos = [
                                ['rota' => 'psicologia.encaminhamentos.index', 'label' => 'Encaminhamentos'],
                                ['rota' => 'psicologia.relatorios_atendimentos.index', 'label' => 'Gerador de relatorios'],
                                ['rota' => 'psicologia.relatorios_tecnicos.index', 'label' => 'Relatorios tecnicos'],
                            ];
                        @endphp

                        @foreach ($linksTecnicos as $link)
                            <a href="{{ route($link['rota']) }}" class="flex items-center justify-between rounded-2xl px-4 py-2 text-xs font-semibold transition {{ request()->routeIs($link['rota']) || request()->routeIs(str_replace('.index', '.*', $link['rota'])) ? 'bg-white text-slate-900 shadow-lg' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                                <span>{{ $link['label'] }}</span>
                                <span class="text-[10px] uppercase tracking-[0.25em] {{ request()->routeIs($link['rota']) || request()->routeIs(str_replace('.index', '.*', $link['rota'])) ? 'text-slate-500' : 'text-slate-400' }}">Abrir</span>
                            </a>
                        @endforeach
                    </nav>
                </section>

            </div>
        </div>

        <div class="border-t border-white/10 px-5 py-4">
            <div class="rounded-2xl bg-white/8 p-4">
                <p class="text-[11px] font-semibold uppercase tracking-[0.3em] text-cyan-300">Sigilo maximo</p>
                <p class="mt-2 text-[11px] text-slate-300">
                    Registros sensiveis, historicos e relatorios permanecem restritos ao perfil habilitado.
                </p>
            </div>
        </div>
    </div>
</aside>
