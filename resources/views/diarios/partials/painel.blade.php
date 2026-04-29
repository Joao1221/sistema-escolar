@php
    $modoConsulta = $modoConsulta ?? false;
    $horariosRelacionados = $horariosRelacionados ?? collect();
    $tipoAvaliacaoDiario = $tipoAvaliacaoDiario ?? strtolower((string) data_get($diario, 'turma.modalidade.tipo_avaliacao', 'nota'));
    $usaNota = str_contains($tipoAvaliacaoDiario, 'nota');
    $rotulosPlanejamento = ['semanal' => 'Semanal', 'quinzenal' => 'Quinzenal', 'mensal' => 'Mensal', 'semestral' => 'Semestral'];
    $diasSemana = [1 => 'Domingo', 2 => 'Segunda-feira', 3 => 'Terca-feira', 4 => 'Quarta-feira', 5 => 'Quinta-feira', 6 => 'Sexta-feira', 7 => 'Sabado'];
@endphp

<div class="space-y-8">
    @unless ($modoConsulta)
        <section class="rounded-[1.8rem] border border-[#e2d3bf] bg-white p-5 shadow-sm">
            <div class="flex flex-wrap gap-3">
                @foreach ([
                    'planejamento-anual' => 'Planejamento anual',
                    'planejamentos-periodo' => 'Planejamentos docentes',
                    'avaliacoes' => 'Notas e conceitos',
                    'registrar-aula' => 'Registrar aula',
                    'frequencia' => 'Registrar frequencia',
                    'observacoes' => 'Observacoes',
                    'ocorrencias' => 'Ocorrencias',
                    'pendencias' => 'Pendencias',
                ] as $ancora => $rotulo)
                    <a href="#{{ $ancora }}" class="rounded-full bg-[#f8efe1] px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-[#7b4b2a] transition hover:bg-[#f1dfc6]">{{ $rotulo }}</a>
                @endforeach
            </div>
        </section>
    @endunless

    <section class="rounded-[2rem] border border-[#e2d3bf] bg-white p-6 shadow-sm lg:p-8">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-amber-700">{{ $modoConsulta ? 'Consulta pedagogica' : 'Diario em uso' }}</p>
                <h2 class="text-3xl font-outfit font-bold text-[#24120d]">{{ $diario->turma->nome }} • {{ $diario->disciplina->nome }}</h2>
                <p class="mt-2 text-[#6f5648]">{{ $diario->escola->nome }} • Professor: {{ $diario->professor->nome }}</p>
                <p class="mt-1 text-sm text-[#8b6f5a]">{{ data_get($diario, 'turma.modalidade.nome', 'Modalidade nao informada') }} • Avaliacao por {{ $usaNota ? 'nota' : 'conceito' }}</p>
            </div>
            <div class="flex flex-wrap gap-3 text-sm">
                <span class="rounded-full bg-amber-100 px-4 py-2 font-semibold text-amber-800">{{ ucfirst($diario->periodo_tipo) }} {{ $diario->periodo_referencia }}</span>
                <span class="rounded-full bg-stone-100 px-4 py-2 font-semibold text-stone-700">{{ $diario->ano_letivo }}</span>
                <span class="rounded-full bg-emerald-100 px-4 py-2 font-semibold text-emerald-800">{{ str_replace('_', ' ', ucfirst($diario->situacao)) }}</span>
            </div>
        </div>
        <div class="mt-6 grid gap-4 md:grid-cols-5">
            <div class="rounded-2xl bg-[#fbf6ef] p-4"><p class="text-xs uppercase tracking-widest text-[#9a7d67]">Aulas</p><p class="mt-2 text-3xl font-outfit font-bold text-[#24120d]">{{ $diario->registrosAula->count() }}</p></div>
            <div class="rounded-2xl bg-[#fbf6ef] p-4"><p class="text-xs uppercase tracking-widest text-[#9a7d67]">Planejamentos</p><p class="mt-2 text-3xl font-outfit font-bold text-[#24120d]">{{ $diario->planejamentosPeriodo->count() + $diario->planejamentosSemanais->count() + ($diario->planejamentoAnual ? 1 : 0) }}</p></div>
            <div class="rounded-2xl bg-[#fbf6ef] p-4"><p class="text-xs uppercase tracking-widest text-[#9a7d67]">Avaliacoes</p><p class="mt-2 text-3xl font-outfit font-bold text-[#24120d]">{{ $diario->lancamentosAvaliativos->count() }}</p></div>
            <div class="rounded-2xl bg-[#fbf6ef] p-4"><p class="text-xs uppercase tracking-widest text-[#9a7d67]">Observacoes</p><p class="mt-2 text-3xl font-outfit font-bold text-[#24120d]">{{ $diario->observacoesAluno->count() }}</p></div>
            <div class="rounded-2xl bg-[#fbf6ef] p-4"><p class="text-xs uppercase tracking-widest text-[#9a7d67]">Pendencias</p><p class="mt-2 text-3xl font-outfit font-bold text-[#24120d]">{{ $diario->pendencias->count() }}</p></div>
        </div>
    </section>

    <section class="grid gap-8 xl:grid-cols-2">
        <div id="planejamento-anual" class="rounded-[2rem] border border-[#e2d3bf] bg-white p-6 shadow-sm">
            <h3 class="text-xl font-outfit font-semibold text-[#24120d]">Planejamento anual</h3>
            @if ($diario->planejamentoAnual)
                <div class="mt-4 space-y-2 text-sm text-[#6f5648]">
                    <div><span class="font-semibold text-[#24120d]">Tema:</span> {{ $diario->planejamentoAnual->tema_gerador ?: 'Nao informado' }}</div>
                    <div><span class="font-semibold text-[#24120d]">Objetivos:</span> {{ $diario->planejamentoAnual->objetivos_gerais }}</div>
                    @if ($diario->planejamentoAnual->conteudos)<div><span class="font-semibold text-[#24120d]">Conteudos:</span> {{ $diario->planejamentoAnual->conteudos }}</div>@endif
                </div>
            @else
                <p class="mt-4 text-sm text-[#7d6556]">Nenhum planejamento anual registrado.</p>
            @endif

            @unless ($modoConsulta)
                <form method="POST" action="{{ route('professor.diario.planejamento-anual.store', $diario) }}" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label for="pa_tema_gerador" class="block text-sm font-medium text-[#4b3728] mb-1">Tema gerador</label>
                        <x-text-input id="pa_tema_gerador" name="tema_gerador" type="text" class="block w-full" :value="old('tema_gerador', $diario->planejamentoAnual?->tema_gerador)" placeholder="Tema gerador" />
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="pa_vigencia_inicio" class="block text-sm font-medium text-[#4b3728] mb-1">Início da vigência</label>
                            <x-text-input id="pa_vigencia_inicio" name="periodo_vigencia_inicio" type="date" class="block w-full" :value="old('periodo_vigencia_inicio', optional($diario->planejamentoAnual?->periodo_vigencia_inicio)->format('Y-m-d'))" />
                        </div>
                        <div>
                            <label for="pa_vigencia_fim" class="block text-sm font-medium text-[#4b3728] mb-1">Fim da vigência</label>
                            <x-text-input id="pa_vigencia_fim" name="periodo_vigencia_fim" type="date" class="block w-full" :value="old('periodo_vigencia_fim', optional($diario->planejamentoAnual?->periodo_vigencia_fim)->format('Y-m-d'))" />
                        </div>
                    </div>
                    <div>
                        <label for="pa_objetivos" class="block text-sm font-medium text-[#4b3728] mb-1">Objetivos de aprendizagem</label>
                        <textarea id="pa_objetivos" name="objetivos_gerais" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm">{{ old('objetivos_gerais', $diario->planejamentoAnual?->objetivos_gerais) }}</textarea>
                    </div>
                    <div>
                        <label for="pa_competencias" class="block text-sm font-medium text-[#4b3728] mb-1">Habilidades e competências</label>
                        <textarea id="pa_competencias" name="competencias_habilidades" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm">{{ old('competencias_habilidades', $diario->planejamentoAnual?->competencias_habilidades) }}</textarea>
                    </div>
                    <div>
                        <label for="pa_conteudos" class="block text-sm font-medium text-[#4b3728] mb-1">Conteúdos</label>
                        <textarea id="pa_conteudos" name="conteudos" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm">{{ old('conteudos', $diario->planejamentoAnual?->conteudos) }}</textarea>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="pa_metodologia" class="block text-sm font-medium text-[#4b3728] mb-1">Metodologia</label>
                            <textarea id="pa_metodologia" name="metodologia" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm">{{ old('metodologia', $diario->planejamentoAnual?->metodologia) }}</textarea>
                        </div>
                        <div>
                            <label for="pa_instrumentos" class="block text-sm font-medium text-[#4b3728] mb-1">Instrumentos de avaliação</label>
                            <textarea id="pa_instrumentos" name="instrumentos_avaliacao" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm">{{ old('instrumentos_avaliacao', $diario->planejamentoAnual?->instrumentos_avaliacao) }}</textarea>
                        </div>
                    </div>
                    <div>
                        <label for="pa_adequacoes" class="block text-sm font-medium text-[#4b3728] mb-1">Adequações para inclusão</label>
                        <textarea id="pa_adequacoes" name="adequacoes_inclusao" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm">{{ old('adequacoes_inclusao', $diario->planejamentoAnual?->adequacoes_inclusao) }}</textarea>
                    </div>
                    <button type="submit" class="inline-flex rounded-xl bg-[#2b1710] px-4 py-2 text-sm font-bold uppercase tracking-widest text-white transition hover:bg-[#8b4d28]">Salvar planejamento anual</button>
                </form>
            @endunless
        </div>

        <div id="planejamentos-periodo" class="rounded-[2rem] border border-[#e2d3bf] bg-white p-6 shadow-sm" x-data="{ tipoPlanejamento: '{{ old('tipo_planejamento', 'semanal') }}' }">
            <h3 class="text-xl font-outfit font-semibold text-[#24120d]">Planejamentos docentes</h3>
            <div class="mt-4 space-y-3">
                @forelse ($diario->planejamentosPeriodo as $planejamento)
                    <div class="rounded-2xl border border-[#ead9c3] bg-[#fbf6ef] p-4">
                        <p class="font-semibold text-[#24120d]">{{ $rotulosPlanejamento[$planejamento->tipo_planejamento] ?? ucfirst($planejamento->tipo_planejamento) }} • {{ optional($planejamento->data_inicio)->format('d/m/Y') }} a {{ optional($planejamento->data_fim)->format('d/m/Y') }}</p>
                        <p class="mt-2 text-sm text-[#6f5648]">{{ $planejamento->objetivos_aprendizagem }}</p>
                    </div>
                @empty
                    <p class="text-sm text-[#7d6556]">Nenhum planejamento dinamico registrado.</p>
                @endforelse
                @foreach ($diario->planejamentosSemanais as $planejamentoLegado)
                    <div class="rounded-2xl border border-dashed border-[#ead9c3] p-4">
                        <p class="font-semibold text-[#24120d]">Semanal legado • {{ optional($planejamentoLegado->data_inicio_semana)->format('d/m/Y') }} a {{ optional($planejamentoLegado->data_fim_semana)->format('d/m/Y') }}</p>
                        <p class="mt-2 text-sm text-[#6f5648]">{{ $planejamentoLegado->objetivos_semana }}</p>
                    </div>
                @endforeach
            </div>

            @unless ($modoConsulta)
                <form method="POST" action="{{ route('professor.diario.planejamento-periodo.store', $diario) }}" class="mt-6 space-y-4 rounded-[1.6rem] bg-[#fcf7f0] p-5">
                    @csrf
                    <div>
                        <label for="pp_tipo" class="block text-sm font-medium text-[#4b3728] mb-1">Tipo de planejamento</label>
                        <select id="pp_tipo" name="tipo_planejamento" x-model="tipoPlanejamento" class="block w-full rounded-xl border-stone-300">
                            @foreach ($rotulosPlanejamento as $valor => $rotulo)
                                <option value="{{ $valor }}">{{ $rotulo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid gap-4 md:grid-cols-3">
                        <div>
                            <label for="pp_referencia" class="block text-sm font-medium text-[#4b3728] mb-1">Referência do período</label>
                            <x-text-input id="pp_referencia" name="periodo_referencia" type="text" class="block w-full" :value="old('periodo_referencia')" x-bind:placeholder="tipoPlanejamento === 'quinzenal' ? 'Ex.: 1a quinzena de abril' : (tipoPlanejamento === 'mensal' ? 'Ex.: Abril/2026' : (tipoPlanejamento === 'semestral' ? 'Ex.: 1o semestre/2026' : 'Ex.: Semana 1'))" />
                        </div>
                        <div>
                            <label for="pp_data_inicio" class="block text-sm font-medium text-[#4b3728] mb-1">Data de início</label>
                            <x-text-input id="pp_data_inicio" name="data_inicio" type="date" class="block w-full" :value="old('data_inicio')" />
                        </div>
                        <div>
                            <label for="pp_data_fim" class="block text-sm font-medium text-[#4b3728] mb-1">Data de fim</label>
                            <x-text-input id="pp_data_fim" name="data_fim" type="date" class="block w-full" :value="old('data_fim')" />
                        </div>
                    </div>
                    <div>
                        <label for="pp_objetivos" class="block text-sm font-medium text-[#4b3728] mb-1">Objetivos de aprendizagem</label>
                        <textarea id="pp_objetivos" name="objetivos_aprendizagem" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm">{{ old('objetivos_aprendizagem') }}</textarea>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="pp_habilidades" class="block text-sm font-medium text-[#4b3728] mb-1">Habilidades e competências</label>
                            <textarea id="pp_habilidades" name="habilidades_competencias" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm">{{ old('habilidades_competencias') }}</textarea>
                        </div>
                        <div>
                            <label for="pp_conteudos" class="block text-sm font-medium text-[#4b3728] mb-1">Conteúdos</label>
                            <textarea id="pp_conteudos" name="conteudos" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm">{{ old('conteudos') }}</textarea>
                        </div>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="pp_metodologia" class="block text-sm font-medium text-[#4b3728] mb-1">Metodologia</label>
                            <textarea id="pp_metodologia" name="metodologia" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm">{{ old('metodologia') }}</textarea>
                        </div>
                        <div>
                            <label for="pp_instrumentos" class="block text-sm font-medium text-[#4b3728] mb-1">Instrumentos de avaliação</label>
                            <textarea id="pp_instrumentos" name="instrumentos_avaliacao" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm">{{ old('instrumentos_avaliacao') }}</textarea>
                        </div>
                    </div>
                    <div>
                        <label for="pp_adequacoes" class="block text-sm font-medium text-[#4b3728] mb-1">Adequações para inclusão</label>
                        <textarea id="pp_adequacoes" name="adequacoes_inclusao" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm">{{ old('adequacoes_inclusao') }}</textarea>
                    </div>
                    <button type="submit" class="inline-flex rounded-xl bg-[#2b1710] px-4 py-2 text-sm font-bold uppercase tracking-widest text-white transition hover:bg-[#8b4d28]">Registrar planejamento do periodo</button>
                </form>
            @endunless
        </div>
    </section>

    <section id="avaliacoes" class="rounded-[2rem] border border-[#e2d3bf] bg-white p-6 shadow-sm">
        <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h3 class="text-xl font-outfit font-semibold text-[#24120d]">Notas e conceitos</h3>
                <p class="mt-2 text-sm text-[#7d6556]">Este diario usa {{ $usaNota ? 'notas numericas' : 'conceitos' }} conforme a modalidade da turma.</p>
            </div>
            <span class="rounded-full bg-[#f8efe1] px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] text-[#7b4b2a]">{{ $usaNota ? 'Fluxo por nota' : 'Fluxo por conceito' }}</span>
        </div>
        <div class="mt-5 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead><tr class="border-b border-[#ead9c3] text-left text-xs uppercase tracking-[0.18em] text-[#8b6f5a]"><th class="px-3 py-3">Aluno</th><th class="px-3 py-3">Referencia</th><th class="px-3 py-3">{{ $usaNota ? 'Nota' : 'Conceito' }}</th><th class="px-3 py-3">Observacoes</th></tr></thead>
                <tbody>
                    @forelse ($diario->lancamentosAvaliativos as $lancamento)
                        <tr class="border-b border-[#f1e4d3]">
                            <td class="px-3 py-4 font-semibold text-[#24120d]">{{ $lancamento->matricula->aluno->nome_completo }}</td>
                            <td class="px-3 py-4 text-[#6f5648]">{{ $lancamento->avaliacao_referencia }}</td>
                            <td class="px-3 py-4 text-[#6f5648]">{{ $usaNota ? number_format((float) $lancamento->valor_numerico, 2, ',', '.') : ($lancamento->conceito ?: '-') }}</td>
                            <td class="px-3 py-4 text-[#6f5648]">{{ $lancamento->observacoes ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-3 py-6 text-sm text-[#7d6556]">Nenhum lancamento avaliativo registrado.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @unless ($modoConsulta)
            <form method="POST" action="{{ route('professor.diario.avaliacoes.store', $diario) }}" class="mt-6 space-y-4 rounded-[1.6rem] bg-[#fcf7f0] p-5">
                @csrf
                <div class="grid gap-4 xl:grid-cols-4">
                    <div>
                        <label for="av_matricula" class="block text-sm font-medium text-[#4b3728] mb-1">Aluno</label>
                        <select id="av_matricula" name="matricula_id" class="block w-full rounded-xl border-stone-300">
                            @foreach ($matriculasAtivas as $matricula)
                                <option value="{{ $matricula->id }}">{{ $matricula->aluno->nome_completo }} - {{ strtoupper($matricula->tipo) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="av_referencia" class="block text-sm font-medium text-[#4b3728] mb-1">Referência da avaliação</label>
                        <x-text-input id="av_referencia" name="avaliacao_referencia" type="text" class="block w-full" :value="old('avaliacao_referencia', $diario->periodo_tipo . ' ' . $diario->periodo_referencia)" />
                    </div>
                    <div>
                        @if ($usaNota)
                            <label for="av_valor" class="block text-sm font-medium text-[#4b3728] mb-1">Nota (0–100)</label>
                            <x-text-input id="av_valor" name="valor_numerico" type="number" step="0.01" min="0" max="100" class="block w-full" :value="old('valor_numerico')" />
                        @else
                            <label for="av_conceito" class="block text-sm font-medium text-[#4b3728] mb-1">Conceito</label>
                            <x-text-input id="av_conceito" name="conceito" type="text" class="block w-full" :value="old('conceito')" />
                        @endif
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex items-center justify-center rounded-xl bg-[#2b1710] px-4 py-2 text-sm font-bold uppercase tracking-widest text-white transition hover:bg-[#8b4d28]">Salvar avaliação</button>
                    </div>
                </div>
                <div>
                    <label for="av_obs" class="block text-sm font-medium text-[#4b3728] mb-1">Observações</label>
                    <textarea id="av_obs" name="observacoes" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm">{{ old('observacoes') }}</textarea>
                </div>
            </form>
        @endunless
    </section>

    <section class="grid gap-8 xl:grid-cols-2">
        <div id="registrar-aula" class="rounded-[2rem] border border-[#e2d3bf] bg-white p-6 shadow-sm">
            <h3 class="text-xl font-outfit font-semibold text-[#24120d]">Registros de aula</h3>
            <div class="mt-4 space-y-4">
                @forelse ($diario->registrosAula as $registro)
                    <div class="rounded-2xl border border-[#ead9c3] p-4">
                        <p class="font-semibold text-[#24120d]">{{ optional($registro->data_aula)->format('d/m/Y') }} • {{ $registro->titulo }}</p>
                        <p class="mt-2 text-sm text-[#6f5648]">{{ $registro->conteudo_ministrado }}</p>
                        <p class="mt-2 text-xs text-[#8b6f5a]">{{ $registro->frequencias->count() }} frequencia(s) lancadas</p>
                    </div>
                @empty
                    <p class="text-sm text-[#7d6556]">Nenhuma aula registrada.</p>
                @endforelse
            </div>
            @unless ($modoConsulta)
                <form method="POST" action="{{ route('professor.diario.registro-aula.store', $diario) }}" class="mt-6 space-y-4">
                    @csrf
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="ra_data" class="block text-sm font-medium text-[#4b3728] mb-1">Data da aula</label>
                            <x-text-input id="ra_data" name="data_aula" type="date" class="block w-full" :value="old('data_aula')" />
                        </div>
                        <div>
                            <label for="ra_qtd" class="block text-sm font-medium text-[#4b3728] mb-1">Quantidade de aulas</label>
                            <x-text-input id="ra_qtd" name="quantidade_aulas" type="number" class="block w-full" :value="old('quantidade_aulas', 1)" />
                        </div>
                    </div>
                    <div>
                        <label for="ra_titulo" class="block text-sm font-medium text-[#4b3728] mb-1">Título da aula</label>
                        <x-text-input id="ra_titulo" name="titulo" type="text" class="block w-full" :value="old('titulo')" />
                    </div>
                    <div>
                        <label for="ra_horario" class="block text-sm font-medium text-[#4b3728] mb-1">Horário vinculado</label>
                        <select id="ra_horario" name="horario_aula_id" class="block w-full rounded-xl border-stone-300">
                            <option value="">Sem vínculo específico</option>
                            @foreach ($horariosRelacionados as $horario)
                                <option value="{{ $horario->id }}">{{ $diasSemana[$horario->dia_semana] ?? 'Dia' }} • {{ \Carbon\Carbon::parse($horario->horario_inicial)->format('H:i') }} - {{ \Carbon\Carbon::parse($horario->horario_final)->format('H:i') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="ra_conteudo" class="block text-sm font-medium text-[#4b3728] mb-1">Conteúdo ministrado</label>
                        <textarea id="ra_conteudo" name="conteudo_ministrado" rows="4" class="block w-full rounded-xl border-stone-300 shadow-sm">{{ old('conteudo_ministrado') }}</textarea>
                    </div>
                    <button type="submit" class="inline-flex rounded-xl bg-[#2b1710] px-4 py-2 text-sm font-bold uppercase tracking-widest text-white transition hover:bg-[#8b4d28]">Registrar aula</button>
                </form>
            @endunless
        </div>

        <div id="frequencia" class="rounded-[2rem] border border-[#e2d3bf] bg-white p-6 shadow-sm">
            <h3 class="text-xl font-outfit font-semibold text-[#24120d]">Frequencia</h3>
            @unless ($modoConsulta)
                <form method="POST" action="{{ route('professor.diario.frequencia.store', $diario) }}" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label for="freq_aula" class="block text-sm font-medium text-[#4b3728] mb-1">Aula de referência</label>
                        <select id="freq_aula" name="registro_aula_id" class="block w-full rounded-xl border-stone-300">
                            <option value="">Selecione a aula</option>
                            @foreach ($diario->registrosAula as $registro)
                                <option value="{{ $registro->id }}">{{ optional($registro->data_aula)->format('d/m/Y') }} • {{ $registro->titulo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="max-h-80 space-y-4 overflow-y-auto pr-2">
                        @foreach ($matriculasAtivas as $index => $matricula)
                            <div class="rounded-2xl border border-[#ead9c3] p-4">
                                <input type="hidden" name="frequencias[{{ $index }}][matricula_id]" value="{{ $matricula->id }}">
                                <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                    <div><p class="font-semibold text-[#24120d]">{{ $matricula->aluno->nome_completo }}</p><p class="text-xs text-[#8b6f5a]">Matricula {{ strtoupper($matricula->tipo) }} #{{ $matricula->id }}</p></div>
                                    <div>
                                        <label class="block text-xs font-medium text-[#4b3728] mb-1">Situação</label>
                                        <select name="frequencias[{{ $index }}][situacao]" class="md:w-56 block rounded-xl border-stone-300"><option value="presente">Presente</option><option value="falta">Falta</option><option value="falta_justificada">Falta justificada</option><option value="atraso">Atraso</option></select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-[#4b3728] mb-1">Observação</label>
                                    <textarea name="frequencias[{{ $index }}][observacao]" rows="2" class="mt-1 block w-full rounded-xl border-stone-300 shadow-sm"></textarea>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="submit" class="inline-flex rounded-xl bg-[#2b1710] px-4 py-2 text-sm font-bold uppercase tracking-widest text-white transition hover:bg-[#8b4d28]">Salvar frequencia</button>
                </form>
            @else
                <div class="mt-6 space-y-3">
                    @forelse ($diario->registrosAula as $registro)
                        <div class="rounded-2xl border border-[#ead9c3] p-4"><p class="font-semibold text-[#24120d]">{{ optional($registro->data_aula)->format('d/m/Y') }} • {{ $registro->titulo }}</p><p class="mt-2 text-sm text-[#7d6556]">{{ $registro->frequencias->count() }} frequencia(s) registradas.</p></div>
                    @empty
                        <p class="text-sm text-[#7d6556]">Nenhuma frequencia registrada ainda.</p>
                    @endforelse
                </div>
            @endunless
        </div>
    </section>

    <section class="grid gap-8 xl:grid-cols-3">
        <div id="observacoes" class="rounded-[2rem] border border-[#e2d3bf] bg-white p-6 shadow-sm">
            <h3 class="text-xl font-outfit font-semibold text-[#24120d]">Observacoes sobre aluno</h3>
            <div class="mt-4 space-y-4">
                @forelse ($diario->observacoesAluno as $observacao)
                    <div class="rounded-2xl border border-[#ead9c3] p-4"><p class="font-semibold text-[#24120d]">{{ $observacao->matricula->aluno->nome_completo }}</p><p class="mt-1 text-xs uppercase tracking-widest text-[#8b6f5a]">{{ $observacao->categoria }} • {{ optional($observacao->data_observacao)->format('d/m/Y') }}</p><p class="mt-3 text-sm text-[#6f5648]">{{ $observacao->descricao }}</p></div>
                @empty
                    <p class="text-sm text-[#7d6556]">Sem observacoes registradas.</p>
                @endforelse
            </div>
            @unless ($modoConsulta)
                <form method="POST" action="{{ route('professor.diario.observacoes.store', $diario) }}" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label for="obs_aluno" class="block text-sm font-medium text-[#4b3728] mb-1">Aluno</label>
                        <select id="obs_aluno" name="matricula_id" class="block w-full rounded-xl border-stone-300">
                            @foreach ($matriculasAtivas as $matricula)
                                <option value="{{ $matricula->id }}">{{ $matricula->aluno->nome_completo }} - {{ strtoupper($matricula->tipo) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="obs_data" class="block text-sm font-medium text-[#4b3728] mb-1">Data da observação</label>
                            <x-text-input id="obs_data" name="data_observacao" type="date" class="block w-full" :value="old('data_observacao', now()->toDateString())" />
                        </div>
                        <div>
                            <label for="obs_categoria" class="block text-sm font-medium text-[#4b3728] mb-1">Categoria</label>
                            <select id="obs_categoria" name="categoria" class="block w-full rounded-xl border-stone-300"><option value="pedagogica">Pedagógica</option><option value="comportamental">Comportamental</option><option value="inclusao">Inclusão</option><option value="acompanhamento">Acompanhamento</option></select>
                        </div>
                    </div>
                    <div>
                        <label for="obs_descricao" class="block text-sm font-medium text-[#4b3728] mb-1">Descrição da observação</label>
                        <textarea id="obs_descricao" name="descricao" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm"></textarea>
                    </div>
                    <div>
                        <label for="obs_encaminhamento" class="block text-sm font-medium text-[#4b3728] mb-1">Encaminhamento (opcional)</label>
                        <textarea id="obs_encaminhamento" name="encaminhamento" rows="2" class="block w-full rounded-xl border-stone-300 shadow-sm"></textarea>
                    </div>
                    <button type="submit" class="inline-flex rounded-xl bg-[#2b1710] px-4 py-2 text-sm font-bold uppercase tracking-widest text-white transition hover:bg-[#8b4d28]">Registrar observação</button>
                </form>
            @endunless
        </div>

        <div id="ocorrencias" class="rounded-[2rem] border border-[#e2d3bf] bg-white p-6 shadow-sm">
            <h3 class="text-xl font-outfit font-semibold text-[#24120d]">Ocorrencias</h3>
            <div class="mt-4 space-y-4">
                @forelse ($diario->ocorrencias as $ocorrencia)
                    <div class="rounded-2xl border border-[#ead9c3] p-4"><p class="font-semibold text-[#24120d]">{{ ucfirst(str_replace('_', ' ', $ocorrencia->tipo)) }}</p><p class="mt-1 text-xs uppercase tracking-widest text-[#8b6f5a]">{{ optional($ocorrencia->data_ocorrencia)->format('d/m/Y') }} • {{ ucfirst(str_replace('_', ' ', $ocorrencia->status)) }}</p><p class="mt-3 text-sm text-[#6f5648]">{{ $ocorrencia->descricao }}</p></div>
                @empty
                    <p class="text-sm text-[#7d6556]">Sem ocorrencias registradas.</p>
                @endforelse
            </div>
            @unless ($modoConsulta)
                <form method="POST" action="{{ route('professor.diario.ocorrencias.store', $diario) }}" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label for="oc_aluno" class="block text-sm font-medium text-[#4b3728] mb-1">Aluno (ou turma)</label>
                        <select id="oc_aluno" name="matricula_id" class="block w-full rounded-xl border-stone-300"><option value="">Ocorrência geral da turma</option>@foreach ($matriculasAtivas as $matricula)<option value="{{ $matricula->id }}">{{ $matricula->aluno->nome_completo }}</option>@endforeach</select>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="oc_data" class="block text-sm font-medium text-[#4b3728] mb-1">Data da ocorrência</label>
                            <x-text-input id="oc_data" name="data_ocorrencia" type="date" class="block w-full" :value="old('data_ocorrencia', now()->toDateString())" />
                        </div>
                        <div>
                            <label for="oc_tipo" class="block text-sm font-medium text-[#4b3728] mb-1">Tipo</label>
                            <select id="oc_tipo" name="tipo" class="block w-full rounded-xl border-stone-300"><option value="disciplinar">Disciplinar</option><option value="pedagogica">Pedagógica</option><option value="convivencia">Convivência</option><option value="encaminhamento">Encaminhamento</option></select>
                        </div>
                    </div>
                    <div>
                        <label for="oc_descricao" class="block text-sm font-medium text-[#4b3728] mb-1">Descrição</label>
                        <textarea id="oc_descricao" name="descricao" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm"></textarea>
                    </div>
                    <div>
                        <label for="oc_providencias" class="block text-sm font-medium text-[#4b3728] mb-1">Providências</label>
                        <textarea id="oc_providencias" name="providencias" rows="2" class="block w-full rounded-xl border-stone-300 shadow-sm"></textarea>
                    </div>
                    <div>
                        <label for="oc_status" class="block text-sm font-medium text-[#4b3728] mb-1">Status</label>
                        <select id="oc_status" name="status" class="block w-full rounded-xl border-stone-300"><option value="aberta">Aberta</option><option value="em_acompanhamento">Em acompanhamento</option><option value="encerrada">Encerrada</option></select>
                    </div>
                    <button type="submit" class="inline-flex rounded-xl bg-[#2b1710] px-4 py-2 text-sm font-bold uppercase tracking-widest text-white transition hover:bg-[#8b4d28]">Registrar ocorrência</button>
                </form>
            @endunless
        </div>

        <div id="pendencias" class="rounded-[2rem] border border-[#e2d3bf] bg-white p-6 shadow-sm">
            <h3 class="text-xl font-outfit font-semibold text-[#24120d]">Pendencias do professor</h3>
            <div class="mt-4 space-y-4">
                @forelse ($diario->pendencias as $pendencia)
                    <div class="rounded-2xl border border-[#ead9c3] p-4"><p class="font-semibold text-[#24120d]">{{ $pendencia->titulo }}</p><p class="mt-1 text-xs uppercase tracking-widest text-[#8b6f5a]">{{ ucfirst(str_replace('_', ' ', $pendencia->status)) }} @if($pendencia->prazo) • prazo {{ optional($pendencia->prazo)->format('d/m/Y') }} @endif</p><p class="mt-3 text-sm text-[#6f5648]">{{ $pendencia->descricao }}</p></div>
                @empty
                    <p class="text-sm text-[#7d6556]">Sem pendencias registradas.</p>
                @endforelse
            </div>
            @unless ($modoConsulta)
                <form method="POST" action="{{ route('professor.diario.pendencias.store', $diario) }}" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label for="pend_titulo" class="block text-sm font-medium text-[#4b3728] mb-1">Título da pendência</label>
                        <x-text-input id="pend_titulo" name="titulo" type="text" class="block w-full" :value="old('titulo')" />
                    </div>
                    <div>
                        <label for="pend_descricao" class="block text-sm font-medium text-[#4b3728] mb-1">Descrição</label>
                        <textarea id="pend_descricao" name="descricao" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm"></textarea>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="pend_origem" class="block text-sm font-medium text-[#4b3728] mb-1">Origem</label>
                            <select id="pend_origem" name="origem" class="block w-full rounded-xl border-stone-300"><option value="diario">Diário</option><option value="coordenacao">Coordenação</option><option value="direcao">Direção</option><option value="secretaria">Secretaria</option></select>
                        </div>
                        <div>
                            <label for="pend_prazo" class="block text-sm font-medium text-[#4b3728] mb-1">Prazo</label>
                            <x-text-input id="pend_prazo" name="prazo" type="date" class="block w-full" :value="old('prazo')" />
                        </div>
                    </div>
                    <div>
                        <label for="pend_status" class="block text-sm font-medium text-[#4b3728] mb-1">Status</label>
                        <select id="pend_status" name="status" class="block w-full rounded-xl border-stone-300"><option value="aberta">Aberta</option><option value="em_andamento">Em andamento</option><option value="concluida">Concluída</option></select>
                    </div>
                    <button type="submit" class="inline-flex rounded-xl bg-[#2b1710] px-4 py-2 text-sm font-bold uppercase tracking-widest text-white transition hover:bg-[#8b4d28]">Registrar pendência</button>
                </form>
            @endunless
        </div>
    </section>
</div>
