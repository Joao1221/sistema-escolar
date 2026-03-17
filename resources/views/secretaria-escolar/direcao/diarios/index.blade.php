<x-secretaria-escolar-layout>
    @php
        $colecao = $diarios->getCollection();
        $totais = [
            'diarios' => $colecao->count(),
            'aulas' => $colecao->sum('registros_aula_count'),
            'faltas_justificadas' => $colecao->sum('faltas_justificadas_count'),
            'liberacoes' => $colecao->sum('liberacoes_ativas_count'),
        ];
    @endphp

    <div class="space-y-8">
        <section class="rounded-[2rem] border border-indigo-100 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.28em] text-indigo-600">Direcao Escolar</p>
                    <h1 class="mt-3 text-3xl font-outfit font-bold text-slate-900">Gestao administrativa e acompanhamento docente</h1>
                    <p class="mt-2 max-w-3xl text-sm text-slate-600">A direcao acompanha diarios, justifica faltas de alunos, libera prazo de lancamento, registra faltas de servidores e inicia o fechamento letivo.</p>
                </div>
                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl bg-indigo-50 px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-indigo-700">Diarios</p>
                        <p class="mt-2 text-3xl font-outfit font-bold text-indigo-950">{{ $totais['diarios'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-sky-50 px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-sky-700">Aulas</p>
                        <p class="mt-2 text-3xl font-outfit font-bold text-sky-950">{{ $totais['aulas'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-emerald-50 px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-emerald-700">Faltas justificadas</p>
                        <p class="mt-2 text-3xl font-outfit font-bold text-emerald-950">{{ $totais['faltas_justificadas'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-amber-50 px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-amber-700">Prazos liberados</p>
                        <p class="mt-2 text-3xl font-outfit font-bold text-amber-950">{{ $totais['liberacoes'] }}</p>
                    </div>
                </div>
            </div>
            @can('consultar horarios da direcao')
                <div class="mt-5">
                    <a href="{{ route('secretaria-escolar.direcao.horarios.index') }}" class="inline-flex items-center rounded-2xl border border-indigo-200 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-indigo-700 transition hover:bg-indigo-50">
                        Gerir horarios da escola
                    </a>
                </div>
            @endcan
        </section>

        <section class="rounded-[2rem] border border-indigo-100 bg-white p-6 shadow-sm">
            <form method="GET" action="{{ route('secretaria-escolar.direcao.diarios.index') }}" class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                <div>
                    <label for="escola_id" class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Escola</label>
                    <select id="escola_id" name="escola_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm">
                        <option value="">Todas</option>
                        @foreach ($filtros['escolas'] as $escola)
                            <option value="{{ $escola->id }}" @selected(request('escola_id') == $escola->id)>{{ $escola->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="turma_id" class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Turma</label>
                    <select id="turma_id" name="turma_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm">
                        <option value="">Todas</option>
                        @foreach ($filtros['turmas'] as $turma)
                            <option value="{{ $turma->id }}" @selected(request('turma_id') == $turma->id)>{{ $turma->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="professor_id" class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Professor</label>
                    <select id="professor_id" name="professor_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm">
                        <option value="">Todos</option>
                        @foreach ($filtros['professores'] as $professor)
                            <option value="{{ $professor->id }}" @selected(request('professor_id') == $professor->id)>{{ $professor->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="ano_letivo" class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Ano letivo</label>
                    <input id="ano_letivo" name="ano_letivo" type="number" value="{{ request('ano_letivo') }}" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm" placeholder="2026">
                </div>
                <div>
                    <label for="situacao_validacao" class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Validacao</label>
                    <select id="situacao_validacao" name="situacao_validacao" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm">
                        <option value="">Todas</option>
                        <option value="validado" @selected(request('situacao_validacao') === 'validado')>Validado</option>
                        <option value="ajustes_solicitados" @selected(request('situacao_validacao') === 'ajustes_solicitados')>Solicitar ajustes</option>
                    </select>
                </div>
                <div class="md:col-span-2 xl:col-span-5 flex flex-wrap gap-3">
                    <button type="submit" class="inline-flex items-center rounded-2xl bg-indigo-600 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-white transition hover:bg-indigo-700">
                        Filtrar diarios
                    </button>
                    <a href="{{ route('secretaria-escolar.direcao.diarios.index') }}" class="inline-flex items-center rounded-2xl border border-slate-200 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-slate-600 transition hover:bg-slate-50">
                        Limpar filtros
                    </a>
                </div>
            </form>
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
            <div class="space-y-5">
                @forelse ($diarios as $diario)
                    @php
                        $statusAnual = $diario->planejamentoAnual?->validacaoDirecao?->status ?? 'pendente';
                        $badgeClasses = [
                            'validado' => 'bg-emerald-100 text-emerald-800',
                            'ajustes_solicitados' => 'bg-amber-100 text-amber-800',
                            'pendente' => 'bg-slate-100 text-slate-700',
                        ];
                    @endphp
                    <article class="rounded-[2rem] border border-indigo-100 bg-white p-6 shadow-sm">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.24em] text-indigo-600">{{ $diario->escola->nome }}</p>
                                <h2 class="mt-2 text-2xl font-outfit font-bold text-slate-900">{{ $diario->turma->nome }}</h2>
                                <p class="mt-2 text-sm text-slate-600">{{ $diario->disciplina->nome }} • {{ $diario->professor->nome }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ ucfirst($diario->periodo_tipo) }} {{ $diario->periodo_referencia }} • {{ $diario->ano_letivo }}</p>
                            </div>
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] {{ $badgeClasses[$statusAnual] ?? $badgeClasses['pendente'] }}">
                                Planejamento: {{ str_replace('_', ' ', $statusAnual) }}
                            </span>
                        </div>

                        <div class="mt-5 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                            <div class="rounded-2xl bg-slate-50 px-4 py-3">
                                <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-500">Aulas</p>
                                <p class="mt-2 text-lg font-semibold text-slate-900">{{ $diario->registros_aula_count }}</p>
                            </div>
                            <div class="rounded-2xl bg-emerald-50 px-4 py-3">
                                <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-emerald-700">Faltas justificadas</p>
                                <p class="mt-2 text-lg font-semibold text-emerald-900">{{ $diario->faltas_justificadas_count }}</p>
                            </div>
                            <div class="rounded-2xl bg-amber-50 px-4 py-3">
                                <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-amber-700">Liberacoes</p>
                                <p class="mt-2 text-lg font-semibold text-amber-900">{{ $diario->liberacoes_ativas_count }}</p>
                            </div>
                            <div class="rounded-2xl bg-rose-50 px-4 py-3">
                                <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-rose-700">Pendencias</p>
                                <p class="mt-2 text-lg font-semibold text-rose-900">{{ $diario->pendencias_count }}</p>
                            </div>
                        </div>

                        <div class="mt-5 flex flex-wrap gap-3">
                            <a href="{{ route('secretaria-escolar.direcao.diarios.show', $diario) }}" class="inline-flex items-center rounded-2xl bg-slate-900 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-white transition hover:bg-slate-800">
                                Abrir area da direcao
                            </a>
                            <a href="{{ route('secretaria-escolar.diarios.show', $diario) }}" class="inline-flex items-center rounded-2xl border border-slate-200 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-slate-600 transition hover:bg-slate-50">
                                Consulta geral
                            </a>
                        </div>
                    </article>
                @empty
                    <div class="rounded-[2rem] border border-dashed border-slate-300 bg-white px-6 py-14 text-center text-slate-500">
                        Nenhum diario encontrado para os filtros informados.
                    </div>
                @endforelse

                @if ($diarios->hasPages())
                    <div class="rounded-[2rem] border border-indigo-100 bg-white px-6 py-4 shadow-sm">
                        {{ $diarios->links() }}
                    </div>
                @endif
            </div>

            <div class="space-y-5">
                <section class="rounded-[2rem] border border-indigo-100 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="text-xl font-outfit font-semibold text-slate-900">Faltas de funcionarios</h2>
                        <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-rose-800">{{ $faltasFuncionarios->count() }} recentes</span>
                    </div>

                    <form method="POST" action="{{ route('secretaria-escolar.direcao.faltas-funcionarios.store') }}" class="mt-5 space-y-4 rounded-[1.6rem] bg-slate-50 p-4">
                        @csrf
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Escola</label>
                                <select name="escola_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm">
                                    @foreach ($filtros['escolas'] as $escola)
                                        <option value="{{ $escola->id }}">{{ $escola->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Funcionario</label>
                                <select name="funcionario_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm">
                                    @foreach ($filtros['funcionarios'] as $funcionario)
                                        <option value="{{ $funcionario->id }}">{{ $funcionario->nome }} ({{ $funcionario->cargo }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Data</label>
                                <input type="date" name="data_falta" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm" value="{{ now()->toDateString() }}">
                            </div>
                            <div>
                                <label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Turno</label>
                                <select name="turno" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm">
                                    <option value="matutino">Matutino</option>
                                    <option value="vespertino">Vespertino</option>
                                    <option value="noturno">Noturno</option>
                                    <option value="integral">Integral</option>
                                </select>
                            </div>
                        </div>
                        <div class="grid gap-4 md:grid-cols-[180px_1fr]">
                            <div>
                                <label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Tipo</label>
                                <select name="tipo_falta" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm">
                                    <option value="falta">Falta</option>
                                    <option value="atraso">Atraso</option>
                                    <option value="saida_antecipada">Saida antecipada</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Motivo</label>
                                <input type="text" name="motivo" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm" placeholder="Motivo resumido do registro">
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Observacoes</label>
                            <textarea name="observacoes" rows="3" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm" placeholder="Complemento do registro funcional"></textarea>
                        </div>
                        <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700">
                            <input type="hidden" name="justificada" value="0">
                            <input type="checkbox" name="justificada" value="1" class="rounded border-slate-300 text-indigo-600">
                            Registro justificado
                        </label>
                        <button type="submit" class="inline-flex items-center rounded-2xl bg-indigo-600 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-white transition hover:bg-indigo-700">
                            Registrar falta funcional
                        </button>
                    </form>

                    <div class="mt-5 space-y-3">
                        @forelse ($faltasFuncionarios as $falta)
                            <div class="rounded-2xl border border-slate-200 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="font-semibold text-slate-900">{{ $falta->funcionario->nome }}</p>
                                    <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] {{ $falta->justificada ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $falta->justificada ? 'Justificada' : 'Nao justificada' }}
                                    </span>
                                </div>
                                <p class="mt-2 text-sm text-slate-600">{{ $falta->escola->nome }} • {{ optional($falta->data_falta)->format('d/m/Y') }} • {{ str_replace('_', ' ', $falta->tipo_falta) }}</p>
                                <p class="mt-1 text-sm text-slate-500">{{ $falta->motivo }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Nenhum registro recente de falta funcional.</p>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-[2rem] border border-indigo-100 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="text-xl font-outfit font-semibold text-slate-900">Fechamento letivo</h2>
                        <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-indigo-800">{{ $fechamentosLetivos->count() }} registro(s)</span>
                    </div>

                    <form method="POST" action="{{ route('secretaria-escolar.direcao.fechamento-letivo.store') }}" class="mt-5 space-y-4 rounded-[1.6rem] bg-slate-50 p-4">
                        @csrf
                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Escola</label>
                                <select name="escola_id" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm">
                                    @foreach ($filtros['escolas'] as $escola)
                                        <option value="{{ $escola->id }}">{{ $escola->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Ano letivo</label>
                                <input type="number" name="ano_letivo" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm" value="{{ request('ano_letivo', now()->year) }}">
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Status</label>
                            <select name="status" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm">
                                <option value="iniciado">Iniciar fluxo</option>
                                <option value="concluido">Concluir fluxo inicial</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Resumo</label>
                            <textarea name="resumo" rows="3" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm" placeholder="Resumo administrativo e pedagogico do fechamento"></textarea>
                        </div>
                        <div>
                            <label class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Observacoes</label>
                            <textarea name="observacoes" rows="3" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm" placeholder="Pendencias, ressalvas e encaminhamentos da direcao"></textarea>
                        </div>
                        <button type="submit" class="inline-flex items-center rounded-2xl bg-slate-900 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-white transition hover:bg-slate-800">
                            Salvar fechamento
                        </button>
                    </form>

                    <div class="mt-5 space-y-3">
                        @forelse ($fechamentosLetivos as $fechamento)
                            <div class="rounded-2xl border border-slate-200 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <p class="font-semibold text-slate-900">{{ $fechamento->escola->nome }} • {{ $fechamento->ano_letivo }}</p>
                                    <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] {{ $fechamento->status === 'concluido' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                        {{ $fechamento->status }}
                                    </span>
                                </div>
                                <p class="mt-2 text-sm text-slate-600">{{ $fechamento->resumo ?: 'Sem resumo informado.' }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Nenhum fechamento letivo registrado ainda.</p>
                        @endforelse
                    </div>
                </section>

                <section class="rounded-[2rem] border border-indigo-100 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="text-xl font-outfit font-semibold text-slate-900">Horarios integrados</h2>
                        <span class="rounded-full bg-sky-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-sky-800">{{ $horarios->count() }} exibido(s)</span>
                    </div>
                    <div class="mt-5 space-y-3">
                        @forelse ($horarios as $horario)
                            <div class="rounded-2xl border border-slate-200 p-4">
                                <p class="font-semibold text-slate-900">{{ $horario->turma->nome }} • {{ $horario->disciplina->nome }}</p>
                                <p class="mt-1 text-sm text-slate-600">{{ $horario->professor->nome }} • dia {{ $horario->dia_semana }} • {{ substr($horario->horario_inicial, 0, 5) }} às {{ substr($horario->horario_final, 0, 5) }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Nenhum horario encontrado para o contexto atual.</p>
                        @endforelse
                    </div>
                    @can('ver horarios')
                        <a href="{{ route('secretaria-escolar.horarios.index') }}" class="mt-5 inline-flex items-center rounded-2xl border border-slate-200 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-slate-600 transition hover:bg-slate-50">
                            Abrir modulo de horarios
                        </a>
                    @endcan
                </section>
            </div>
        </section>
    </div>
</x-secretaria-escolar-layout>
