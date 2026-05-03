<x-secretaria-escolar-layout>
    @php
        $colecao = $diarios->getCollection();
        $totais = [
            'diarios' => $colecao->count(),
            'aulas' => $colecao->sum('registros_aula_count'),
            'pendencias' => $colecao->sum('pendencias_count'),
            'alunos_risco' => $colecao->sum('alunos_em_risco_count'),
        ];
    @endphp

    <div class="space-y-8">
        <section class="rounded-[2rem] border border-emerald-100 bg-white p-6 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.28em] text-emerald-600">Coordenacao Pedagogica</p>
                    <h1 class="mt-3 text-3xl font-outfit font-bold text-slate-900">Acompanhamento dos diarios docentes</h1>
                    <p class="mt-2 max-w-3xl text-sm text-slate-600">Visualize planejamentos, aulas, frequencia, pendencias e sinais de risco academico sem depender ainda de um portal visual separado.</p>
                </div>
                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl bg-emerald-50 px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-emerald-700">Diarios</p>
                        <p class="mt-2 text-3xl font-outfit font-bold text-emerald-950">{{ $totais['diarios'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-sky-50 px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-sky-700">Aulas</p>
                        <p class="mt-2 text-3xl font-outfit font-bold text-sky-950">{{ $totais['aulas'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-amber-50 px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-amber-700">Pendencias</p>
                        <p class="mt-2 text-3xl font-outfit font-bold text-amber-950">{{ $totais['pendencias'] }}</p>
                    </div>
                    <div class="rounded-2xl bg-rose-50 px-4 py-3">
                        <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-rose-700">Alunos em risco</p>
                        <p class="mt-2 text-3xl font-outfit font-bold text-rose-950">{{ $totais['alunos_risco'] }}</p>
                    </div>
                </div>
            </div>
            @can('consultar horarios pedagogicamente')
                <div class="mt-5">
                    <a href="{{ route('secretaria-escolar.coordenacao.horarios.index') }}" class="inline-flex items-center rounded-2xl border border-emerald-200 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-emerald-700 transition hover:bg-emerald-50">
                        Gerir horarios pedagogicos
                    </a>
                </div>
            @endcan
        </section>

        <section class="rounded-[2rem] border border-emerald-100 bg-white p-6 shadow-sm">
            <form method="GET" action="{{ route('secretaria-escolar.coordenacao.diarios.index') }}" class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
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
                    <label for="situacao_validacao" class="text-xs font-bold uppercase tracking-[0.18em] text-slate-500">Situacao</label>
                    <select id="situacao_validacao" name="situacao_validacao" class="mt-2 block w-full rounded-2xl border-slate-200 text-sm">
                        <option value="">Todas</option>
                        <option value="validado" @selected(request('situacao_validacao') === 'validado')>Validado</option>
                        <option value="ajustes_solicitados" @selected(request('situacao_validacao') === 'ajustes_solicitados')>Ajustes solicitados</option>
                    </select>
                </div>
                <div class="md:col-span-2 xl:col-span-5 flex flex-wrap items-center gap-3">
                    <button type="submit" class="inline-flex items-center rounded-2xl bg-emerald-600 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-white transition hover:bg-emerald-700">
                        Filtrar acompanhamento
                    </button>
                    <a href="{{ route('secretaria-escolar.coordenacao.diarios.index') }}" class="inline-flex items-center rounded-2xl border border-slate-200 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-slate-600 transition hover:bg-slate-50">
                        Limpar filtros
                    </a>
                </div>
            </form>
        </section>

        <section class="grid gap-5 xl:grid-cols-2">
            @forelse ($diarios as $diario)
                @php
                    $statusAnual = $diario->planejamentoAnual?->validacaoPedagogica?->status ?? 'pendente';
                    $aulasValidadas = $diario->registrosAula->filter(fn ($item) => $item->validacaoPedagogica?->status === 'validado')->count();
                    $badgeClasses = [
                        'validado' => 'bg-emerald-100 text-emerald-800',
                        'ajustes_solicitados' => 'bg-amber-100 text-amber-800',
                        'pendente' => 'bg-slate-100 text-slate-700',
                    ];
                @endphp
                <article class="rounded-[2rem] border border-emerald-100 bg-white p-6 shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-[0.24em] text-emerald-600">{{ $diario->escola->nome }}</p>
                            <h2 class="mt-2 text-2xl font-outfit font-bold text-slate-900">{{ $diario->turma->nome }}</h2>
                            <p class="mt-2 text-sm text-slate-600">{{ $diario->disciplina->nome }} • {{ $diario->professor->nome }}</p>
                            <p class="mt-1 text-sm text-slate-500">{{ ucfirst($diario->periodo_tipo) }} {{ $diario->periodo_referencia }} • {{ $diario->ano_letivo }}</p>
                        </div>
                        <span class="inline-flex rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] {{ $badgeClasses[$statusAnual] ?? $badgeClasses['pendente'] }}">
                            Planejamento anual: {{ str_replace('_', ' ', $statusAnual) }}
                        </span>
                    </div>

                    <div class="mt-5 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-2xl bg-slate-50 px-4 py-3">
                            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-500">Aulas validadas</p>
                            <p class="mt-2 text-lg font-semibold text-slate-900">{{ $aulasValidadas }}/{{ $diario->registros_aula_count }}</p>
                        </div>
                        <div class="rounded-2xl bg-rose-50 px-4 py-3">
                            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-rose-600">Risco</p>
                            <p class="mt-2 text-lg font-semibold text-rose-900">{{ $diario->alunos_em_risco_count }} aluno(s)</p>
                        </div>
                        <div class="rounded-2xl bg-amber-50 px-4 py-3">
                            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-amber-600">Pendencias</p>
                            <p class="mt-2 text-lg font-semibold text-amber-900">{{ $diario->pendencias_count }}</p>
                        </div>
                    </div>

                    <div class="mt-5 flex flex-wrap items-center gap-3">
                        <a href="{{ route('secretaria-escolar.coordenacao.diarios.show', $diario) }}" class="inline-flex items-center rounded-2xl bg-slate-900 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-white transition hover:bg-slate-800">
                            Analisar diario
                        </a>
                        <a href="{{ route('secretaria-escolar.diarios.show', $diario) }}" class="inline-flex items-center rounded-2xl border border-slate-200 px-5 py-3 text-xs font-bold uppercase tracking-[0.18em] text-slate-600 transition hover:bg-slate-50">
                            Consulta pedagogica
                        </a>
                    </div>
                </article>
            @empty
                <div class="xl:col-span-2 rounded-[2rem] border border-dashed border-slate-300 bg-white px-6 py-14 text-center text-slate-500">
                    Nenhum diario encontrado para os filtros informados.
                </div>
            @endforelse
        </section>

        @if ($diarios->hasPages())
            <section class="rounded-[2rem] border border-emerald-100 bg-white px-6 py-4 shadow-sm">
                {{ $diarios->links() }}
            </section>
        @endif
    </div>
</x-secretaria-escolar-layout>
