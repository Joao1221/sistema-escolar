@php
    $mapaOpcoes = [
        'escola_id' => $opcoesFormulario['escolas'] ?? collect(),
        'turma_id' => $opcoesFormulario['turmas'] ?? collect(),
        'modalidade_id' => $opcoesFormulario['modalidades'] ?? collect(),
        'matricula_id' => $opcoesFormulario['matriculas'] ?? collect(),
        'professor_id' => $opcoesFormulario['professores'] ?? collect(),
    ];
@endphp

<div class="grid gap-6 xl:grid-cols-2">
    @forelse ($relatorios as $relatorio)
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Relatorio</p>
                    <h2 class="mt-2 text-xl font-bold text-slate-900">{{ $relatorio['titulo'] }}</h2>
                    <p class="mt-2 text-sm text-slate-500">{{ $relatorio['descricao'] }}</p>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-600">Filtro</span>
            </div>

            <form method="POST" action="{{ route($rotaPreview, $relatorio['tipo']) }}" class="mt-6 space-y-4">
                @csrf

                @foreach ($relatorio['campos'] as $campo)
                    @php
                        $campoId = 'relatorio-' . $relatorio['tipo'] . '-' . $campo['nome'];
                    @endphp
                    <div>
                        <label for="{{ $campoId }}" class="text-xs font-semibold uppercase tracking-widest text-slate-500">{{ $campo['label'] }}</label>

                        @if ($campo['tipo'] === 'select')
                            <select id="{{ $campoId }}" name="{{ $campo['nome'] }}" class="mt-2 w-full rounded-2xl border-slate-300 text-sm shadow-sm">
                                <option value="">Selecione</option>
                                @foreach ($mapaOpcoes[$campo['nome']] as $opcao)
                                    @php
                                        $label = match ($campo['nome']) {
                                            'escola_id' => $opcao->nome,
                                            'turma_id' => trim(($opcao->nome ?? 'Turma') . ' - ' . ($opcao->ano_letivo ?? '')),
                                            'modalidade_id' => $opcao->nome,
                                            'matricula_id' => trim(($opcao->aluno->nome_completo ?? 'Matricula') . ' - ' . ($opcao->turma->nome ?? 'Sem turma') . ' - ' . ($opcao->ano_letivo ?? '')),
                                            'professor_id' => $opcao->nome ?? 'Professor',
                                            default => $opcao->id,
                                        };
                                    @endphp
                                    <option value="{{ $opcao->id }}" @selected(old($campo['nome']) == $opcao->id)>{{ $label }}</option>
                                @endforeach
                            </select>
                        @elseif ($campo['tipo'] === 'number')
                            <input id="{{ $campoId }}" type="number" name="{{ $campo['nome'] }}" value="{{ old($campo['nome']) }}" class="mt-2 w-full rounded-2xl border-slate-300 text-sm shadow-sm">
                        @elseif ($campo['tipo'] === 'date')
                            <input id="{{ $campoId }}" type="date" name="{{ $campo['nome'] }}" value="{{ old($campo['nome']) }}" class="mt-2 w-full rounded-2xl border-slate-300 text-sm shadow-sm">
                        @else
                            <input id="{{ $campoId }}" type="text" name="{{ $campo['nome'] }}" value="{{ old($campo['nome']) }}" class="mt-2 w-full rounded-2xl border-slate-300 text-sm shadow-sm">
                        @endif

                        @error($campo['nome'])
                            <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                <button type="submit" class="inline-flex items-center rounded-2xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
                    Visualizar relatorio
                </button>
            </form>
        </div>
    @empty
        <div class="rounded-3xl border border-slate-200 bg-white p-8 text-sm text-slate-500 shadow-sm">
            Nenhum relatorio disponivel para o perfil autenticado neste portal.
        </div>
    @endforelse
</div>
