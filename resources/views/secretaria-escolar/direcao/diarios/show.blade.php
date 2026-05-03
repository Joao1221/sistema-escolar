<x-secretaria-escolar-layout>
    @php
        $statusClasses = [
            'validado' => 'bg-emerald-100 text-emerald-800',
            'ajustes_solicitados' => 'bg-amber-100 text-amber-800',
            'pendente' => 'bg-slate-100 text-slate-700',
        ];
        $camposPlanejamentoAulas = [
            'tema_gerador' => 'Unidade(s) Tematica(s)',
            'conteudos' => 'Objeto(s) de Conhecimento / Conteudo(s)',
            'habilidades_competencias' => 'Habilidades (BNCC)',
            'objetivos_aprendizagem' => 'Objetivos da aula',
            'metodologia' => 'Metodologias / estrategias',
            'estrategias_pedagogicas' => 'Atividades (desenvolvimento da aula)',
            'recursos_didaticos' => 'Recursos didaticos',
            'instrumentos_avaliacao' => 'Avaliacao da aprendizagem',
            'adequacoes_inclusao' => 'Adequacoes / Inclusao (AEE / adaptacoes)',
            'observacoes' => 'Observacoes do professor',
            'referencias' => 'Referencias bibliograficas',
        ];
    @endphp

    <div class="space-y-8">
        <section class="rounded-[2rem] border border-indigo-100 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-5 xl:flex-row xl:items-start xl:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.28em] text-indigo-600">Direcao Escolar</p>
                    <h1 class="mt-3 text-3xl font-outfit font-bold text-slate-900">{{ $diario->turma->nome }} • {{ $diario->disciplina->nome }}</h1>
                    <p class="mt-2 text-sm text-slate-600">{{ $diario->escola->nome }} • {{ $diario->professor->nome }}</p>
                    <p class="mt-1 text-sm text-slate-500">{{ ucfirst($diario->periodo_tipo) }} {{ $diario->periodo_referencia }} • {{ $diario->ano_letivo }}</p>
                    @can('consultar horarios da direcao')
                        <div class="mt-4">
                            <a href="{{ route('secretaria-escolar.direcao.horarios.index', ['turma_id' => $diario->turma_id]) }}" class="inline-flex items-center rounded-2xl border border-indigo-200 px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-indigo-700 transition hover:bg-indigo-50">
                                Horarios da turma
                            </a>
                        </div>
                    @endcan
                </div>
                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl bg-indigo-50 px-4 py-3"><p class="text-[11px] font-bold uppercase tracking-[0.2em] text-indigo-700">Planejamentos</p><p class="mt-2 text-sm font-semibold text-indigo-950">{{ $metricas['planejamentos_validados'] }}</p></div>
                    <div class="rounded-2xl bg-sky-50 px-4 py-3"><p class="text-[11px] font-bold uppercase tracking-[0.2em] text-sky-700">Aulas</p><p class="mt-2 text-sm font-semibold text-sky-950">{{ $metricas['aulas_validadas'] }}</p></div>
                    <div class="rounded-2xl bg-violet-50 px-4 py-3"><p class="text-[11px] font-bold uppercase tracking-[0.2em] text-violet-700">Avaliacoes</p><p class="mt-2 text-sm font-semibold text-violet-950">{{ $metricas['avaliacoes_lancadas'] }}</p></div>
                    <div class="rounded-2xl bg-emerald-50 px-4 py-3"><p class="text-[11px] font-bold uppercase tracking-[0.2em] text-emerald-700">Justificadas</p><p class="mt-2 text-sm font-semibold text-emerald-950">{{ $metricas['faltas_justificadas'] }}</p></div>
                    <div class="rounded-2xl bg-amber-50 px-4 py-3"><p class="text-[11px] font-bold uppercase tracking-[0.2em] text-amber-700">Prazos</p><p class="mt-2 text-sm font-semibold text-amber-950">{{ $metricas['liberacoes_ativas'] }}</p></div>
                    <div class="rounded-2xl bg-rose-50 px-4 py-3"><p class="text-[11px] font-bold uppercase tracking-[0.2em] text-rose-700">Pendencias</p><p class="mt-2 text-sm font-semibold text-rose-950">{{ $metricas['pendencias_abertas'] }}</p></div>
                </div>
            </div>
        </section>

        <section class="grid gap-8 xl:grid-cols-2">
            <div class="rounded-[2rem] border border-indigo-100 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-xl font-outfit font-semibold text-slate-900">Planejamento anual</h2>
                    <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] {{ $statusClasses[$diario->planejamentoAnual?->validacaoDirecao?->status ?? 'pendente'] }}">{{ str_replace('_', ' ', $diario->planejamentoAnual?->validacaoDirecao?->status ?? 'pendente') }}</span>
                </div>
                @if ($diario->planejamentoAnual)
                    <div class="mt-4 space-y-2 text-sm text-slate-600">
                        <div><span class="font-semibold text-slate-900">Tema:</span> {{ $diario->planejamentoAnual->tema_gerador ?: 'Nao informado' }}</div>
                        <div><span class="font-semibold text-slate-900">Objetivos:</span> {{ $diario->planejamentoAnual->objetivos_gerais }}</div>
                    </div>
                    <form method="POST" action="{{ route('secretaria-escolar.direcao.diarios.planejamento-anual.validar', [$diario, $diario->planejamentoAnual]) }}" class="mt-5 grid gap-4 rounded-[1.4rem] bg-slate-50 p-4">
                        @csrf
                        <select name="status" class="rounded-2xl border-slate-200 text-sm"><option value="validado">Validado</option><option value="ajustes_solicitados">Solicitar ajustes</option></select>
                        <textarea name="parecer" rows="3" class="rounded-2xl border-slate-200 text-sm" placeholder="Parecer da direcao"></textarea>
                        <textarea name="observacoes_internas" rows="2" class="rounded-2xl border-slate-200 text-sm" placeholder="Observacao interna"></textarea>
                        <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-white transition hover:bg-indigo-700">Validar planejamento anual</button>
                    </form>
                @else
                    <p class="mt-4 text-sm text-slate-500">O professor ainda nao registrou planejamento anual.</p>
                @endif
            </div>

            <div class="rounded-[2rem] border border-indigo-100 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-xl font-outfit font-semibold text-slate-900">Liberacao de prazo</h2>
                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-amber-800">{{ $diario->liberacoesPrazo->count() }} registro(s)</span>
                </div>
                <form method="POST" action="{{ route('secretaria-escolar.direcao.diarios.liberacoes.store', $diario) }}" class="mt-5 grid gap-4 rounded-[1.4rem] bg-slate-50 p-4">
                    @csrf
                    <div class="grid gap-4 md:grid-cols-2">
                        <select name="tipo_lancamento" class="rounded-2xl border-slate-200 text-sm"><option value="aula">Aula</option><option value="frequencia">Frequencia</option><option value="notas_conceitos">Notas ou conceitos</option></select>
                        <input type="date" name="data_limite" value="{{ now()->addDays(3)->toDateString() }}" class="rounded-2xl border-slate-200 text-sm">
                    </div>
                    <input type="text" name="motivo" class="rounded-2xl border-slate-200 text-sm" placeholder="Motivo da liberacao">
                    <textarea name="observacoes" rows="3" class="rounded-2xl border-slate-200 text-sm" placeholder="Observacoes administrativas"></textarea>
                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-white transition hover:bg-slate-800">Liberar prazo</button>
                </form>
                <div class="mt-4 space-y-3">
                    @forelse ($diario->liberacoesPrazo as $liberacao)
                        <div class="rounded-2xl border border-slate-200 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-semibold text-slate-900">{{ str_replace('_', ' ', $liberacao->tipo_lancamento) }}</p>
                                <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] {{ $liberacao->status === 'ativa' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">{{ $liberacao->status }}</span>
                            </div>
                            <p class="mt-2 text-sm text-slate-600">Limite: {{ optional($liberacao->data_limite)->format('d/m/Y') }} • {{ $liberacao->motivo }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Nenhuma liberacao de prazo registrada.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="grid gap-8 xl:grid-cols-2">
            <div class="rounded-[2rem] border border-indigo-100 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-xl font-outfit font-semibold text-slate-900">Planejamentos por periodo</h2>
                    <span class="rounded-full bg-cyan-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-cyan-800">{{ $diario->planejamentosPeriodo->count() }} registro(s)</span>
                </div>
                <div class="mt-5 space-y-4">
                    @forelse ($diario->planejamentosPeriodo as $planejamento)
                        <article class="rounded-[1.6rem] border border-slate-200 p-5">
                            <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ ucfirst($planejamento->tipo_planejamento) }} • {{ optional($planejamento->data_inicio)->format('d/m/Y') }} a {{ optional($planejamento->data_fim)->format('d/m/Y') }}</p>
                                    @if($planejamento->periodo_referencia)
                                        <p class="mt-1 text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Referencia: {{ $planejamento->periodo_referencia }}</p>
                                    @endif
                                </div>
                                <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] {{ $statusClasses[$planejamento->validacaoDirecao?->status ?? 'pendente'] }}">{{ str_replace('_', ' ', $planejamento->validacaoDirecao?->status ?? 'pendente') }}</span>
                            </div>
                            <div class="mt-4 grid gap-3 text-sm text-slate-600">
                                @foreach($camposPlanejamentoAulas as $campo => $rotulo)
                                    @if(filled($planejamento->{$campo}))
                                        <div>
                                            <span class="font-semibold text-slate-900">{{ $rotulo }}:</span><br>
                                            {!! nl2br(e($planejamento->{$campo})) !!}
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <form method="POST" action="{{ route('secretaria-escolar.direcao.diarios.planejamento-periodo.validar', [$diario, $planejamento]) }}" class="mt-4 grid gap-4 lg:grid-cols-[180px_1fr_1fr_auto]">
                                @csrf
                                <select name="status" class="rounded-2xl border-slate-200 text-sm"><option value="validado">Validado</option><option value="ajustes_solicitados">Solicitar ajustes</option></select>
                                <textarea name="parecer" rows="3" class="rounded-2xl border-slate-200 text-sm" placeholder="Parecer da direcao"></textarea>
                                <textarea name="observacoes_internas" rows="3" class="rounded-2xl border-slate-200 text-sm" placeholder="Observacao interna"></textarea>
                                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-cyan-600 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-white transition hover:bg-cyan-700">Validar</button>
                            </form>
                        </article>
                    @empty
                        <p class="text-sm text-slate-500">Nenhum planejamento por periodo registrado.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-[2rem] border border-indigo-100 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-xl font-outfit font-semibold text-slate-900">Notas e conceitos</h2>
                    <span class="rounded-full bg-violet-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-violet-800">{{ $diario->lancamentosAvaliativos->count() }} lancamento(s)</span>
                </div>
                <div class="mt-5 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 text-left text-xs uppercase tracking-[0.18em] text-slate-500">
                                <th class="px-3 py-3">Aluno</th>
                                <th class="px-3 py-3">Referencia</th>
                                <th class="px-3 py-3">Valor</th>
                                <th class="px-3 py-3">Alteracao</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($diario->lancamentosAvaliativos as $lancamento)
                                <tr class="border-b border-slate-100 align-top">
                                    <td class="px-3 py-4 font-semibold text-slate-900">{{ $lancamento->matricula->aluno->nome_completo }}</td>
                                    <td class="px-3 py-4 text-slate-700">{{ $lancamento->avaliacao_referencia }}</td>
                                    <td class="px-3 py-4 text-slate-700">{{ $lancamento->valor_numerico !== null ? number_format((float) $lancamento->valor_numerico, 2, ',', '.') : ($lancamento->conceito ?: '-') }}</td>
                                    <td class="px-3 py-4">
                                        @can('alterar notas e conceitos da direcao')
                                            <form method="POST" action="{{ route('secretaria-escolar.direcao.diarios.avaliacoes.update', [$diario, $lancamento]) }}" class="grid gap-3 rounded-[1.4rem] bg-slate-50 p-3">
                                                @csrf
                                                @method('PUT')
                                                <input type="text" name="avaliacao_referencia" value="{{ $lancamento->avaliacao_referencia }}" class="rounded-2xl border-slate-200 text-sm">
                                                @if ($lancamento->valor_numerico !== null)
                                                    <input type="number" name="valor_numerico" step="0.01" min="0" max="100" value="{{ $lancamento->valor_numerico }}" class="rounded-2xl border-slate-200 text-sm">
                                                @else
                                                    <input type="text" name="conceito" value="{{ $lancamento->conceito }}" class="rounded-2xl border-slate-200 text-sm">
                                                @endif
                                                <textarea name="observacoes" rows="2" class="rounded-2xl border-slate-200 text-sm" placeholder="Observacoes">{{ $lancamento->observacoes }}</textarea>
                                                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-violet-600 px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-white transition hover:bg-violet-700">Atualizar</button>
                                            </form>
                                        @else
                                            <span class="text-xs text-slate-400">Somente consulta</span>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-3 py-6 text-sm text-slate-500">Nenhum lancamento avaliativo encontrado.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="rounded-[2rem] border border-indigo-100 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <h2 class="text-xl font-outfit font-semibold text-slate-900">Aulas registradas</h2>
                <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-indigo-800">{{ $diario->registrosAula->count() }} aula(s)</span>
            </div>
            <div class="mt-5 space-y-5">
                @forelse ($diario->registrosAula as $registro)
                    <article class="rounded-[1.6rem] border border-slate-200 p-5">
                        <div class="flex flex-col gap-3 xl:flex-row xl:items-start xl:justify-between">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-900">{{ optional($registro->data_aula)->format('d/m/Y') }} • {{ $registro->titulo }}</p>
                                <p class="mt-2 text-sm text-slate-600">{{ $registro->conteudo_ministrado }}</p>
                                <p class="mt-2 text-xs uppercase tracking-[0.18em] text-slate-400">{{ $registro->frequencias->count() }} frequencia(s) • {{ $registro->quantidade_aulas }} aula(s)</p>
                            </div>
                            <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] {{ $statusClasses[$registro->validacaoDirecao?->status ?? 'pendente'] }}">{{ str_replace('_', ' ', $registro->validacaoDirecao?->status ?? 'pendente') }}</span>
                        </div>
                        <form method="POST" action="{{ route('secretaria-escolar.direcao.diarios.registro-aula.validar', [$diario, $registro]) }}" class="mt-4 grid gap-4 lg:grid-cols-[180px_1fr_1fr_auto]">
                            @csrf
                            <select name="status" class="rounded-2xl border-slate-200 text-sm"><option value="validado">Validado</option><option value="ajustes_solicitados">Solicitar ajustes</option></select>
                            <textarea name="parecer" rows="3" class="rounded-2xl border-slate-200 text-sm" placeholder="Parecer da direcao"></textarea>
                            <textarea name="observacoes_internas" rows="3" class="rounded-2xl border-slate-200 text-sm" placeholder="Observacao interna"></textarea>
                            <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-sky-600 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-white transition hover:bg-sky-700">Validar aula</button>
                        </form>
                        @can('ajustar aulas da direcao')
                            <form method="POST" action="{{ route('secretaria-escolar.direcao.diarios.registro-aula.update', [$diario, $registro]) }}" class="mt-4 grid gap-4 rounded-[1.4rem] bg-slate-50 p-4 xl:grid-cols-5">
                                @csrf
                                @method('PUT')
                                <div><label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Data</label><input type="date" name="data_aula" value="{{ optional($registro->data_aula)->format('Y-m-d') }}" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm"></div>
                                <div class="xl:col-span-2"><label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Titulo</label><input type="text" name="titulo" value="{{ $registro->titulo }}" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm"></div>
                                <div><label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Quantidade</label><input type="number" name="quantidade_aulas" min="1" value="{{ $registro->quantidade_aulas }}" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm"></div>
                                <div class="flex items-end"><label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700"><input type="hidden" name="aula_dada" value="0"><input type="checkbox" name="aula_dada" value="1" class="rounded border-slate-300 text-indigo-600" @checked($registro->aula_dada)>Aula dada</label></div>
                                <div class="xl:col-span-2"><label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Conteudo previsto</label><textarea name="conteudo_previsto" rows="3" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm">{{ $registro->conteudo_previsto }}</textarea></div>
                                <div class="xl:col-span-2"><label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Conteudo ministrado</label><textarea name="conteudo_ministrado" rows="3" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm">{{ $registro->conteudo_ministrado }}</textarea></div>
                                <div><label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Metodologia</label><textarea name="metodologia" rows="3" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm">{{ $registro->metodologia }}</textarea></div>
                                <div class="xl:col-span-4"><label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Recursos utilizados</label><textarea name="recursos_utilizados" rows="2" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm">{{ $registro->recursos_utilizados }}</textarea></div>
                                <div class="xl:col-span-5 flex justify-end"><button type="submit" class="inline-flex items-center rounded-2xl bg-slate-900 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-white transition hover:bg-slate-800">Ajustar aula</button></div>
                            </form>
                        @endcan
                    </article>
                @empty
                    <p class="text-sm text-slate-500">Nenhuma aula registrada neste diario.</p>
                @endforelse
            </div>
        </section>

        <section class="grid gap-8 xl:grid-cols-[1.1fr_0.9fr]">
            <div class="rounded-[2rem] border border-indigo-100 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-xl font-outfit font-semibold text-slate-900">Justificativa de faltas dos alunos</h2>
                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-emerald-800">{{ $frequenciasPassiveisJustificativa->count() }} registro(s)</span>
                </div>
                <div class="mt-5 space-y-4">
                    @forelse ($frequenciasPassiveisJustificativa as $frequencia)
                        <article class="rounded-[1.6rem] border border-slate-200 p-5">
                            <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $frequencia->matricula->aluno->nome_completo }}</p>
                                    <p class="mt-1 text-sm text-slate-600">{{ optional($frequencia->registroAula->data_aula)->format('d/m/Y') }} • {{ $frequencia->registroAula->titulo }}</p>
                                    <p class="mt-1 text-sm text-slate-500">Situacao atual: {{ str_replace('_', ' ', $frequencia->situacao) }}</p>
                                </div>
                                @if ($frequencia->justificativaDirecao)
                                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-emerald-800">Justificada</span>
                                @endif
                            </div>
                            <form method="POST" action="{{ route('secretaria-escolar.direcao.diarios.frequencias.justificar', [$diario, $frequencia]) }}" class="mt-4 grid gap-4 lg:grid-cols-[1fr_220px_auto]">
                                @csrf
                                <textarea name="motivo" rows="3" class="rounded-2xl border-slate-200 text-sm" placeholder="Fundamentacao da justificativa">{{ $frequencia->justificativaDirecao?->motivo }}</textarea>
                                <input type="text" name="documento_comprobatorio" value="{{ $frequencia->justificativaDirecao?->documento_comprobatorio }}" class="rounded-2xl border-slate-200 text-sm" placeholder="Documento">
                                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-white transition hover:bg-emerald-700">Justificar</button>
                            </form>
                        </article>
                    @empty
                        <p class="text-sm text-slate-500">Nao ha faltas passiveis de justificativa neste diario.</p>
                    @endforelse
                </div>
            </div>

            <div class="space-y-5">
                <section class="rounded-[2rem] border border-indigo-100 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="text-xl font-outfit font-semibold text-slate-900">Horarios da turma</h2>
                        <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-sky-800">{{ $horariosTurma->count() }} aula(s)</span>
                    </div>
                    <div class="mt-5 space-y-3">
                        @forelse ($horariosTurma as $horario)
                            <div class="rounded-2xl border border-slate-200 p-4">
                                <p class="font-semibold text-slate-900">{{ $horario->disciplina->nome }}</p>
                                <p class="mt-1 text-sm text-slate-600">Dia {{ $horario->dia_semana }} • {{ substr($horario->horario_inicial, 0, 5) }} as {{ substr($horario->horario_final, 0, 5) }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ $horario->professor?->nome ?: 'Sem professor' }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Nenhum horario vinculado a esta turma.</p>
                        @endforelse
                    </div>
                    @can('consultar horarios da direcao')
                        <a href="{{ route('secretaria-escolar.direcao.horarios.index', ['turma_id' => $diario->turma_id]) }}" class="mt-5 inline-flex items-center rounded-2xl border border-indigo-200 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-indigo-700 transition hover:bg-indigo-50">Abrir gestao de horarios</a>
                    @endcan
                </section>

                <section class="rounded-[2rem] border border-indigo-100 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="text-xl font-outfit font-semibold text-slate-900">Pendencias docentes</h2>
                        <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-rose-800">{{ $diario->pendencias->count() }} registro(s)</span>
                    </div>
                    <div class="mt-5 space-y-3">
                        @forelse ($diario->pendencias as $pendencia)
                            <div class="rounded-2xl border border-slate-200 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="font-semibold text-slate-900">{{ $pendencia->titulo }}</p>
                                    <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] {{ $pendencia->status === 'concluida' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">{{ str_replace('_', ' ', $pendencia->status) }}</span>
                                </div>
                                <p class="mt-2 text-sm text-slate-600">{{ $pendencia->descricao }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Nenhuma pendencia docente registrada neste diario.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </section>
    </div>
</x-secretaria-escolar-layout>
