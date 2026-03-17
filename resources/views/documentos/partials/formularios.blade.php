@php
    $mapaOpcoes = [
        'escola_id' => $opcoesFormulario['escolas'] ?? collect(),
        'aluno_id' => $opcoesFormulario['alunos'] ?? collect(),
        'matricula_id' => $opcoesFormulario['matriculas'] ?? collect(),
        'diario_id' => $opcoesFormulario['diarios'] ?? collect(),
        'atendimento_id' => $opcoesFormulario['atendimentos'] ?? collect(),
        'relatorio_id' => $opcoesFormulario['relatoriosPsicossociais'] ?? collect(),
        'encaminhamento_id' => $opcoesFormulario['encaminhamentosPsicossociais'] ?? collect(),
    ];
@endphp

<div class="grid gap-6 xl:grid-cols-2">
    @forelse ($documentos as $documento)
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Documento</p>
                    <h2 class="mt-2 text-xl font-bold text-slate-900">{{ $documento['titulo'] }}</h2>
                    <p class="mt-2 text-sm text-slate-500">{{ $documento['descricao'] }}</p>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-600">Preview</span>
            </div>

            <form method="POST" action="{{ route($rotaPreview, $documento['tipo']) }}" class="mt-6 space-y-4">
                @csrf

                @foreach ($documento['campos'] as $campo)
                    <div>
                        <label class="text-xs font-semibold uppercase tracking-widest text-slate-500">{{ $campo['label'] }}</label>

                        @if ($campo['tipo'] === 'select')
                            <select name="{{ $campo['nome'] }}" class="mt-2 w-full rounded-2xl border-slate-300 text-sm shadow-sm">
                                <option value="">Selecione</option>
                                @foreach ($mapaOpcoes[$campo['nome']] as $opcao)
                                    @php
                                        $label = match ($campo['nome']) {
                                            'escola_id' => $opcao->nome,
                                            'aluno_id' => trim(($opcao->nome_completo ?? $opcao->nome) . ' - ' . ($opcao->rgm ?? 'Sem RGM')),
                                            'matricula_id' => trim(($opcao->aluno->nome_completo ?? 'Matricula') . ' - ' . ($opcao->turma->nome ?? 'Sem turma') . ' - ' . ($opcao->ano_letivo ?? '')),
                                            'diario_id' => trim(($opcao->turma->nome ?? 'Turma') . ' - ' . ($opcao->disciplina->nome ?? 'Disciplina') . ' - ' . ($opcao->periodo_tipo ?? '')),
                                            'atendimento_id' => trim(($opcao->nome_atendido ?? 'Atendimento') . ' - ' . optional($opcao->data_agendada)->format('d/m/Y H:i')),
                                            'relatorio_id' => trim(($opcao->titulo ?? 'Relatorio') . ' - ' . ($opcao->atendimento->nome_atendido ?? '')),
                                            'encaminhamento_id' => trim(($opcao->destino ?? 'Encaminhamento') . ' - ' . ($opcao->atendimento->nome_atendido ?? '')),
                                            default => $opcao->id,
                                        };
                                    @endphp
                                    <option value="{{ $opcao->id }}" @selected(old($campo['nome']) == $opcao->id)>{{ $label }}</option>
                                @endforeach
                            </select>
                        @elseif ($campo['tipo'] === 'textarea')
                            <textarea name="{{ $campo['nome'] }}" rows="5" class="mt-2 w-full rounded-2xl border-slate-300 text-sm shadow-sm">{{ old($campo['nome']) }}</textarea>
                        @else
                            <input type="text" name="{{ $campo['nome'] }}" value="{{ old($campo['nome']) }}" class="mt-2 w-full rounded-2xl border-slate-300 text-sm shadow-sm">
                        @endif

                        @error($campo['nome'])
                            <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                <button type="submit" class="inline-flex items-center rounded-2xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
                    Visualizar documento
                </button>
            </form>
        </div>
    @empty
        <div class="rounded-3xl border border-slate-200 bg-white p-8 text-sm text-slate-500 shadow-sm">
            Nenhum documento disponivel para o perfil autenticado neste portal.
        </div>
    @endforelse
</div>
