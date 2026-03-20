<x-psicologia-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    <div class="space-y-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-cyan-700">Demanda</p>
                <h1 class="mt-2 text-3xl font-bold text-[#14363a]">{{ $demanda->nome_atendido }}</h1>
                <p class="mt-2 text-sm text-slate-500">
                    {{ ucfirst($demanda->tipo_publico) }} | 
                    {{ ucfirst($demanda->tipo_atendimento) }} | 
                    {{ ucfirst(str_replace('_', ' ', $demanda->origem_demanda)) }}
                </p>
            </div>
            <div class="flex gap-3">
                @if ($demanda->status === 'aberta')
                    <span class="rounded-xl bg-blue-100 px-4 py-2 text-sm font-semibold text-blue-700">Aguardando triagem</span>
                @elseif ($demanda->status === 'em_triagem')
                    <span class="rounded-xl bg-purple-100 px-4 py-2 text-sm font-semibold text-purple-700">Em triagem</span>
                @elseif ($demanda->status === 'em_atendimento')
                    <a href="{{ route('psicologia.show', $demanda->atendimento) }}" class="rounded-xl border border-emerald-600 bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">
                        Ver atendimento
                    </a>
                @elseif ($demanda->status === 'encerrada')
                    <span class="rounded-xl bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-600">Encerrada</span>
                @endif
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[1.2fr_1fr]">
            <section class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-[#14363a]">Dados da demanda</h2>
                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    <div><span class="text-xs uppercase tracking-widest text-slate-500">Escola</span><p class="mt-1 font-semibold text-[#14363a]">{{ $demanda->escola?->nome }}</p></div>
                    <div><span class="text-xs uppercase tracking-widest text-slate-500">Prioridade</span>
                        <p class="mt-1">
                            @if ($demanda->prioridade === 'urgente')
                                <span class="rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-700">Urgente</span>
                            @elseif ($demanda->prioridade === 'alta')
                                <span class="rounded-full bg-orange-100 px-2 py-1 text-xs font-semibold text-orange-700">Alta</span>
                            @elseif ($demanda->prioridade === 'media')
                                <span class="rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-700">Media</span>
                            @else
                                <span class="rounded-full bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600">Baixa</span>
                            @endif
                        </p>
                    </div>
                    <div><span class="text-xs uppercase tracking-widest text-slate-500">Status</span><p class="mt-1 font-semibold text-[#14363a]">{{ ucfirst(str_replace('_', ' ', $demanda->status)) }}</p></div>
                    <div><span class="text-xs uppercase tracking-widest text-slate-500">Data solicitacao</span><p class="mt-1 font-semibold text-[#14363a]">{{ $demanda->data_solicitacao->format('d/m/Y') }}</p></div>
                </div>
                <div class="mt-5 space-y-4 text-sm text-slate-700">
                    <div><span class="text-xs uppercase tracking-widest text-slate-500">Motivo inicial</span><p class="mt-1 whitespace-pre-line">{{ $demanda->motivo_inicial }}</p></div>
                    @if ($demanda->observacoes)
                        <div><span class="text-xs uppercase tracking-widest text-slate-500">Observacoes</span><p class="mt-1 whitespace-pre-line">{{ $demanda->observacoes }}</p></div>
                    @endif
                </div>
            </section>

            @if ($demanda->status !== 'em_atendimento' && $demanda->status !== 'encerrada')
                <section class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-[#14363a]">Triagem inicial</h2>
                    <form method="POST" action="{{ route('psicologia.demandas.triagem', $demanda) }}" class="mt-5 space-y-4">
                        @csrf
                        
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Urgencia</label>
                                <select name="urgencia" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                                    <option value="baixa">Baixa</option>
                                    <option value="media" selected>Media</option>
                                    <option value="alta">Alta</option>
                                    <option value="critica">Critica</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Nivel de sigilo</label>
                                <select name="nivel_sigilo" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                                    <option value="normal">Normal</option>
                                    <option value="reforcado">Reforado</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="risco_identificado" value="1" class="rounded border-slate-300">
                                <span class="text-sm font-semibold text-slate-700">Risco identificado</span>
                            </label>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Resumo do caso</label>
                            <textarea name="resumo_caso" rows="3" placeholder="Resumo breve da situacao..." class="mt-1 w-full rounded-xl border-slate-300 shadow-sm"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Sinais observados</label>
                            <textarea name="sinais_observados" rows="2" placeholder="Comportamentos ou sinais observados..." class="mt-1 w-full rounded-xl border-slate-300 shadow-sm"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Historico breve</label>
                            <textarea name="historico_breve" rows="2" placeholder="Contexto historico relevante..." class="mt-1 w-full rounded-xl border-slate-300 shadow-sm"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Decisao *</label>
                            <select name="decisao" required class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                                <option value="">Selecione</option>
                                <option value="iniciar_atendimento">Iniciar atendimento</option>
                                <option value="observar">Observar</option>
                                <option value="encaminhar_externo">Encaminhar externo</option>
                                <option value="devolver_pedagogico">Devolver para pedagógico</option>
                                <option value="encerrar_sem_atendimento">Encerrar sem atendimento</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Justificativa</label>
                            <textarea name="justificativa_decisao" rows="2" placeholder="Justifique a decisao tomada..." class="mt-1 w-full rounded-xl border-slate-300 shadow-sm"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Profissional responsavel (se atendimento)</label>
                            <select name="profissional_responsavel_id" class="mt-1 w-full rounded-xl border-slate-300 shadow-sm">
                                <option value="">Selecione</option>
                                @foreach ($funcionarios as $funcionario)
                                    <option value="{{ $funcionario->id }}">{{ $funcionario->nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500">Observacoes da triagem</label>
                            <textarea name="observacoes" rows="2" placeholder="Observacoes adicionais..." class="mt-1 w-full rounded-xl border-slate-300 shadow-sm"></textarea>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full rounded-xl border border-emerald-600 bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">
                                Concluir triagem
                            </button>
                        </div>
                    </form>
                </section>
            @else
                <section class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-bold text-[#14363a]">Triagem realizada</h2>
                    @if ($demanda->triagem)
                        <div class="mt-5 space-y-4 text-sm">
                            <div><span class="text-xs uppercase tracking-widest text-slate-500">Urgencia</span><p class="mt-1 font-semibold text-[#14363a]">{{ ucfirst($demanda->triagem->urgencia) }}</p></div>
                            <div><span class="text-xs uppercase tracking-widest text-slate-500">Decisao</span><p class="mt-1 font-semibold text-[#14363a]">{{ ucfirst(str_replace('_', ' ', $demanda->triagem->decisao)) }}</p></div>
                            @if ($demanda->triagem->resumo_caso)
                                <div><span class="text-xs uppercase tracking-widest text-slate-500">Resumo</span><p class="mt-1 text-slate-700">{{ $demanda->triagem->resumo_caso }}</p></div>
                            @endif
                            @if ($demanda->triagem->justificativa_decisao)
                                <div><span class="text-xs uppercase tracking-widest text-slate-500">Justificativa</span><p class="mt-1 text-slate-700">{{ $demanda->triagem->justificativa_decisao }}</p></div>
                            @endif
                        </div>
                    @else
                        <p class="mt-4 text-sm text-slate-500">Triagem nao realizada.</p>
                    @endif
                </section>
            @endif
        </div>
    </div>
</x-psicologia-layout>
