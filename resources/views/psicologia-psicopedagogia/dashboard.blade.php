<x-psicologia-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    @php
        $rotulosPublico = [
            'aluno' => 'Alunos',
            'professor' => 'Professores',
            'funcionario' => 'Funcionarios',
            'responsavel' => 'Pais/Responsaveis',
        ];
    @endphp

    <div class="space-y-6">
        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
            <div class="overflow-hidden rounded-[1.75rem] border border-cyan-100 bg-[linear-gradient(180deg,rgba(236,254,255,0.95)_0%,rgba(255,255,255,0.98)_100%)] p-5 shadow-sm">
                <div class="h-1.5 w-16 rounded-full bg-cyan-400"></div>
                <p class="mt-4 text-xs font-semibold uppercase tracking-[0.24em] text-cyan-700">Agendados hoje</p>
                <p class="mt-4 text-3xl font-bold text-[#14363a]">{{ $totais['agendados_hoje'] }}</p>
            </div>
            <div class="overflow-hidden rounded-[1.75rem] border border-emerald-100 bg-[linear-gradient(180deg,rgba(236,253,245,0.95)_0%,rgba(255,255,255,0.98)_100%)] p-5 shadow-sm">
                <div class="h-1.5 w-16 rounded-full bg-emerald-400"></div>
                <p class="mt-4 text-xs font-semibold uppercase tracking-[0.24em] text-emerald-700">Atendimentos em aberto</p>
                <p class="mt-4 text-3xl font-bold text-[#14363a]">{{ $totais['atendimentos_abertos'] }}</p>
            </div>
            <div class="overflow-hidden rounded-[1.75rem] border border-amber-100 bg-[linear-gradient(180deg,rgba(255,251,235,0.96)_0%,rgba(255,255,255,0.98)_100%)] p-5 shadow-sm">
                <div class="h-1.5 w-16 rounded-full bg-amber-400"></div>
                <p class="mt-4 text-xs font-semibold uppercase tracking-[0.24em] text-amber-700">Atendimentos realizados</p>
                <p class="mt-4 text-3xl font-bold text-[#14363a]">{{ $totais['atendimentos_realizados'] }}</p>
            </div>
            <div class="overflow-hidden rounded-[1.75rem] border border-rose-100 bg-[linear-gradient(180deg,rgba(255,241,242,0.96)_0%,rgba(255,255,255,0.98)_100%)] p-5 shadow-sm">
                <div class="h-1.5 w-16 rounded-full bg-rose-400"></div>
                <p class="mt-4 text-xs font-semibold uppercase tracking-[0.24em] text-rose-700">Planos ativos</p>
                <p class="mt-4 text-3xl font-bold text-[#14363a]">{{ $totais['planos_ativos'] }}</p>
            </div>
            <div class="overflow-hidden rounded-[1.75rem] border border-sky-100 bg-[linear-gradient(180deg,rgba(240,249,255,0.96)_0%,rgba(255,255,255,0.98)_100%)] p-5 shadow-sm">
                <div class="h-1.5 w-16 rounded-full bg-sky-400"></div>
                <p class="mt-4 text-xs font-semibold uppercase tracking-[0.24em] text-sky-700">Encaminhamentos abertos</p>
                <p class="mt-4 text-3xl font-bold text-[#14363a]">{{ $totais['encaminhamentos_abertos'] }}</p>
            </div>
            <div class="overflow-hidden rounded-[1.75rem] border border-lime-100 bg-[linear-gradient(180deg,rgba(247,254,231,0.96)_0%,rgba(255,255,255,0.98)_100%)] p-5 shadow-sm">
                <div class="h-1.5 w-16 rounded-full bg-lime-400"></div>
                <p class="mt-4 text-xs font-semibold uppercase tracking-[0.24em] text-lime-700">Casos disciplinares</p>
                <p class="mt-4 text-3xl font-bold text-[#14363a]">{{ $totais['casos_abertos'] }}</p>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            @foreach ($rotulosPublico as $tipo => $rotulo)
                <div class="rounded-[1.75rem] border border-slate-200 bg-[linear-gradient(180deg,rgba(248,250,252,0.96)_0%,rgba(255,255,255,0.98)_100%)] p-5 shadow-sm">
                    <div class="h-1.5 w-16 rounded-full bg-slate-300"></div>
                    <p class="mt-4 text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">{{ $rotulo }}</p>
                    <p class="mt-4 text-3xl font-bold text-[#14363a]">{{ $porPublico[$tipo] ?? 0 }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.4fr_1fr]">
            <section class="rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-4">
                    <h2 class="text-lg font-bold text-[#14363a]">Agenda de hoje</h2>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse ($agendaHoje as $atendimento)
                        <a href="{{ route('psicologia.show', $atendimento) }}" class="block px-6 py-4 transition hover:bg-slate-50">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="font-semibold text-[#14363a]">{{ $atendimento->nome_atendido }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ ucfirst($atendimento->tipo_publico) }} | {{ ucfirst($atendimento->tipo_atendimento) }} | {{ $atendimento->escola?->nome }}</p>
                                </div>
                                <span class="rounded-full bg-cyan-50 px-3 py-1 text-xs font-semibold text-cyan-700">{{ $atendimento->data_agendada->format('H:i') }}</span>
                            </div>
                        </a>
                    @empty
                        <p class="px-6 py-10 text-sm text-slate-500">Nenhum atendimento agendado para hoje.</p>
                    @endforelse
                </div>
            </section>

            <section class="space-y-6">
                <div class="rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-6 py-4">
                        <h2 class="text-lg font-bold text-[#14363a]">Atendimentos recentes</h2>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse ($atendimentosRecentes as $atendimento)
                            <a href="{{ route('psicologia.show', $atendimento) }}" class="block px-6 py-4 transition hover:bg-slate-50">
                                <p class="font-semibold text-[#14363a]">{{ $atendimento->nome_atendido }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $atendimento->data_agendada->format('d/m/Y H:i') }} | {{ ucfirst($atendimento->status) }}</p>
                            </a>
                        @empty
                            <p class="px-6 py-8 text-sm text-slate-500">Sem historico recente.</p>
                        @endforelse
                    </div>
                </div>

                <div class="rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-6 py-4">
                        <h2 class="text-lg font-bold text-[#14363a]">Relatorios tecnicos recentes</h2>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse ($relatoriosRecentes as $relatorio)
                            <div class="px-6 py-4">
                                <p class="font-semibold text-[#14363a]">{{ $relatorio->titulo }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $relatorio->data_emissao->format('d/m/Y') }} | {{ ucfirst(str_replace('_', ' ', $relatorio->tipo_relatorio)) }}</p>
                            </div>
                        @empty
                            <p class="px-6 py-8 text-sm text-slate-500">Nenhum relatorio tecnico emitido.</p>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <section class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-[#14363a]">Planos de intervencao recentes</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($planosRecentes as $plano)
                        <div class="rounded-2xl border border-slate-100 p-4">
                            <p class="font-semibold text-[#14363a]">{{ $plano->atendimento?->nome_atendido }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $plano->data_inicio?->format('d/m/Y') }} | {{ ucfirst($plano->status) }}</p>
                            <p class="mt-2 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($plano->objetivo_geral, 90) }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Sem planos cadastrados.</p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-[#14363a]">Encaminhamentos recentes</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($encaminhamentosRecentes as $encaminhamento)
                        <div class="rounded-2xl border border-slate-100 p-4">
                            <p class="font-semibold text-[#14363a]">{{ $encaminhamento->atendimento?->nome_atendido }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ ucfirst($encaminhamento->tipo) }} | {{ $encaminhamento->data_encaminhamento?->format('d/m/Y') }}</p>
                            <p class="mt-2 text-sm text-slate-600">{{ $encaminhamento->destino }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Sem encaminhamentos registrados.</p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-[#14363a]">Casos disciplinares recentes</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($casosRecentes as $caso)
                        <div class="rounded-2xl border border-slate-100 p-4">
                            <p class="font-semibold text-[#14363a]">{{ $caso->titulo }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $caso->data_ocorrencia?->format('d/m/Y') }} | {{ ucfirst($caso->status) }}</p>
                            <p class="mt-2 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($caso->descricao_sigilosa, 90) }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Sem casos disciplinares vinculados.</p>
                    @endforelse
                </div>
            </section>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <a href="{{ route('psicologia.agenda') }}" class="rounded-[1.75rem] border border-cyan-100 bg-[linear-gradient(135deg,rgba(236,254,255,0.95)_0%,rgba(255,255,255,0.96)_100%)] p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-cyan-700">Fluxo</p>
                <p class="mt-3 text-lg font-bold text-[#14363a]">Acessar agenda</p>
            </a>
            <a href="{{ route('psicologia.create') }}" class="rounded-[1.75rem] border border-emerald-100 bg-[linear-gradient(135deg,rgba(236,253,245,0.95)_0%,rgba(255,255,255,0.96)_100%)] p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-emerald-700">Fluxo</p>
                <p class="mt-3 text-lg font-bold text-[#14363a]">Novo atendimento</p>
            </a>
            <a href="{{ route('psicologia.planos.index') }}" class="rounded-[1.75rem] border border-amber-100 bg-[linear-gradient(135deg,rgba(255,251,235,0.96)_0%,rgba(255,255,255,0.96)_100%)] p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-amber-700">Tecnico</p>
                <p class="mt-3 text-lg font-bold text-[#14363a]">Planos e acompanhamento</p>
            </a>
            <a href="{{ route('psicologia.relatorios_tecnicos.index') }}" class="rounded-[1.75rem] border border-rose-100 bg-[linear-gradient(135deg,rgba(255,241,242,0.96)_0%,rgba(255,255,255,0.96)_100%)] p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-rose-700">Restrito</p>
                <p class="mt-3 text-lg font-bold text-[#14363a]">Relatorios tecnicos</p>
            </a>
        </div>
    </div>
</x-psicologia-layout>
