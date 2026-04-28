<x-secretaria-escolar-layout>
    <div class="px-8 py-6 space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-emerald-600">Psicologia/Psicopedagogia</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">Painel sigiloso de atendimentos</h1>
                <p class="mt-2 text-sm text-slate-500">Area restrita para acompanhamento psicossocial, psicopedagogico e tecnico.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('secretaria-escolar.psicossocial.create') }}" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Novo atendimento</a>
                <a href="{{ route('secretaria-escolar.psicossocial.agenda') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Ver agenda</a>
            </div>
        </div>

        @if (session('success'))
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
        @endif

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-3xl border border-sky-100 bg-gradient-to-br from-sky-50 to-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-widest text-sky-700">Agendados hoje</p>
                <p class="mt-4 text-3xl font-bold text-slate-900">{{ $totais['agendados_hoje'] }}</p>
            </div>
            <div class="rounded-3xl border border-emerald-100 bg-gradient-to-br from-emerald-50 to-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-widest text-emerald-700">Historico realizado</p>
                <p class="mt-4 text-3xl font-bold text-slate-900">{{ $totais['historico_realizado'] }}</p>
            </div>
            <div class="rounded-3xl border border-amber-100 bg-gradient-to-br from-amber-50 to-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-widest text-amber-700">Planos ativos</p>
                <p class="mt-4 text-3xl font-bold text-slate-900">{{ $totais['planos_ativos'] }}</p>
            </div>
            <div class="rounded-3xl border border-rose-100 bg-gradient-to-br from-rose-50 to-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-widest text-rose-700">Encaminhamentos abertos</p>
                <p class="mt-4 text-3xl font-bold text-slate-900">{{ $totais['encaminhamentos_abertos'] }}</p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.4fr_1fr]">
            <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h2 class="text-lg font-semibold text-slate-900">Agenda de hoje</h2>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse ($agendaHoje as $atendimento)
                        <a href="{{ route('secretaria-escolar.psicossocial.show', $atendimento) }}" class="block px-6 py-4 hover:bg-slate-50">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $atendimento->nome_atendido }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $atendimento->tipo_publico_label }} | {{ ucfirst($atendimento->tipo_atendimento) }} | {{ $atendimento->escola?->nome }}</p>
                                </div>
                                <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-semibold text-sky-700">{{ $atendimento->data_agendada->format('H:i') }}</span>
                            </div>
                        </a>
                    @empty
                        <p class="px-6 py-10 text-sm text-slate-500">Nenhum atendimento agendado para hoje.</p>
                    @endforelse
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-6 py-4">
                        <h2 class="text-lg font-semibold text-slate-900">Atendimentos recentes</h2>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse ($atendimentosRecentes as $atendimento)
                            <a href="{{ route('secretaria-escolar.psicossocial.show', $atendimento) }}" class="block px-6 py-4 hover:bg-slate-50">
                                <p class="font-semibold text-slate-900">{{ $atendimento->nome_atendido }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $atendimento->data_agendada->format('d/m/Y H:i') }} | {{ ucfirst($atendimento->status) }}</p>
                            </a>
                        @empty
                            <p class="px-6 py-8 text-sm text-slate-500">Sem historico recente.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-6 py-4">
                        <h2 class="text-lg font-semibold text-slate-900">Relatorios tecnicos recentes</h2>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse ($relatoriosRecentes as $relatorio)
                            <div class="px-6 py-4">
                                <p class="font-semibold text-slate-900">{{ $relatorio->titulo }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $relatorio->data_emissao->format('d/m/Y') }} | {{ ucfirst(str_replace('_', ' ', $relatorio->tipo_relatorio)) }}</p>
                            </div>
                        @empty
                            <p class="px-6 py-8 text-sm text-slate-500">Nenhum relatorio tecnico emitido.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-secretaria-escolar-layout>
