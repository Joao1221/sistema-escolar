<x-psicologia-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    @php
        $rotulosPublico = [
            'aluno' => 'Alunos',
            'professor' => 'Professores',
            'funcionario' => 'Funcionarios',
            'responsavel' => 'Pais/Responsaveis',
            'coletivo' => 'Coletivos',
        ];
    @endphp

    <div class="space-y-6">
        <div class="grid gap-4 grid-cols-2 md:grid-cols-5">
            <a href="{{ route('psicologia.demandas.index', ['escola_id' => '', 'status' => 'aberta', 'prioridade' => '']) }}" class="rounded-[1.75rem] border border-cyan-100 bg-gradient-to-r from-cyan-50 via-cyan-25 to-cyan-100 p-4 shadow-sm block transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="h-1.5 w-16 rounded-full bg-cyan-400"></div>
                <p class="mt-4 text-xs font-semibold uppercase tracking-[0.24em] text-cyan-700 break-words leading-tight">Demandas abertas</p>
                <p class="mt-4 text-3xl font-bold text-[#14363a]">{{ $totais['demandas_abertas'] }}</p>
            </a>
            <div class="rounded-[1.75rem] border border-emerald-100 bg-gradient-to-r from-emerald-50 via-emerald-25 to-emerald-100 p-4 shadow-sm">
                <div class="h-1.5 w-16 rounded-full bg-emerald-400"></div>
                <p class="mt-4 text-xs font-semibold uppercase tracking-[0.24em] text-emerald-700 break-words leading-tight">Atendimentos em aberto</p>
                <p class="mt-4 text-3xl font-bold text-[#14363a]">{{ $totais['atendimentos_abertos'] }}</p>
            </div>
            <div class="rounded-[1.75rem] border border-amber-100 bg-gradient-to-r from-amber-50 via-amber-25 to-amber-100 p-4 shadow-sm">
                <div class="h-1.5 w-16 rounded-full bg-amber-400"></div>
                <p class="mt-4 text-xs font-semibold uppercase tracking-[0.24em] text-amber-700 break-words leading-tight">Atendimentos realizados</p>
                <p class="mt-4 text-3xl font-bold text-[#14363a]">{{ $totais['atendimentos_realizados'] }}</p>
            </div>
            <div class="rounded-[1.75rem] border border-rose-100 bg-gradient-to-r from-rose-50 via-rose-25 to-rose-100 p-4 shadow-sm">
                <div class="h-1.5 w-16 rounded-full bg-rose-400"></div>
                <p class="mt-4 text-xs font-semibold uppercase tracking-[0.24em] text-rose-700 break-words leading-tight">Planos ativos</p>
                <p class="mt-4 text-3xl font-bold text-[#14363a]">{{ $totais['planos_ativos'] }}</p>
            </div>
            <div class="rounded-[1.75rem] border border-sky-100 bg-gradient-to-r from-sky-50 via-sky-25 to-sky-100 p-4 shadow-sm">
                <div class="h-1.5 w-16 rounded-full bg-sky-400"></div>
                <p class="mt-4 text-xs font-semibold uppercase tracking-[0.24em] text-sky-700 break-words leading-tight">Encaminhamentos abertos</p>
                <p class="mt-4 text-3xl font-bold text-[#14363a]">{{ $totais['encaminhamentos_abertos'] }}</p>
            </div>
            <div class="rounded-[1.75rem] border border-lime-100 bg-gradient-to-r from-lime-50 via-lime-25 to-lime-100 p-4 shadow-sm">
                <div class="h-1.5 w-16 rounded-full bg-lime-400"></div>
                <p class="mt-4 text-xs font-semibold uppercase tracking-[0.24em] text-lime-700 break-words leading-tight">Casos disciplinares</p>
                <p class="mt-4 text-3xl font-bold text-[#14363a]">{{ $totais['casos_abertos'] }}</p>
            </div>
            @foreach ($rotulosPublico as $tipo => $rotulo)
                <div @class([
                    'rounded-[1.75rem] border p-4 shadow-sm',
                    'border-sky-100 bg-gradient-to-r from-sky-50 via-sky-25 to-sky-100' => $tipo === 'aluno',
                    'border-emerald-100 bg-gradient-to-r from-emerald-50 via-emerald-25 to-emerald-100' => $tipo === 'professor',
                    'border-amber-100 bg-gradient-to-r from-amber-50 via-amber-25 to-amber-100' => $tipo === 'funcionario',
                    'border-rose-100 bg-gradient-to-r from-rose-50 via-rose-25 to-rose-100' => $tipo === 'responsavel',
                    'border-violet-100 bg-gradient-to-r from-violet-50 via-violet-25 to-violet-100' => $tipo === 'coletivo',
                ])>
                    <div @class([
                        'h-1.5 w-16 rounded-full',
                        'bg-sky-300' => $tipo === 'aluno',
                        'bg-emerald-300' => $tipo === 'professor',
                        'bg-amber-300' => $tipo === 'funcionario',
                        'bg-rose-300' => $tipo === 'responsavel',
                        'bg-violet-300' => $tipo === 'coletivo',
                    ])></div>
                    <p @class([
                        'mt-4 text-xs font-semibold uppercase tracking-[0.24em] break-words leading-tight',
                        'text-sky-700' => $tipo === 'aluno',
                        'text-emerald-700' => $tipo === 'professor',
                        'text-amber-700' => $tipo === 'funcionario',
                        'text-rose-700' => $tipo === 'responsavel',
                        'text-violet-700' => $tipo === 'coletivo',
                    ])>{{ $rotulo }}</p>
                    <p class="mt-4 text-3xl font-bold text-[#14363a]">{{ $porPublico[$tipo] ?? 0 }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.4fr_1fr]">
            <section class="rounded-[1.75rem] border border-cyan-100 bg-gradient-to-r from-cyan-50 via-cyan-25 to-cyan-100 shadow-sm">
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
                <div class="rounded-[1.75rem] border border-emerald-100 bg-gradient-to-r from-emerald-50 via-emerald-25 to-emerald-100 shadow-sm">
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

                <div class="rounded-[1.75rem] border border-rose-100 bg-gradient-to-r from-rose-50 via-rose-25 to-rose-100 shadow-sm">
                    <div class="border-b border-slate-100 px-6 py-4">
                        <h2 class="text-lg font-bold text-[#14363a]">Relatorios tecnicos recentes</h2>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @forelse ($relatoriosRecentes as $relatorio)
                            <a href="{{ route('psicologia.relatorios_tecnicos.show', $relatorio) }}" class="block px-6 py-4 transition hover:bg-slate-50">
                                <p class="font-semibold text-[#14363a]">{{ $relatorio->titulo }}</p>
                                <p class="mt-1 text-xs text-slate-500">{{ $relatorio->data_emissao->format('d/m/Y') }} | {{ ucfirst(str_replace('_', ' ', $relatorio->tipo_relatorio)) }}</p>
                            </a>
                        @empty
                            <p class="px-6 py-8 text-sm text-slate-500">Nenhum relatorio tecnico emitido.</p>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>

        <div class="grid gap-6 xl:grid-cols-3">
            <section class="rounded-[1.75rem] border border-amber-100 bg-gradient-to-r from-amber-50 via-amber-25 to-amber-100 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-[#14363a]">Planos de intervencao recentes</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($planosRecentes as $plano)
                            <div class="rounded-2xl border border-amber-100/80 bg-gradient-to-r from-amber-50 via-amber-25 to-amber-100 p-4">
                            <p class="font-semibold text-[#14363a]">{{ $plano->atendimento?->nome_atendido }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $plano->data_inicio?->format('d/m/Y') }} | {{ ucfirst($plano->status) }}</p>
                            <p class="mt-2 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($plano->objetivo_geral, 90) }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Sem planos cadastrados.</p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-[1.75rem] border border-sky-100 bg-gradient-to-r from-sky-50 via-sky-25 to-sky-100 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-[#14363a]">Encaminhamentos recentes</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($encaminhamentosRecentes as $encaminhamento)
                            <div class="rounded-2xl border border-sky-100/80 bg-gradient-to-r from-sky-50 via-sky-25 to-sky-100 p-4">
                            <p class="font-semibold text-[#14363a]">{{ $encaminhamento->atendimento?->nome_atendido }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ ucfirst($encaminhamento->tipo) }} | {{ $encaminhamento->data_encaminhamento?->format('d/m/Y') }}</p>
                            <p class="mt-2 text-sm text-slate-600">{{ $encaminhamento->destino }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Sem encaminhamentos registrados.</p>
                    @endforelse
                </div>
            </section>

            <section class="rounded-[1.75rem] border border-violet-100 bg-gradient-to-r from-violet-50 via-violet-25 to-violet-100 p-6 shadow-sm">
                <h2 class="text-lg font-bold text-[#14363a]">Casos disciplinares recentes</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($casosRecentes as $caso)
                            <div class="rounded-2xl border border-violet-100/80 bg-gradient-to-r from-violet-50 via-violet-25 to-violet-100 p-4">
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
            <a href="{{ route('psicologia.demandas.index') }}" class="rounded-[1.75rem] border border-cyan-100 bg-gradient-to-r from-cyan-50 via-cyan-25 to-cyan-100 p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-cyan-700">Fluxo</p>
                <p class="mt-3 text-lg font-bold text-[#14363a]">Demandas</p>
            </a>
            <a href="{{ route('psicologia.demandas.create') }}" class="rounded-[1.75rem] border border-emerald-100 bg-gradient-to-r from-emerald-50 via-emerald-25 to-emerald-100 p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-emerald-700">Fluxo</p>
                <p class="mt-3 text-lg font-bold text-[#14363a]">Nova demanda</p>
            </a>
            <a href="{{ route('psicologia.planos.index') }}" class="rounded-[1.75rem] border border-amber-100 bg-gradient-to-r from-amber-50 via-amber-25 to-amber-100 p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-amber-700">Tecnico</p>
                <p class="mt-3 text-lg font-bold text-[#14363a]">Planos e acompanhamento</p>
            </a>
            <a href="{{ route('psicologia.relatorios_tecnicos.index') }}" class="rounded-[1.75rem] border border-rose-100 bg-gradient-to-r from-rose-50 via-rose-25 to-rose-100 p-5 transition hover:-translate-y-0.5 hover:shadow-md">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-rose-700">Restrito</p>
                <p class="mt-3 text-lg font-bold text-[#14363a]">Relatorios tecnicos</p>
            </a>
        </div>
    </div>
</x-psicologia-layout>
