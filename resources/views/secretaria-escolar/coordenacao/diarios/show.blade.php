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
        $acompanhamentosPorMatricula = $diario->acompanhamentosPedagogicos->keyBy('matricula_id');
    @endphp

    <div class="space-y-8">
        <section class="rounded-[2rem] border border-emerald-100 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-5 xl:flex-row xl:items-start xl:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.28em] text-emerald-600">Coordenacao Pedagogica</p>
                    <h1 class="mt-3 text-3xl font-outfit font-bold text-slate-900">{{ $diario->turma->nome }} • {{ $diario->disciplina->nome }}</h1>
                    <p class="mt-2 text-sm text-slate-600">{{ $diario->escola->nome }} • {{ $diario->professor->nome }}</p>
                    <p class="mt-1 text-sm text-slate-500">{{ ucfirst($diario->periodo_tipo) }} {{ $diario->periodo_referencia }} • {{ $diario->ano_letivo }}</p>
                    @can('consultar horarios pedagogicamente')
                        <div class="mt-4">
                            <a href="{{ route('secretaria-escolar.coordenacao.horarios.index', ['turma_id' => $diario->turma_id]) }}" class="inline-flex items-center rounded-2xl border border-emerald-200 px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-emerald-700 transition hover:bg-emerald-50">
                                Horarios pedagogicos da turma
                            </a>
                        </div>
                    @endcan
                </div>
                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl bg-emerald-50 px-4 py-3"><p class="text-[11px] font-bold uppercase tracking-[0.2em] text-emerald-700">Anual</p><p class="mt-2 text-sm font-semibold text-emerald-950">{{ $metricas['planejamento_anual_validado'] ? 'Validado' : 'Pendente' }}</p></div>
                    <div class="rounded-2xl bg-cyan-50 px-4 py-3"><p class="text-[11px] font-bold uppercase tracking-[0.2em] text-cyan-700">Periodo</p><p class="mt-2 text-sm font-semibold text-cyan-950">{{ $metricas['planejamentos_periodo_validados'] }}</p></div>
                    <div class="rounded-2xl bg-indigo-50 px-4 py-3"><p class="text-[11px] font-bold uppercase tracking-[0.2em] text-indigo-700">Aulas</p><p class="mt-2 text-sm font-semibold text-indigo-950">{{ $metricas['aulas_validadas'] }}</p></div>
                    <div class="rounded-2xl bg-violet-50 px-4 py-3"><p class="text-[11px] font-bold uppercase tracking-[0.2em] text-violet-700">Avaliacoes</p><p class="mt-2 text-sm font-semibold text-violet-950">{{ $metricas['avaliacoes_lancadas'] }}</p></div>
                    <div class="rounded-2xl bg-amber-50 px-4 py-3"><p class="text-[11px] font-bold uppercase tracking-[0.2em] text-amber-700">Pendencias</p><p class="mt-2 text-sm font-semibold text-amber-950">{{ $metricas['pendencias_abertas'] }}</p></div>
                    <div class="rounded-2xl bg-rose-50 px-4 py-3"><p class="text-[11px] font-bold uppercase tracking-[0.2em] text-rose-700">Risco</p><p class="mt-2 text-sm font-semibold text-rose-950">{{ $metricas['alunos_em_risco'] }}</p></div>
                </div>
            </div>
        </section>

        <section class="grid gap-8 xl:grid-cols-2">
            <div class="rounded-[2rem] border border-emerald-100 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-xl font-outfit font-semibold text-slate-900">Planejamento anual</h2>
                    <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] {{ $statusClasses[$diario->planejamentoAnual?->validacaoPedagogica?->status ?? 'pendente'] }}">
                        {{ str_replace('_', ' ', $diario->planejamentoAnual?->validacaoPedagogica?->status ?? 'pendente') }}
                    </span>
                </div>
                @if ($diario->planejamentoAnual)
                    <div class="mt-4 space-y-2 text-sm text-slate-600">
                        <div><span class="font-semibold text-slate-900">Tema:</span> {{ $diario->planejamentoAnual->tema_gerador ?: 'Nao informado' }}</div>
                        <div><span class="font-semibold text-slate-900">Objetivos:</span> {{ $diario->planejamentoAnual->objetivos_gerais }}</div>
                    </div>
                    <form method="POST" action="{{ route('secretaria-escolar.coordenacao.diarios.planejamento-anual.validar', [$diario, $diario->planejamentoAnual]) }}" class="mt-5 grid gap-4 rounded-[1.4rem] bg-slate-50 p-4">
                        @csrf
                        <select name="status" class="rounded-2xl border-slate-200 text-sm"><option value="validado">Validado</option><option value="ajustes_solicitados">Solicitar ajustes</option></select>
                        <textarea name="parecer" rows="3" class="rounded-2xl border-slate-200 text-sm" placeholder="Parecer pedagogico"></textarea>
                        <textarea name="observacoes_internas" rows="2" class="rounded-2xl border-slate-200 text-sm" placeholder="Observacao interna"></textarea>
                        <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-white transition hover:bg-emerald-700">Salvar validacao anual</button>
                    </form>
                @else
                    <p class="mt-4 text-sm text-slate-500">O professor ainda nao registrou planejamento anual.</p>
                @endif
            </div>

            <div class="rounded-[2rem] border border-emerald-100 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-xl font-outfit font-semibold text-slate-900">Pendencias docentes</h2>
                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-amber-800">{{ $diario->pendencias->count() }} registro(s)</span>
                </div>
                <div class="mt-4 space-y-3">
                    @forelse ($diario->pendencias as $pendencia)
                        <div class="rounded-2xl border border-slate-200 p-4">
                            <div class="flex items-center justify-between gap-3">
                                <p class="font-semibold text-slate-900">{{ $pendencia->titulo }}</p>
                                <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] {{ $pendencia->status === 'concluida' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">{{ str_replace('_', ' ', $pendencia->status) }}</span>
                            </div>
                            <p class="mt-2 text-sm text-slate-600">{{ $pendencia->descricao }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Nenhuma pendencia docente registrada.</p>
                    @endforelse
                </div>
                <form method="POST" action="{{ route('secretaria-escolar.coordenacao.diarios.pendencias.store', $diario) }}" class="mt-5 grid gap-4 rounded-[1.4rem] bg-slate-50 p-4">
                    @csrf
                    <input type="text" name="titulo" class="rounded-2xl border-slate-200 text-sm" placeholder="Titulo da pendencia">
                    <textarea name="descricao" rows="3" class="rounded-2xl border-slate-200 text-sm" placeholder="Descricao"></textarea>
                    <div class="grid gap-4 md:grid-cols-2">
                        <input type="date" name="prazo" class="rounded-2xl border-slate-200 text-sm">
                        <select name="status" class="rounded-2xl border-slate-200 text-sm"><option value="aberta">Aberta</option><option value="em_andamento">Em andamento</option><option value="concluida">Concluida</option></select>
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-white transition hover:bg-slate-800">Registrar pendencia</button>
                </form>
            </div>
        </section>

        <section class="grid gap-8 xl:grid-cols-2">
            <div class="rounded-[2rem] border border-emerald-100 bg-white p-6 shadow-sm">
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
                                <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] {{ $statusClasses[$planejamento->validacaoPedagogica?->status ?? 'pendente'] }}">{{ str_replace('_', ' ', $planejamento->validacaoPedagogica?->status ?? 'pendente') }}</span>
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
                            <form method="POST" action="{{ route('secretaria-escolar.coordenacao.diarios.planejamento-periodo.validar', [$diario, $planejamento]) }}" class="mt-4 grid gap-4 lg:grid-cols-[180px_1fr_1fr_auto]">
                                @csrf
                                <select name="status" class="rounded-2xl border-slate-200 text-sm"><option value="validado">Validado</option><option value="ajustes_solicitados">Solicitar ajustes</option></select>
                                <textarea name="parecer" rows="3" class="rounded-2xl border-slate-200 text-sm" placeholder="Parecer pedagogico"></textarea>
                                <textarea name="observacoes_internas" rows="3" class="rounded-2xl border-slate-200 text-sm" placeholder="Observacao interna"></textarea>
                                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-cyan-600 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-white transition hover:bg-cyan-700">Validar</button>
                            </form>
                        </article>
                    @empty
                        <p class="text-sm text-slate-500">Nenhum planejamento por periodo registrado.</p>
                    @endforelse
                </div>
            </div>

        </section>

        <section class="rounded-[2rem] border border-emerald-100 bg-white p-6 shadow-sm">
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
                                    @can('alterar notas e conceitos pedagogicos')
                                        <form method="POST" action="{{ route('secretaria-escolar.coordenacao.diarios.avaliacoes.update', [$diario, $lancamento]) }}" class="grid gap-3 rounded-[1.4rem] bg-slate-50 p-3">
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
                            <tr>
                                <td colspan="4" class="px-3 py-6 text-sm text-slate-500">Nenhum lancamento avaliativo encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-[2rem] border border-emerald-100 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <h2 class="text-xl font-outfit font-semibold text-slate-900">Aulas registradas e frequencia</h2>
                <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-indigo-800">{{ $diario->registrosAula->count() }} aula(s)</span>
            </div>
            <div class="mt-5 space-y-5">
                @forelse ($diario->registrosAula as $registro)
                    <article class="rounded-[1.6rem] border border-slate-200 p-5">
                        <div class="flex flex-col gap-3 xl:flex-row xl:items-start xl:justify-between">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-900">{{ optional($registro->data_aula)->format('d/m/Y') }} • {{ $registro->titulo }}</p>
                                <p class="mt-2 text-sm text-slate-600">{{ $registro->conteudo_ministrado }}</p>
                                <p class="mt-2 text-xs uppercase tracking-[0.18em] text-slate-400">{{ $registro->frequencias->count() }} frequencia(s) lancadas • {{ $registro->quantidade_aulas }} aula(s)</p>
                            </div>
                            <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] {{ $statusClasses[$registro->validacaoPedagogica?->status ?? 'pendente'] }}">{{ str_replace('_', ' ', $registro->validacaoPedagogica?->status ?? 'pendente') }}</span>
                        </div>
                        <form method="POST" action="{{ route('secretaria-escolar.coordenacao.diarios.registro-aula.validar', [$diario, $registro]) }}" class="mt-4 grid gap-4 lg:grid-cols-[180px_1fr_1fr_auto]">
                            @csrf
                            <select name="status" class="rounded-2xl border-slate-200 text-sm"><option value="validado">Validado</option><option value="ajustes_solicitados">Solicitar ajustes</option></select>
                            <textarea name="parecer" rows="3" class="rounded-2xl border-slate-200 text-sm" placeholder="Parecer pedagogico"></textarea>
                            <textarea name="observacoes_internas" rows="3" class="rounded-2xl border-slate-200 text-sm" placeholder="Observacao interna"></textarea>
                            <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-indigo-600 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-white transition hover:bg-indigo-700">Validar aula</button>
                        </form>
                        @can('ajustar aulas pedagogicamente')
                            <form method="POST" action="{{ route('secretaria-escolar.coordenacao.diarios.registro-aula.update', [$diario, $registro]) }}" class="mt-4 grid gap-4 rounded-[1.4rem] bg-slate-50 p-4 xl:grid-cols-5">
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

        <section class="rounded-[2rem] border border-emerald-100 bg-white p-6 shadow-sm">
            <div class="flex items-center justify-between gap-3">
                <h2 class="text-xl font-outfit font-semibold text-slate-900">Acompanhamento de frequencia e risco</h2>
                <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-rose-800">{{ $alunosEmRisco->count() }} em risco</span>
            </div>
            <div class="mt-5 overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-left text-xs uppercase tracking-[0.18em] text-slate-500">
                            <th class="px-3 py-3">Aluno</th>
                            <th class="px-3 py-3">Frequencia</th>
                            <th class="px-3 py-3">Lancamentos</th>
                            <th class="px-3 py-3">Risco atual</th>
                            <th class="px-3 py-3">Intervencao</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($frequenciaPorMatricula as $matriculaId => $resumo)
                            @php
                                $acompanhamento = $acompanhamentosPorMatricula->get($matriculaId);
                                $situacao = $acompanhamento?->situacao_risco ?? (($resumo['percentual_presenca'] !== null && $resumo['percentual_presenca'] < 85) ? 'moderado' : 'baixo');
                            @endphp
                            <tr class="border-b border-slate-100 align-top">
                                <td class="px-3 py-4"><p class="font-semibold text-slate-900">{{ $resumo['matricula']->aluno->nome_completo }}</p><p class="mt-1 text-xs uppercase tracking-[0.18em] text-slate-400">Matricula {{ strtoupper($resumo['matricula']->tipo) }}</p></td>
                                <td class="px-3 py-4 text-slate-700">{{ $resumo['percentual_presenca'] !== null ? number_format((float) $resumo['percentual_presenca'], 2, ',', '.') . '%' : 'Sem dados' }}</td>
                                <td class="px-3 py-4 text-slate-700">{{ $resumo['total_lancado'] }} registros</td>
                                <td class="px-3 py-4"><span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] {{ in_array($situacao, ['alto', 'critico']) ? 'bg-rose-100 text-rose-800' : ($situacao === 'moderado' ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800') }}">{{ $situacao }}</span></td>
                                <td class="px-3 py-4 text-slate-700">{{ $acompanhamento?->precisa_intervencao ? 'Sim' : 'Nao' }}</td>
                            </tr>
                            <tr class="border-b border-slate-100">
                                <td colspan="5" class="px-3 pb-5">
                                    <form method="POST" action="{{ route('secretaria-escolar.coordenacao.diarios.acompanhamentos.store', [$diario, $resumo['matricula']]) }}" class="grid gap-4 rounded-[1.4rem] bg-slate-50 p-4 xl:grid-cols-5">
                                        @csrf
                                        <div><label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Rendimento</label><select name="nivel_rendimento" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm">@foreach (['adequado' => 'Adequado', 'em_atencao' => 'Em atencao', 'defasado' => 'Defasado', 'critico' => 'Critico'] as $valor => $rotulo)<option value="{{ $valor }}" @selected($acompanhamento?->nivel_rendimento === $valor)>{{ $rotulo }}</option>@endforeach</select></div>
                                        <div><label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Risco</label><select name="situacao_risco" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm">@foreach (['baixo' => 'Baixo', 'moderado' => 'Moderado', 'alto' => 'Alto', 'critico' => 'Critico'] as $valor => $rotulo)<option value="{{ $valor }}" @selected(($acompanhamento?->situacao_risco ?? $situacao) === $valor)>{{ $rotulo }}</option>@endforeach</select></div>
                                        <div class="xl:col-span-2"><label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Indicativos de aprendizagem</label><textarea name="indicativos_aprendizagem" rows="3" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm">{{ $acompanhamento?->indicativos_aprendizagem }}</textarea></div>
                                        <div><label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Intervencao</label><label class="mt-3 flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700"><input type="hidden" name="precisa_intervencao" value="0"><input type="checkbox" name="precisa_intervencao" value="1" class="rounded border-slate-300 text-emerald-600" @checked($acompanhamento?->precisa_intervencao)>Requer intervencao</label></div>
                                        <div class="xl:col-span-2"><label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Fatores de risco</label><textarea name="fatores_risco" rows="3" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm">{{ $acompanhamento?->fatores_risco }}</textarea></div>
                                        <div class="xl:col-span-2"><label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Encaminhamentos</label><textarea name="encaminhamentos" rows="3" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm">{{ $acompanhamento?->encaminhamentos }}</textarea></div>
                                        <div class="flex items-end"><button type="submit" class="inline-flex items-center rounded-2xl bg-rose-600 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-white transition hover:bg-rose-700">Salvar acompanhamento</button></div>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-3 py-6 text-sm text-slate-500">Nenhum aluno ativo encontrado nesta turma.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-secretaria-escolar-layout>
