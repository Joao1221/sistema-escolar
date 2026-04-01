<x-secretaria-escolar-layout>
    <div class="px-8 py-6 space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-emerald-600">Demanda psicossocial</p>
                <h1 class="mt-2 text-3xl font-bold text-slate-900">{{ $demanda->nome_atendido ?? 'Demanda escolar' }}</h1>
                <p class="mt-2 text-sm text-slate-500">
                    {{ ucfirst($demanda->tipo_publico) }} |
                    {{ ucfirst($demanda->tipo_atendimento) }} |
                    {{ ucfirst(str_replace('_', ' ', $demanda->origem_demanda)) }}
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('secretaria-escolar.demandas-psicossociais.index') }}" class="rounded-xl border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50">Voltar</a>
                @can('registrar demandas psicossociais escolares')
                    <a href="{{ route('secretaria-escolar.demandas-psicossociais.create') }}" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Nova demanda</a>
                @endcan
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.15fr_0.85fr]">
            <section class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-slate-900">Dados da demanda</h2>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div>
                        <span class="text-xs uppercase tracking-widest text-slate-500">Escola</span>
                        <p class="mt-1 font-semibold text-slate-900">{{ $demanda->escola?->nome }}</p>
                    </div>
                    <div>
                        <span class="text-xs uppercase tracking-widest text-slate-500">Data da solicitacao</span>
                        <p class="mt-1 font-semibold text-slate-900">{{ $demanda->data_solicitacao?->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <span class="text-xs uppercase tracking-widest text-slate-500">Prioridade</span>
                        <p class="mt-1 font-semibold text-slate-900">{{ ucfirst($demanda->prioridade) }}</p>
                    </div>
                    <div>
                        <span class="text-xs uppercase tracking-widest text-slate-500">Registrada por</span>
                        <p class="mt-1 font-semibold text-slate-900">{{ $demanda->usuarioRegistro?->name }}</p>
                    </div>
                </div>

                <div class="mt-6 space-y-4 text-sm text-slate-700">
                    <div>
                        <span class="text-xs uppercase tracking-widest text-slate-500">Motivo inicial</span>
                        <p class="mt-1 whitespace-pre-line">{{ $demanda->motivo_inicial }}</p>
                    </div>

                    @if ($demanda->observacoes)
                        <div>
                            <span class="text-xs uppercase tracking-widest text-slate-500">Observacoes da escola</span>
                            <p class="mt-1 whitespace-pre-line">{{ $demanda->observacoes }}</p>
                        </div>
                    @endif

                    @if ($demanda->tipo_publico === 'aluno' && $demanda->aluno)
                        <div>
                            <span class="text-xs uppercase tracking-widest text-slate-500">Aluno</span>
                            <p class="mt-1 font-semibold text-slate-900">{{ $demanda->aluno->nome_completo }}</p>
                        </div>
                    @endif

                    @if (in_array($demanda->tipo_publico, ['professor', 'funcionario'], true) && $demanda->funcionario)
                        <div>
                            <span class="text-xs uppercase tracking-widest text-slate-500">Professor / Funcionario</span>
                            <p class="mt-1 font-semibold text-slate-900">{{ $demanda->funcionario->nome }}</p>
                        </div>
                    @endif

                    @if ($demanda->tipo_publico === 'responsavel')
                        <div>
                            <span class="text-xs uppercase tracking-widest text-slate-500">Responsavel</span>
                            <p class="mt-1 font-semibold text-slate-900">{{ $demanda->responsavel_nome }}</p>
                            <p class="mt-1 text-slate-600">{{ $demanda->responsavel_vinculo }}{{ $demanda->responsavel_telefone ? ' | ' . $demanda->responsavel_telefone : '' }}</p>
                        </div>
                    @endif

                    @if ($demanda->tipo_publico === 'coletivo')
                        <div>
                            <span class="text-xs uppercase tracking-widest text-slate-500">Atendimento coletivo</span>
                            <p class="mt-1 text-slate-700">Demanda sem vinculo individual, destinada a turma, serie, turno ou escola inteira.</p>
                        </div>
                    @endif
                </div>
            </section>

            <section class="space-y-6">
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-slate-900">Acompanhamento</h2>
                    <div class="mt-5 space-y-4 text-sm text-slate-700">
                        <div>
                            <span class="text-xs uppercase tracking-widest text-slate-500">Status atual</span>
                            <p class="mt-1 font-semibold text-slate-900">{{ ucfirst(str_replace('_', ' ', $demanda->status)) }}</p>
                        </div>
                        <div>
                            <span class="text-xs uppercase tracking-widest text-slate-500">Profissional responsavel</span>
                            <p class="mt-1 font-semibold text-slate-900">{{ $demanda->atendimento?->profissionalResponsavel?->nome ?? 'Aguardando atribuicao' }}</p>
                        </div>
                        <div>
                            <span class="text-xs uppercase tracking-widest text-slate-500">Atendimento vinculado</span>
                            <p class="mt-1 font-semibold text-slate-900">
                                @if ($demanda->atendimento)
                                    {{ ucfirst(str_replace('_', ' ', $demanda->atendimento->status)) }}
                                @else
                                    Ainda nao iniciado
                                @endif
                            </p>
                        </div>
                        @if ($demanda->atendimento?->data_agendada)
                            <div>
                                <span class="text-xs uppercase tracking-widest text-slate-500">Primeiro agendamento tecnico</span>
                                <p class="mt-1 font-semibold text-slate-900">{{ $demanda->atendimento->data_agendada->format('d/m/Y H:i') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">Devolutivas para a escola</h2>
                            <p class="mt-1 text-sm text-slate-500">Somente devolutivas destinadas ao seu perfil ficam visiveis neste painel.</p>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">{{ $devolutivas->count() }}</span>
                    </div>

                    <div class="mt-5 space-y-4">
                        @forelse ($devolutivas as $devolutiva)
                            @php
                                $destinatarioLabel = match ($devolutiva->destinatario) {
                                    'coordenacao' => 'Coordenacao',
                                    'direcao' => 'Direcao',
                                    default => ucfirst($devolutiva->destinatario),
                                };
                            @endphp
                            <article class="rounded-2xl border border-slate-200 p-4">
                                <div class="flex flex-col gap-2 md:flex-row md:items-start md:justify-between">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $destinatarioLabel }}</p>
                                        <p class="mt-1 text-xs uppercase tracking-widest text-slate-500">
                                            {{ $devolutiva->data_devolutiva?->format('d/m/Y') }} |
                                            {{ $devolutiva->usuarioResponsavel?->name ?? 'Equipe psicossocial' }}
                                        </p>
                                    </div>
                                    @if ($devolutiva->necessita_acompanhamento)
                                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700">Requer acompanhamento</span>
                                    @endif
                                </div>

                                @if ($devolutiva->nome_destinatario)
                                    <p class="mt-3 text-sm text-slate-600"><span class="font-semibold text-slate-700">Destinatario:</span> {{ $devolutiva->nome_destinatario }}</p>
                                @endif

                                @if ($devolutiva->resumo_devolutiva)
                                    <div class="mt-3">
                                        <span class="text-xs uppercase tracking-widest text-slate-500">Resumo</span>
                                        <p class="mt-1 whitespace-pre-line text-sm text-slate-700">{{ $devolutiva->resumo_devolutiva }}</p>
                                    </div>
                                @endif

                                @if ($devolutiva->orientacoes)
                                    <div class="mt-3">
                                        <span class="text-xs uppercase tracking-widest text-slate-500">Orientacoes</span>
                                        <p class="mt-1 whitespace-pre-line text-sm text-slate-700">{{ $devolutiva->orientacoes }}</p>
                                    </div>
                                @endif

                                @if ($devolutiva->encaminhamentos_combinados)
                                    <div class="mt-3">
                                        <span class="text-xs uppercase tracking-widest text-slate-500">Encaminhamentos combinados</span>
                                        <p class="mt-1 whitespace-pre-line text-sm text-slate-700">{{ $devolutiva->encaminhamentos_combinados }}</p>
                                    </div>
                                @endif
                            </article>
                        @empty
                            <div class="rounded-2xl border border-dashed border-slate-200 px-4 py-8 text-center text-sm text-slate-500">
                                Nenhuma devolutiva destinada ao seu perfil foi registrada ate o momento.
                            </div>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-secretaria-escolar-layout>
