@php
    $modoConsulta = $modoConsulta ?? false;
    $horariosRelacionados = $horariosRelacionados ?? collect();
    $tipoAvaliacaoDiario = $tipoAvaliacaoDiario ?? strtolower((string) data_get($diario, 'turma.modalidade.tipo_avaliacao', 'nota'));
    $usaNota = str_contains($tipoAvaliacaoDiario, 'nota');
    $rotulosPlanejamento = ['semanal' => 'Semanal', 'quinzenal' => 'Quinzenal', 'mensal' => 'Mensal', 'semestral' => 'Semestral'];
    $diasSemana = [1 => 'Domingo', 2 => 'Segunda-feira', 3 => 'Terca-feira', 4 => 'Quarta-feira', 5 => 'Quinta-feira', 6 => 'Sexta-feira', 7 => 'Sabado'];
    $statusPlanejamentoCor = [
        'rascunho'  => 'bg-slate-100 text-slate-700',
        'enviado'   => 'bg-blue-100 text-blue-800',
        'aprovado'  => 'bg-emerald-100 text-emerald-800',
        'devolvido' => 'bg-amber-100 text-amber-800',
    ];
    $statusPlanejamentoRotulo = [
        'rascunho'  => 'Rascunho',
        'enviado'   => 'Aguardando aprovacao',
        'aprovado'  => 'Aprovado',
        'devolvido' => 'Devolvido para ajuste',
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

<div class="space-y-8" x-data="{ aba: '{{ $modoConsulta ? 'avaliacoes' : 'registrar-aula' }}' }">
    {{-- PARTE DE CIMA - SEMPRE FIXA --}}
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
            <div class="rounded-2xl bg-[#fbf6ef] p-4"><p class="text-xs uppercase tracking-widest text-[#9a7d67]">Planejamentos</p><p class="mt-2 text-3xl font-outfit font-bold text-[#24120d]">{{ $diario->planejamentosPeriodo->count() + ($diario->planejamentoAnual ? 1 : 0) }}</p></div>
            <div class="rounded-2xl bg-[#fbf6ef] p-4"><p class="text-xs uppercase tracking-widest text-[#9a7d67]">Avaliacoes</p><p class="mt-2 text-3xl font-outfit font-bold text-[#24120d]">{{ $diario->lancamentosAvaliativos->count() }}</p></div>
            <div class="rounded-2xl bg-[#fbf6ef] p-4"><p class="text-xs uppercase tracking-widest text-[#9a7d67]">Observacoes</p><p class="mt-2 text-3xl font-outfit font-bold text-[#24120d]">{{ $diario->observacoesAluno->count() }}</p></div>
            <div class="rounded-2xl bg-[#fbf6ef] p-4"><p class="text-xs uppercase tracking-widest text-[#9a7d67]">Pendencias</p><p class="mt-2 text-3xl font-outfit font-bold text-[#24120d]">{{ $diario->pendencias->count() }}</p></div>
        </div>
    </section>

    {{-- MENU DE ABAS --}}
    <section class="rounded-[1.8rem] border border-[#e2d3bf] bg-white p-5 shadow-sm">
        <div class="flex flex-wrap gap-2">
            <button @click="aba = 'planejamento-anual'" :class="aba === 'planejamento-anual' ? 'bg-[#2b1710] text-white' : 'bg-[#f8efe1] text-[#7b4b2a] hover:bg-[#f1dfc6]'" class="rounded-full px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] transition">Planejamento anual</button>
            <button @click="aba = 'planejamentos-aulas'" :class="aba === 'planejamentos-aulas' ? 'bg-[#2b1710] text-white' : 'bg-[#f8efe1] text-[#7b4b2a] hover:bg-[#f1dfc6]'" class="rounded-full px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] transition">Planejamentos de aulas</button>
            <button @click="aba = 'avaliacoes'" :class="aba === 'avaliacoes' ? 'bg-[#2b1710] text-white' : 'bg-[#f8efe1] text-[#7b4b2a] hover:bg-[#f1dfc6]'" class="rounded-full px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] transition">Notas e conceitos</button>
            <button @click="aba = 'registrar-aula'" :class="aba === 'registrar-aula' ? 'bg-[#2b1710] text-white' : 'bg-[#f8efe1] text-[#7b4b2a] hover:bg-[#f1dfc6]'" class="rounded-full px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] transition">Registrar aula</button>
            <button @click="aba = 'frequencia'" :class="aba === 'frequencia' ? 'bg-[#2b1710] text-white' : 'bg-[#f8efe1] text-[#7b4b2a] hover:bg-[#f1dfc6]'" class="rounded-full px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] transition">Frequencia</button>
            <button @click="aba = 'observacoes'" :class="aba === 'observacoes' ? 'bg-[#2b1710] text-white' : 'bg-[#f8efe1] text-[#7b4b2a] hover:bg-[#f1dfc6]'" class="rounded-full px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] transition">Observacoes</button>
            <button @click="aba = 'ocorrencias'" :class="aba === 'ocorrencias' ? 'bg-[#2b1710] text-white' : 'bg-[#f8efe1] text-[#7b4b2a] hover:bg-[#f1dfc6]'" class="rounded-full px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] transition">Ocorrencias</button>
            <button @click="aba = 'pendencias'" :class="aba === 'pendencias' ? 'bg-[#2b1710] text-white' : 'bg-[#f8efe1] text-[#7b4b2a] hover:bg-[#f1dfc6]'" class="rounded-full px-4 py-2 text-xs font-bold uppercase tracking-[0.18em] transition">Pendencias</button>
        </div>
    </section>

    {{-- CONTEÚDO DAS ABAS --}}
    <div x-show="aba === 'planejamento-anual'" x-cloak>
        <div class="rounded-[2rem] border border-[#e2d3bf] bg-white p-6 shadow-sm" x-data="{ unidade: '1' }">
            @php
                $pa = $diario->planejamentoAnual; // unidade 1 — representa status/validação do plano inteiro
                $unidades = $diario->planejamentosAnuais->keyBy('unidade');
                $bloqueado = !$modoConsulta && $pa && $pa->status === 'enviado';
            @endphp

            <div class="flex flex-wrap items-center justify-between gap-3">
                <h3 class="text-xl font-outfit font-semibold text-[#24120d]">Planejamento anual</h3>
                @if($pa)
                    <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] {{ $statusPlanejamentoCor[$pa->status] ?? 'bg-slate-100 text-slate-700' }}">
                        {{ $statusPlanejamentoRotulo[$pa->status] ?? $pa->status }}
                    </span>
                @endif
            </div>

            {{-- Feedback da coordenação quando devolvido --}}
            @if($pa && $pa->status === 'devolvido' && $pa->validacaoPedagogica)
                <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-amber-700">Devolvido pela coordenacao — ajuste necessario</p>
                    <p class="mt-2 text-sm text-amber-900">{{ $pa->validacaoPedagogica->parecer }}</p>
                    @if($pa->validacaoPedagogica->observacoes_internas)
                        <p class="mt-1 text-xs text-amber-700">{{ $pa->validacaoPedagogica->observacoes_internas }}</p>
                    @endif
                </div>
            @endif

            {{-- Feedback de aprovação --}}
            @if($pa && $pa->status === 'aprovado' && $pa->validacaoPedagogica)
                <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700">Aprovado pela coordenacao</p>
                    <p class="mt-2 text-sm text-emerald-900">{{ $pa->validacaoPedagogica->parecer }}</p>
                </div>
            @endif

            {{-- Aviso de bloqueio enquanto aguarda aprovação --}}
            @if($bloqueado)
                <div class="mt-4 rounded-2xl border border-blue-200 bg-blue-50 p-4 flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0 text-blue-500" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd" /></svg>
                    <div>
                        <p class="text-sm font-bold text-blue-800">Planejamento em analise — somente leitura</p>
                        <p class="mt-1 text-xs text-blue-700">O planejamento anual foi enviado para a coordenacao e nao pode ser alterado enquanto aguarda aprovacao.</p>
                    </div>
                </div>
            @endif

            @unless ($modoConsulta)
            <form method="POST" action="{{ route('professor.diario.planejamento-anual.store', $diario) }}" class="mt-6 space-y-6">
                @csrf

                {{-- Seletor de Unidade (sempre navegável) --}}
                <div class="rounded-2xl border border-[#ead9c3] bg-[#fbf6ef] p-5">
                    <p class="text-sm font-bold uppercase tracking-[0.18em] text-[#7b4b2a] mb-4">Selecione a unidade / bimestre</p>
                    <div class="flex flex-wrap gap-3">
                        @for($i = 1; $i <= 4; $i++)
                            <button type="button"
                                @click="unidade = '{{ $i }}'"
                                :class="unidade == '{{ $i }}' ? 'bg-[#2b1710] text-white' : 'bg-white text-[#2b1710] border border-[#e2d3bf] hover:bg-[#f1dfc6]'"
                                class="px-6 py-3 rounded-xl font-bold text-sm transition">
                                {{ $i }}ª Unidade
                            </button>
                        @endfor
                    </div>
                </div>

                <fieldset {{ $bloqueado ? 'disabled' : '' }} class="{{ $bloqueado ? 'opacity-60' : '' }}">
                {{-- Formulário por Unidade --}}
                <div>
                    @for($i = 1; $i <= 4; $i++)
                    @php($u = $unidades->get($i))
                    <div x-show="unidade == '{{ $i }}'" x-cloak class="space-y-4">
                        <div class="text-lg font-semibold text-[#24120d] py-3 border-b border-[#e2d3bf]">{{ $i }}ª Unidade</div>

                        <div>
                            <label class="block text-sm font-medium text-slate-800 mb-1">Unidade(s) Temática(s)</label>
                            <x-text-input name="unidades[{{ $i }}][tema_gerador]" type="text" class="block w-full" value="{{ $u?->tema_gerador ?? '' }}" placeholder="Ex: Vida e Sociedade" />
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-800 mb-1">Objeto(s) de Conhecimento / Conteúdo(s)</label>
                            <textarea name="unidades[{{ $i }}][conteudos]" rows="2" class="block w-full rounded-xl border-stone-300 shadow-sm" placeholder="Liste os conteúdos desta unidade">{{ $u?->conteudos ?? '' }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-800 mb-1">Habilidades (BNCC)</label>
                            <textarea name="unidades[{{ $i }}][habilidades_competencias]" rows="2" class="block w-full rounded-xl border-stone-300 shadow-sm" placeholder="Liste as habilidades da BNCC">{{ $u?->competencias_habilidades ?? '' }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-800 mb-1">Objetivos das aulas</label>
                            <textarea name="unidades[{{ $i }}][objetivos_aprendizagem]" rows="2" class="block w-full rounded-xl border-stone-300 shadow-sm" placeholder="Descreva os objetivos de aprendizagem">{{ $u?->objetivos_gerais ?? '' }}</textarea>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-slate-800 mb-1">Metodologias / Estratégias</label>
                                <textarea name="unidades[{{ $i }}][metodologia]" rows="2" class="block w-full rounded-xl border-stone-300 shadow-sm" placeholder="Descreva as metodologias e estratégias">{{ $u?->metodologia ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-800 mb-1">Atividades (desenvolvimento das aulas)</label>
                                <textarea name="unidades[{{ $i }}][estrategias_pedagogicas]" rows="2" class="block w-full rounded-xl border-stone-300 shadow-sm" placeholder="Descreva as atividades planejadas">{{ $u?->estrategias_pedagogicas ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-slate-800 mb-1">Recursos didáticos</label>
                                <textarea name="unidades[{{ $i }}][recursos_didaticos]" rows="2" class="block w-full rounded-xl border-stone-300 shadow-sm" placeholder="Liste os recursos a serem utilizados">{{ $u?->recursos_didaticos ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-800 mb-1">Avaliação da aprendizagem</label>
                                <textarea name="unidades[{{ $i }}][instrumentos_avaliacao]" rows="2" class="block w-full rounded-xl border-stone-300 shadow-sm" placeholder="Ex: prova, trabalho, projeto, participação, autoavaliação">{{ $u?->instrumentos_avaliacao ?? '' }}</textarea>
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-slate-800 mb-1">Adequações / Inclusão (AEE / adaptações)</label>
                                <textarea name="unidades[{{ $i }}][adequacoes_inclusao]" rows="2" class="block w-full rounded-xl border-stone-300 shadow-sm" placeholder="Descreva as adaptações necessárias">{{ $u?->adequacoes_inclusao ?? '' }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-800 mb-1">Observações do professor</label>
                                <textarea name="unidades[{{ $i }}][observacoes]" rows="2" class="block w-full rounded-xl border-stone-300 shadow-sm" placeholder="Observações adicionais">{{ $u?->observacoes ?? '' }}</textarea>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-slate-800 mb-1">Referências bibliográficas</label>
                            <textarea name="unidades[{{ $i }}][referencias]" rows="2" class="block w-full rounded-xl border-stone-300 shadow-sm" placeholder="Liste as referências utilizadas">{{ $u?->referencias ?? '' }}</textarea>
                        </div>
                    </div>
                    @endfor

                    {{-- Botões lado a lado --}}
                    @unless($bloqueado)
                    <div class="mt-8 pt-6 border-t border-[#ead9c3] flex items-center gap-3">
                        <button type="submit" class="inline-flex rounded-xl bg-[#2b1710] px-6 py-3 text-sm font-bold uppercase tracking-widest text-white transition hover:bg-[#8b4d28]">Salvar planejamento completo</button>
                        @if($pa && in_array($pa->status, ['rascunho', 'devolvido']))
                            <button type="submit" form="form-enviar-planejamento-anual" class="inline-flex rounded-xl bg-blue-600 px-6 py-3 text-sm font-bold uppercase tracking-widest text-white transition hover:bg-blue-700">
                                {{ $pa->status === 'devolvido' ? 'Reenviar para aprovacao' : 'Enviar para aprovacao' }}
                            </button>
                        @endif
                    </div>
                    @endunless
                </div>

                </fieldset>
            </form>

            {{-- Formulário oculto para envio à aprovação --}}
            @if(!$bloqueado && $pa && in_array($pa->status, ['rascunho', 'devolvido']))
                <form id="form-enviar-planejamento-anual" method="POST" action="{{ route('professor.diario.planejamento-anual.enviar', $diario) }}" class="hidden">
                    @csrf
                    @method('PATCH')
                </form>
            @endif

            @else
                {{-- Modo visualização (coordenação/direção) --}}
                <div class="mt-6 space-y-4">
                    @forelse($diario->planejamentosAnuais as $unidadeItem)
                    <div class="rounded-xl border border-[#ead9c3] p-4">
                        <h4 class="font-bold text-[#24120d] mb-3">{{ $unidadeItem->unidade }}ª Unidade</h4>
                        <div class="grid gap-3 text-sm text-[#6f5648]">
                            @if($unidadeItem->tema_gerador)<div><span class="font-semibold">Unidade Temática:</span> {{ $unidadeItem->tema_gerador }}</div>@endif
                            @if($unidadeItem->objetivos_gerais)<div><span class="font-semibold">Objetivos:</span><br>{!! nl2br(e($unidadeItem->objetivos_gerais)) !!}</div>@endif
                            @if($unidadeItem->competencias_habilidades)<div><span class="font-semibold">Habilidades (BNCC):</span><br>{!! nl2br(e($unidadeItem->competencias_habilidades)) !!}</div>@endif
                            @if($unidadeItem->conteudos)<div><span class="font-semibold">Conteúdos:</span><br>{!! nl2br(e($unidadeItem->conteudos)) !!}</div>@endif
                            @if($unidadeItem->metodologia)<div><span class="font-semibold">Metodologias:</span><br>{!! nl2br(e($unidadeItem->metodologia)) !!}</div>@endif
                            @if($unidadeItem->estrategias_pedagogicas)<div><span class="font-semibold">Atividades:</span><br>{!! nl2br(e($unidadeItem->estrategias_pedagogicas)) !!}</div>@endif
                            @if($unidadeItem->recursos_didaticos)<div><span class="font-semibold">Recursos:</span><br>{!! nl2br(e($unidadeItem->recursos_didaticos)) !!}</div>@endif
                            @if($unidadeItem->instrumentos_avaliacao)<div><span class="font-semibold">Avaliação:</span><br>{!! nl2br(e($unidadeItem->instrumentos_avaliacao)) !!}</div>@endif
                            @if($unidadeItem->adequacoes_inclusao)<div><span class="font-semibold">Adequações/Inclusão:</span><br>{!! nl2br(e($unidadeItem->adequacoes_inclusao)) !!}</div>@endif
                            @if($unidadeItem->observacoes)<div><span class="font-semibold">Observações:</span><br>{!! nl2br(e($unidadeItem->observacoes)) !!}</div>@endif
                            @if($unidadeItem->referencias)<div><span class="font-semibold">Referências:</span><br>{!! nl2br(e($unidadeItem->referencias)) !!}</div>@endif
                        </div>
                    </div>
                    @empty
                    <div class="text-sm text-[#7d6556]">Nenhum planejamento anual registrado.</div>
                    @endforelse
                </div>
            @endunless
        </div>
    </div>

    <div x-show="aba === 'planejamentos-aulas'" x-cloak>
        <div class="rounded-[2rem] border border-[#e2d3bf] bg-white p-6 shadow-sm" x-data="planejamentosAulasForm(@js([
            'planejamento_periodo_id' => old('planejamento_periodo_id'),
            'tipo_planejamento' => old('tipo_planejamento', 'semanal'),
            'periodo_referencia' => old('periodo_referencia'),
            'data_inicio' => old('data_inicio'),
            'data_fim' => old('data_fim'),
            'tema_gerador' => old('tema_gerador'),
            'objetivos_aprendizagem' => old('objetivos_aprendizagem'),
            'habilidades_competencias' => old('habilidades_competencias'),
            'conteudos' => old('conteudos'),
            'metodologia' => old('metodologia'),
            'instrumentos_avaliacao' => old('instrumentos_avaliacao'),
            'estrategias_pedagogicas' => old('estrategias_pedagogicas'),
            'recursos_didaticos' => old('recursos_didaticos'),
            'adequacoes_inclusao' => old('adequacoes_inclusao'),
            'observacoes' => old('observacoes'),
            'referencias' => old('referencias'),
        ]))">
            <h3 class="text-xl font-outfit font-semibold text-[#24120d]">Planejamentos de aulas</h3>
            <div class="mt-4 space-y-3">
                @forelse ($diario->planejamentosPeriodo as $planejamento)
                    <div
                        x-show="{{ $loop->iteration <= 2 ? 'true' : 'mostrarTodosPlanejamentos' }}"
                        @if($loop->iteration > 2) x-cloak @endif
                        class="rounded-2xl border border-[#ead9c3] bg-[#fbf6ef] p-4"
                    >
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="font-semibold text-[#24120d]">{{ $rotulosPlanejamento[$planejamento->tipo_planejamento] ?? ucfirst($planejamento->tipo_planejamento) }} • {{ optional($planejamento->data_inicio)->format('d/m/Y') }} a {{ optional($planejamento->data_fim)->format('d/m/Y') }}</p>
                                @if($planejamento->periodo_referencia)
                                    <p class="mt-1 text-xs font-semibold uppercase tracking-[0.18em] text-[#8b6f5a]">Referencia: {{ $planejamento->periodo_referencia }}</p>
                                @endif
                            </div>
                            <span class="rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] {{ $statusPlanejamentoCor[$planejamento->status] ?? 'bg-slate-100 text-slate-700' }}">
                                {{ $statusPlanejamentoRotulo[$planejamento->status] ?? $planejamento->status }}
                            </span>
                        </div>

                        {{-- Feedback da coordenação quando devolvido --}}
                        @if($planejamento->status === 'devolvido' && $planejamento->validacaoPedagogica)
                            <div class="mt-3 rounded-xl border border-amber-200 bg-amber-50 p-3">
                                <p class="text-xs font-bold uppercase tracking-[0.15em] text-amber-700">Devolvido — {{ $planejamento->validacaoPedagogica->parecer }}</p>
                            </div>
                        @endif

                        {{-- Feedback de aprovação --}}
                        @if($planejamento->status === 'aprovado' && $planejamento->validacaoPedagogica)
                            <div class="mt-3 rounded-xl border border-emerald-200 bg-emerald-50 p-3">
                                <p class="text-xs font-bold uppercase tracking-[0.15em] text-emerald-700">Aprovado — {{ $planejamento->validacaoPedagogica->parecer }}</p>
                            </div>
                        @endif

                        {{-- Botão enviar para aprovação --}}
                        @if($modoConsulta)
                            <div class="mt-4 grid gap-3 text-sm text-[#6f5648]">
                                @foreach($camposPlanejamentoAulas as $campo => $rotulo)
                                    @if(filled($planejamento->{$campo}))
                                        <div>
                                            <span class="font-semibold text-[#24120d]">{{ $rotulo }}:</span><br>
                                            {!! nl2br(e($planejamento->{$campo})) !!}
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        @unless($modoConsulta)
                            @if(in_array($planejamento->status, ['rascunho', 'devolvido']))
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <button
                                        type="button"
                                        @click="carregarPlanejamento(@js([
                                            'planejamento_periodo_id' => $planejamento->id,
                                            'tipo_planejamento' => $planejamento->tipo_planejamento,
                                            'periodo_referencia' => $planejamento->periodo_referencia,
                                            'data_inicio' => optional($planejamento->data_inicio)->format('Y-m-d'),
                                            'data_fim' => optional($planejamento->data_fim)->format('Y-m-d'),
                                            'tema_gerador' => $planejamento->tema_gerador,
                                            'objetivos_aprendizagem' => $planejamento->objetivos_aprendizagem,
                                            'habilidades_competencias' => $planejamento->habilidades_competencias,
                                            'conteudos' => $planejamento->conteudos,
                                            'metodologia' => $planejamento->metodologia,
                                            'instrumentos_avaliacao' => $planejamento->instrumentos_avaliacao,
                                            'estrategias_pedagogicas' => $planejamento->estrategias_pedagogicas,
                                            'recursos_didaticos' => $planejamento->recursos_didaticos,
                                            'adequacoes_inclusao' => $planejamento->adequacoes_inclusao,
                                            'observacoes' => $planejamento->observacoes,
                                            'referencias' => $planejamento->referencias,
                                        ]))"
                                        class="rounded-xl border border-[#2b1710] px-4 py-2 text-xs font-bold uppercase tracking-widest text-[#2b1710] transition hover:bg-[#2b1710] hover:text-white"
                                    >
                                        Editar planejamento
                                    </button>
                                    <form method="POST" action="{{ route('professor.diario.planejamento-periodo.enviar', [$diario, $planejamento]) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2 text-xs font-bold uppercase tracking-widest text-white transition hover:bg-blue-700">
                                            {{ $planejamento->status === 'devolvido' ? 'Reenviar para validacao da coordenacao' : 'Enviar para validacao da coordenacao' }}
                                        </button>
                                    </form>
                                </div>
                            @elseif($planejamento->status === 'enviado')
                                <p class="mt-2 text-xs font-semibold text-blue-700">Aguardando validacao da coordenacao...</p>
                            @endif
                        @endunless
                    </div>
                @empty
                    <p class="text-sm text-[#7d6556]">Nenhum planejamento dinamico registrado.</p>
                @endforelse
            </div>

            @if($diario->planejamentosPeriodo->count() > 2)
                <button
                    type="button"
                    @click="mostrarTodosPlanejamentos = !mostrarTodosPlanejamentos"
                    class="mt-4 inline-flex rounded-xl border border-[#d0b49a] px-4 py-2 text-xs font-bold uppercase tracking-widest text-[#7b4b2a] transition hover:border-[#8b4d28] hover:bg-[#fffaf4]"
                    x-text="mostrarTodosPlanejamentos ? 'Mostrar menos' : 'Mais...'"
                >
                    Mais...
                </button>
            @endif

            @unless ($modoConsulta)
                <form id="form-planejamento-periodo" method="POST" action="{{ route('professor.diario.planejamento-periodo.store', $diario) }}" class="mt-6 space-y-4 rounded-[1.6rem] bg-[#fcf7f0] p-5">
                    @csrf
                    <input type="hidden" name="planejamento_periodo_id" x-model="form.planejamento_periodo_id">
                    <div x-show="editingId" x-cloak class="rounded-2xl border border-blue-200 bg-blue-50 p-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm font-bold text-blue-800">Editando planejamento de aulas</p>
                                <p class="mt-1 text-xs text-blue-700">Altere os campos abaixo e salve novamente antes de enviar para validacao da coordenacao.</p>
                            </div>
                            <button type="button" @click="novoPlanejamento()" class="rounded-xl border border-blue-300 px-4 py-2 text-xs font-bold uppercase tracking-widest text-blue-700 transition hover:bg-blue-100">
                                Novo planejamento
                            </button>
                        </div>
                    </div>
                    <div>
                        <label for="pp_tipo" class="block text-sm font-medium text-slate-800 mb-1">Tipo de planejamento</label>
                        <select id="pp_tipo" name="tipo_planejamento" x-model="form.tipo_planejamento" class="block w-full rounded-xl border-stone-300">
                            @foreach ($rotulosPlanejamento as $valor => $rotulo)
                                <option value="{{ $valor }}">{{ $rotulo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="pp_referencia" class="block text-sm font-medium text-slate-800 mb-1">Referência do período</label>
                        <x-text-input id="pp_referencia" name="periodo_referencia" type="text" class="block w-full" :value="old('periodo_referencia')" x-model="form.periodo_referencia" x-bind:placeholder="form.tipo_planejamento === 'quinzenal' ? 'Ex.: 1a quinzena de abril' : (form.tipo_planejamento === 'mensal' ? 'Ex.: Abril/2026' : (form.tipo_planejamento === 'semestral' ? 'Ex.: 1o semestre/2026' : 'Ex.: Semana 1'))" />
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="pp_data_inicio" class="block text-sm font-medium text-slate-800 mb-1">Data de início</label>
                            <x-text-input id="pp_data_inicio" name="data_inicio" type="date" class="block w-full" :value="old('data_inicio')" x-model="form.data_inicio" />
                        </div>
                        <div>
                            <label for="pp_data_fim" class="block text-sm font-medium text-slate-800 mb-1">Data de fim</label>
                            <x-text-input id="pp_data_fim" name="data_fim" type="date" class="block w-full" :value="old('data_fim')" x-model="form.data_fim" />
                        </div>
                    </div>
                    <div>
                        <label for="pp_tema" class="block text-sm font-medium text-slate-800 mb-1">Unidade(s) Tematica(s)</label>
                        <x-text-input id="pp_tema" name="tema_gerador" type="text" class="block w-full" :value="old('tema_gerador')" x-model="form.tema_gerador" placeholder="Ex.: Vida e Sociedade" />
                    </div>
                    <div>
                        <label for="pp_objetivos" class="block text-sm font-medium text-slate-800 mb-1">Objetivos da aula</label>
                        <textarea id="pp_objetivos" name="objetivos_aprendizagem" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm" x-model="form.objetivos_aprendizagem">{{ old('objetivos_aprendizagem') }}</textarea>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="pp_habilidades" class="block text-sm font-medium text-slate-800 mb-1">Habilidades (BNCC)</label>
                            <textarea id="pp_habilidades" name="habilidades_competencias" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm" x-model="form.habilidades_competencias">{{ old('habilidades_competencias') }}</textarea>
                        </div>
                        <div>
                            <label for="pp_conteudos" class="block text-sm font-medium text-slate-800 mb-1">Objeto(s) de Conhecimento / Conteudo(s)</label>
                            <textarea id="pp_conteudos" name="conteudos" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm" x-model="form.conteudos">{{ old('conteudos') }}</textarea>
                        </div>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="pp_metodologia" class="block text-sm font-medium text-slate-800 mb-1">Metodologias / estrategias</label>
                            <textarea id="pp_metodologia" name="metodologia" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm" x-model="form.metodologia">{{ old('metodologia') }}</textarea>
                        </div>
                        <div>
                            <label for="pp_instrumentos" class="block text-sm font-medium text-slate-800 mb-1">Avaliacao da aprendizagem</label>
                            <textarea id="pp_instrumentos" name="instrumentos_avaliacao" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm" x-model="form.instrumentos_avaliacao">{{ old('instrumentos_avaliacao') }}</textarea>
                        </div>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="pp_atividades" class="block text-sm font-medium text-slate-800 mb-1">Atividades (desenvolvimento da aula)</label>
                            <textarea id="pp_atividades" name="estrategias_pedagogicas" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm" x-model="form.estrategias_pedagogicas">{{ old('estrategias_pedagogicas') }}</textarea>
                        </div>
                        <div>
                            <label for="pp_recursos" class="block text-sm font-medium text-slate-800 mb-1">Recursos didaticos</label>
                            <textarea id="pp_recursos" name="recursos_didaticos" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm" x-model="form.recursos_didaticos">{{ old('recursos_didaticos') }}</textarea>
                        </div>
                    </div>
                    <div>
                        <label for="pp_adequacoes" class="block text-sm font-medium text-slate-800 mb-1">Adequacoes / Inclusao (AEE / adaptacoes)</label>
                        <textarea id="pp_adequacoes" name="adequacoes_inclusao" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm" x-model="form.adequacoes_inclusao">{{ old('adequacoes_inclusao') }}</textarea>
                    </div>
                    <div>
                        <label for="pp_observacoes" class="block text-sm font-medium text-slate-800 mb-1">Observacoes do professor</label>
                        <textarea id="pp_observacoes" name="observacoes" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm" x-model="form.observacoes">{{ old('observacoes') }}</textarea>
                    </div>
                    <div>
                        <label for="pp_referencias" class="block text-sm font-medium text-slate-800 mb-1">Referencias bibliograficas</label>
                        <textarea id="pp_referencias" name="referencias" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm" x-model="form.referencias">{{ old('referencias') }}</textarea>
                    </div>
                    <button type="submit" class="inline-flex rounded-xl bg-[#2b1710] px-4 py-2 text-sm font-bold uppercase tracking-widest text-white transition hover:bg-[#8b4d28]" x-text="editingId ? 'Salvar alteracoes do planejamento' : 'Registrar planejamento do periodo'">Registrar planejamento do periodo</button>
                </form>
            @endunless
        </div>
    </div>

    <div x-show="aba === 'avaliacoes'" x-cloak>
        <div class="rounded-[2rem] border border-[#e2d3bf] bg-white p-6 shadow-sm">
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
                            <label for="av_matricula" class="block text-sm font-medium text-slate-800 mb-1">Aluno</label>
                            <select id="av_matricula" name="matricula_id" class="block w-full rounded-xl border-stone-300">
                                @foreach ($matriculasAtivas as $matricula)
                                    <option value="{{ $matricula->id }}">{{ $matricula->aluno->nome_completo }} - {{ strtoupper($matricula->tipo) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="av_referencia" class="block text-sm font-medium text-slate-800 mb-1">Referência da avaliação</label>
                            <x-text-input id="av_referencia" name="avaliacao_referencia" type="text" class="block w-full" :value="old('avaliacao_referencia', $diario->periodo_tipo . ' ' . $diario->periodo_referencia)" />
                        </div>
                        <div>
                            @if ($usaNota)
                                <label for="av_valor" class="block text-sm font-medium text-slate-800 mb-1">Nota (0–100)</label>
                                <x-text-input id="av_valor" name="valor_numerico" type="number" step="0.01" min="0" max="100" class="block w-full" :value="old('valor_numerico')" />
                            @else
                                <label for="av_conceito" class="block text-sm font-medium text-slate-800 mb-1">Conceito</label>
                                <x-text-input id="av_conceito" name="conceito" type="text" class="block w-full" :value="old('conceito')" />
                            @endif
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full inline-flex items-center justify-center rounded-xl bg-[#2b1710] px-4 py-2 text-sm font-bold uppercase tracking-widest text-white transition hover:bg-[#8b4d28]">Salvar avaliação</button>
                        </div>
                    </div>
                    <div>
                        <label for="av_obs" class="block text-sm font-medium text-slate-800 mb-1">Observações</label>
                        <textarea id="av_obs" name="observacoes" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm">{{ old('observacoes') }}</textarea>
                    </div>
                </form>
            @endunless
        </div>
    </div>

    <div x-show="aba === 'registrar-aula'" x-cloak>
        <div class="rounded-[2rem] border border-[#e2d3bf] bg-white p-6 shadow-sm">
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
                            <label for="ra_data" class="block text-sm font-medium text-slate-800 mb-1">Data da aula</label>
                            <x-text-input id="ra_data" name="data_aula" type="date" class="block w-full" :value="old('data_aula')" />
                        </div>
                        <div>
                            <label for="ra_qtd" class="block text-sm font-medium text-slate-800 mb-1">Quantidade de aulas</label>
                            <x-text-input id="ra_qtd" name="quantidade_aulas" type="number" class="block w-full" :value="old('quantidade_aulas', 1)" />
                        </div>
                    </div>
                    <div>
                        <label for="ra_titulo" class="block text-sm font-medium text-slate-800 mb-1">Título da aula</label>
                        <x-text-input id="ra_titulo" name="titulo" type="text" class="block w-full" :value="old('titulo')" />
                    </div>
                    <div>
                        <label for="ra_horario" class="block text-sm font-medium text-slate-800 mb-1">Horário vinculado</label>
                        <select id="ra_horario" name="horario_aula_id" class="block w-full rounded-xl border-stone-300">
                            <option value="">Sem vínculo específico</option>
                            @foreach ($horariosRelacionados as $horario)
                                <option value="{{ $horario->id }}">{{ $diasSemana[$horario->dia_semana] ?? 'Dia' }} • {{ \Carbon\Carbon::parse($horario->horario_inicial)->format('H:i') }} - {{ \Carbon\Carbon::parse($horario->horario_final)->format('H:i') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="ra_conteudo" class="block text-sm font-medium text-slate-800 mb-1">Conteúdo ministrado</label>
                        <textarea id="ra_conteudo" name="conteudo_ministrado" rows="4" class="block w-full rounded-xl border-stone-300 shadow-sm">{{ old('conteudo_ministrado') }}</textarea>
                    </div>
                    <button type="submit" class="inline-flex rounded-xl bg-[#2b1710] px-4 py-2 text-sm font-bold uppercase tracking-widest text-white transition hover:bg-[#8b4d28]">Registrar aula</button>
                </form>
            @endunless
        </div>
    </div>

    <div x-show="aba === 'frequencia'" x-cloak>
        <div class="rounded-[2rem] border border-[#e2d3bf] bg-white p-6 shadow-sm">
            <h3 class="text-xl font-outfit font-semibold text-[#24120d]">Frequencia</h3>
            @unless ($modoConsulta)
                <form method="POST" action="{{ route('professor.diario.frequencia.store', $diario) }}" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label for="freq_aula" class="block text-sm font-medium text-slate-800 mb-1">Aula de referência</label>
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
                                        <label class="block text-xs font-medium text-slate-800 mb-1">Situação</label>
                                        <select name="frequencias[{{ $index }}][situacao]" class="md:w-56 block rounded-xl border-stone-300"><option value="presente">Presente</option><option value="falta">Falta</option><option value="falta_justificada">Falta justificada</option><option value="atraso">Atraso</option></select>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-800 mb-1">Observação</label>
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
    </div>

    <div x-show="aba === 'observacoes'" x-cloak>
        <div class="rounded-[2rem] border border-[#e2d3bf] bg-white p-6 shadow-sm">
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
                        <label for="obs_aluno" class="block text-sm font-medium text-slate-800 mb-1">Aluno</label>
                        <select id="obs_aluno" name="matricula_id" class="block w-full rounded-xl border-stone-300">
                            @foreach ($matriculasAtivas as $matricula)
                                <option value="{{ $matricula->id }}">{{ $matricula->aluno->nome_completo }} - {{ strtoupper($matricula->tipo) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="obs_data" class="block text-sm font-medium text-slate-800 mb-1">Data da observação</label>
                            <x-text-input id="obs_data" name="data_observacao" type="date" class="block w-full" :value="old('data_observacao', now()->toDateString())" />
                        </div>
                        <div>
                            <label for="obs_categoria" class="block text-sm font-medium text-slate-800 mb-1">Categoria</label>
                            <select id="obs_categoria" name="categoria" class="block w-full rounded-xl border-stone-300"><option value="pedagogica">Pedagógica</option><option value="comportamental">Comportamental</option><option value="inclusao">Inclusão</option><option value="acompanhamento">Acompanhamento</option></select>
                        </div>
                    </div>
                    <div>
                        <label for="obs_descricao" class="block text-sm font-medium text-slate-800 mb-1">Descrição da observação</label>
                        <textarea id="obs_descricao" name="descricao" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm"></textarea>
                    </div>
                    <div>
                        <label for="obs_encaminhamento" class="block text-sm font-medium text-slate-800 mb-1">Encaminhamento (opcional)</label>
                        <textarea id="obs_encaminhamento" name="encaminhamento" rows="2" class="block w-full rounded-xl border-stone-300 shadow-sm"></textarea>
                    </div>
                    <button type="submit" class="inline-flex rounded-xl bg-[#2b1710] px-4 py-2 text-sm font-bold uppercase tracking-widest text-white transition hover:bg-[#8b4d28]">Registrar observação</button>
                </form>
            @endunless
        </div>
    </div>

    <div x-show="aba === 'ocorrencias'" x-cloak>
        <div class="rounded-[2rem] border border-[#e2d3bf] bg-white p-6 shadow-sm">
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
                        <label for="oc_aluno" class="block text-sm font-medium text-slate-800 mb-1">Aluno (ou turma)</label>
                        <select id="oc_aluno" name="matricula_id" class="block w-full rounded-xl border-stone-300"><option value="">Ocorrência geral da turma</option>@foreach ($matriculasAtivas as $matricula)<option value="{{ $matricula->id }}">{{ $matricula->aluno->nome_completo }}</option>@endforeach</select>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="oc_data" class="block text-sm font-medium text-slate-800 mb-1">Data da ocorrência</label>
                            <x-text-input id="oc_data" name="data_ocorrencia" type="date" class="block w-full" :value="old('data_ocorrencia', now()->toDateString())" />
                        </div>
                        <div>
                            <label for="oc_tipo" class="block text-sm font-medium text-slate-800 mb-1">Tipo</label>
                            <select id="oc_tipo" name="tipo" class="block w-full rounded-xl border-stone-300"><option value="disciplinar">Disciplinar</option><option value="pedagogica">Pedagógica</option><option value="convivencia">Convivência</option><option value="encaminhamento">Encaminhamento</option></select>
                        </div>
                    </div>
                    <div>
                        <label for="oc_descricao" class="block text-sm font-medium text-slate-800 mb-1">Descrição</label>
                        <textarea id="oc_descricao" name="descricao" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm"></textarea>
                    </div>
                    <div>
                        <label for="oc_providencias" class="block text-sm font-medium text-slate-800 mb-1">Providências</label>
                        <textarea id="oc_providencias" name="providencias" rows="2" class="block w-full rounded-xl border-stone-300 shadow-sm"></textarea>
                    </div>
                    <div>
                        <label for="oc_status" class="block text-sm font-medium text-slate-800 mb-1">Status</label>
                        <select id="oc_status" name="status" class="block w-full rounded-xl border-stone-300"><option value="aberta">Aberta</option><option value="em_acompanhamento">Em acompanhamento</option><option value="encerrada">Encerrada</option></select>
                    </div>
                    <button type="submit" class="inline-flex rounded-xl bg-[#2b1710] px-4 py-2 text-sm font-bold uppercase tracking-widest text-white transition hover:bg-[#8b4d28]">Registrar ocorrência</button>
                </form>
            @endunless
        </div>
    </div>

    <div x-show="aba === 'pendencias'" x-cloak>
        <div class="rounded-[2rem] border border-[#e2d3bf] bg-white p-6 shadow-sm">
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
                        <label for="pend_titulo" class="block text-sm font-medium text-slate-800 mb-1">Título da pendência</label>
                        <x-text-input id="pend_titulo" name="titulo" type="text" class="block w-full" :value="old('titulo')" />
                    </div>
                    <div>
                        <label for="pend_descricao" class="block text-sm font-medium text-slate-800 mb-1">Descrição</label>
                        <textarea id="pend_descricao" name="descricao" rows="3" class="block w-full rounded-xl border-stone-300 shadow-sm">{{ old('descricao') }}</textarea>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label for="pend_origem" class="block text-sm font-medium text-slate-800 mb-1">Origem</label>
                            <select id="pend_origem" name="origem" class="block w-full rounded-xl border-stone-300"><option value="diario">Diário</option><option value="coordenacao">Coordenação</option><option value="direcao">Direção</option><option value="secretaria">Secretaria</option></select>
                        </div>
                        <div>
                            <label for="pend_prazo" class="block text-sm font-medium text-slate-800 mb-1">Prazo</label>
                            <x-text-input id="pend_prazo" name="prazo" type="date" class="block w-full" :value="old('prazo')" />
                        </div>
                    </div>
                    <div>
                        <label for="pend_status" class="block text-sm font-medium text-slate-800 mb-1">Status</label>
                        <select id="pend_status" name="status" class="block w-full rounded-xl border-stone-300"><option value="aberta">Aberta</option><option value="em_andamento">Em andamento</option><option value="concluida">Concluída</option></select>
                    </div>
                    <button type="submit" class="inline-flex rounded-xl bg-[#2b1710] px-4 py-2 text-sm font-bold uppercase tracking-widest text-white transition hover:bg-[#8b4d28]">Registrar pendência</button>
                </form>
            @endunless
        </div>
    </div>
</div>

<script>
    function planejamentosAulasForm(initial) {
        const vazio = {
            planejamento_periodo_id: '',
            tipo_planejamento: 'semanal',
            periodo_referencia: '',
            data_inicio: '',
            data_fim: '',
            tema_gerador: '',
            objetivos_aprendizagem: '',
            habilidades_competencias: '',
            conteudos: '',
            metodologia: '',
            instrumentos_avaliacao: '',
            estrategias_pedagogicas: '',
            recursos_didaticos: '',
            adequacoes_inclusao: '',
            observacoes: '',
            referencias: '',
        };

        const normalizar = (dados) => Object.fromEntries(
            Object.entries({ ...vazio, ...dados }).map(([campo, valor]) => [campo, valor ?? ''])
        );

        return {
            form: normalizar(initial),
            editingId: initial?.planejamento_periodo_id || '',
            mostrarTodosPlanejamentos: false,
            carregarPlanejamento(planejamento) {
                this.form = normalizar(planejamento);
                this.editingId = this.form.planejamento_periodo_id;
                this.$nextTick(() => document.getElementById('form-planejamento-periodo')?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                }));
            },
            novoPlanejamento() {
                this.form = { ...vazio };
                this.editingId = '';
            },
        };
    }
</script>

<style>
    [x-cloak] { display: none !important; }
</style>
