<x-psicologia-layout :titulo="$tituloPagina" :subtitulo="$subtituloPagina" :breadcrumbs="$breadcrumbs">
    @php
        $tipoRelatorioSelecionado = $filtros['tipo_relatorio'] ?? '';
        $periodoDescricao = match ($tipoRelatorioSelecionado) {
            'por_periodo' => filled($filtros['data_inicio'] ?? null) && filled($filtros['data_fim'] ?? null)
                ? 'Periodo de ' . \Illuminate\Support\Carbon::parse($filtros['data_inicio'])->format('d/m/Y') . ' ate ' . \Illuminate\Support\Carbon::parse($filtros['data_fim'])->format('d/m/Y')
                : 'Periodo personalizado',
            'por_profissional' => 'Atendimentos filtrados por profissional responsavel',
            'geral_mes' => 'Relatorio consolidado de ' . ($meses[(int) ($filtros['mes'] ?? 0)] ?? 'mes selecionado') . ' de ' . ($filtros['ano'] ?? now()->year),
            default => 'Relatorio tecnico restrito',
        };

        $formatarValor = function (\App\Models\AtendimentoPsicossocial $atendimento, string $campo): string {
            return match ($campo) {
                'data_agendada' => $atendimento->data_agendada?->format('d/m/Y H:i') ?? '-',
                'data_realizacao' => $atendimento->data_realizacao?->format('d/m/Y H:i') ?? '-',
                'nome_atendido' => $atendimento->nome_atendido,
                'escola' => $atendimento->escola?->nome ?? '-',
                'tipo_publico' => ucfirst(str_replace('_', ' ', $atendimento->tipo_publico ?? '-')),
                'tipo_atendimento' => ucfirst(str_replace('_', ' ', $atendimento->tipo_atendimento ?? '-')),
                'status' => ucfirst(str_replace('_', ' ', $atendimento->status ?? '-')),
                'profissional_responsavel' => $atendimento->profissionalResponsavel?->nome ?? 'Nao vinculado',
                'local_atendimento' => $atendimento->local_atendimento ?: '-',
                'motivo_demanda' => $atendimento->motivo_demanda ?: '-',
                'nivel_sigilo' => ucfirst(str_replace('_', ' ', $atendimento->nivel_sigilo ?? '-')),
                'requer_acompanhamento' => $atendimento->requer_acompanhamento ? 'Sim' : 'Nao',
                default => '-',
            };
        };
    @endphp

    <style>
        .relatorio-cabecalho {
            position: relative;
            padding-right: 260px;
        }

        .relatorio-resumo {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 240px;
        }

        @media (max-width: 767px) {
            .relatorio-cabecalho {
                padding-right: 0;
            }

            .relatorio-resumo {
                position: static;
                min-width: 0;
            }
        }

        @media print {
            @page {
                size: landscape;
                margin: 12mm;
            }

            html,
            body {
                margin: 0 !important;
                padding: 0 !important;
                background: #fff !important;
            }

            body * {
                visibility: hidden !important;
            }

            #relatorio-impressao,
            #relatorio-impressao * {
                visibility: visible !important;
            }

            #relatorio-impressao {
                position: absolute !important;
                inset: 0 auto auto 0 !important;
                width: 100% !important;
                box-sizing: border-box !important;
                background: #fff !important;
                padding: 24px !important;
                margin: 0 !important;
                border: none !important;
                border-radius: 0 !important;
                box-shadow: none !important;
            }

            .no-print {
                display: none !important;
            }

            .print-area {
                box-shadow: none !important;
                border: none !important;
                padding: 0 !important;
                margin: 0 !important;
                overflow: visible !important;
            }

            .relatorio-tabela-wrapper {
                overflow: visible !important;
            }

            .relatorio-tabela {
                width: 100% !important;
                table-layout: auto !important;
                border-collapse: collapse !important;
            }

            .relatorio-tabela-col-indice {
                width: 22px !important;
            }

            .relatorio-tabela thead th {
                font-size: 9px !important;
                line-height: 1.2 !important;
                white-space: normal !important;
            }

            .relatorio-tabela .coluna-indice {
                width: 22px !important;
                max-width: 22px !important;
                padding-left: 4px !important;
                padding-right: 4px !important;
                white-space: nowrap !important;
            }

            .relatorio-tabela thead th:not(.coluna-indice),
            .relatorio-tabela tbody td:not(.coluna-indice) {
                padding-left: 8px !important;
                padding-right: 8px !important;
            }

            .relatorio-tabela tbody td {
                font-size: 9px !important;
                line-height: 1.2 !important;
                white-space: normal !important;
                word-break: break-word !important;
                overflow-wrap: anywhere !important;
                vertical-align: top !important;
            }

            .relatorio-tabela .celula-conteudo {
                max-width: none !important;
                white-space: normal !important;
                word-break: break-word !important;
                overflow-wrap: anywhere !important;
            }

            .relatorio-prefeitura {
                font-size: 11px !important;
                line-height: 1.2 !important;
            }

            .relatorio-titulo {
                font-size: 18px !important;
                line-height: 1.1 !important;
            }

            .relatorio-secretaria,
            .relatorio-periodo {
                font-size: 10px !important;
                line-height: 1.25 !important;
            }

            .relatorio-resumo {
                font-size: 9px !important;
                line-height: 1.25 !important;
            }

            .relatorio-cabecalho {
                display: grid !important;
                grid-template-columns: minmax(0, 1fr) 260px !important;
                align-items: start !important;
                column-gap: 16px !important;
                padding-right: 0 !important;
            }

            .relatorio-cabecalho-conteudo {
                grid-column: 1 !important;
                min-width: 0 !important;
            }

            .relatorio-resumo {
                position: static !important;
                grid-column: 2 !important;
                grid-row: 1 !important;
                align-self: start !important;
                justify-self: end !important;
                min-width: 240px !important;
            }
        }
    </style>

    <div class="space-y-6">
        <section class="no-print rounded-[1.75rem] border border-cyan-100 bg-gradient-to-r from-cyan-50 via-cyan-25 to-cyan-100 p-6 shadow-sm">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.28em] text-cyan-700">Gerador restrito</p>
                    <h1 class="mt-3 text-3xl font-bold text-[#14363a]">Relatorios de atendimentos</h1>
                    <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">
                        Emite uma listagem tecnica semelhante ao gerador legado, com filtros por periodo, profissional ou mes e selecao das colunas exibidas.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('psicologia.relatorios_atendimentos.index') }}" class="inline-flex items-center rounded-2xl border border-black bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                        Limpar filtros
                    </a>
                    @if ($gerouRelatorio)
                        <button type="button" onclick="window.print()" class="inline-flex items-center rounded-2xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800">
                            Imprimir relatorio
                        </button>
                    @endif
                </div>
            </div>
        </section>

        <form method="GET" class="no-print rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div>
                    <label for="tipo_relatorio" class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Tipo de relatorio</label>
                    <select id="tipo_relatorio" name="tipo_relatorio" class="mt-2 w-full rounded-2xl border-slate-300 text-sm shadow-sm" required>
                        <option value="">Selecione</option>
                        @foreach ($opcoesRelatorio['tipos_relatorio'] as $valor => $rotulo)
                            <option value="{{ $valor }}" @selected($tipoRelatorioSelecionado === $valor)>{{ $rotulo }}</option>
                        @endforeach
                    </select>
                    @error('tipo_relatorio')
                        <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="escola_id" class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Escola</label>
                    <select id="escola_id" name="escola_id" class="mt-2 w-full rounded-2xl border-slate-300 text-sm shadow-sm">
                        <option value="">Todas</option>
                        @foreach ($opcoesRelatorio['escolas'] as $escola)
                            <option value="{{ $escola->id }}" @selected((string) ($filtros['escola_id'] ?? '') === (string) $escola->id)>{{ $escola->nome }}</option>
                        @endforeach
                    </select>
                    @error('escola_id')
                        <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div id="filtroProfissional" class="hidden">
                    <label for="profissional_id" class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Profissional</label>
                    <select id="profissional_id" name="profissional_id" class="mt-2 w-full rounded-2xl border-slate-300 text-sm shadow-sm">
                        <option value="">Selecione</option>
                        @foreach ($opcoesRelatorio['profissionais'] as $profissional)
                            <option value="{{ $profissional->id }}" @selected((string) ($filtros['profissional_id'] ?? '') === (string) $profissional->id)>{{ $profissional->nome }}</option>
                        @endforeach
                    </select>
                    @error('profissional_id')
                        <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tipo_atendimento" class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Area tecnica</label>
                    <select id="tipo_atendimento" name="tipo_atendimento" class="mt-2 w-full rounded-2xl border-slate-300 text-sm shadow-sm">
                        <option value="">Todas</option>
                        @foreach ($opcoesRelatorio['tipos_atendimento'] as $valor => $rotulo)
                            <option value="{{ $valor }}" @selected(($filtros['tipo_atendimento'] ?? '') === $valor)>{{ $rotulo }}</option>
                        @endforeach
                    </select>
                    @error('tipo_atendimento')
                        <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div id="filtroDataInicio" class="hidden">
                    <label for="data_inicio" class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Data inicial</label>
                    <input id="data_inicio" type="date" name="data_inicio" value="{{ $filtros['data_inicio'] ?? '' }}" class="mt-2 w-full rounded-2xl border-slate-300 text-sm shadow-sm">
                    @error('data_inicio')
                        <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div id="filtroDataFim" class="hidden">
                    <label for="data_fim" class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Data final</label>
                    <input id="data_fim" type="date" name="data_fim" value="{{ $filtros['data_fim'] ?? '' }}" class="mt-2 w-full rounded-2xl border-slate-300 text-sm shadow-sm">
                    @error('data_fim')
                        <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div id="filtroMes" class="hidden">
                    <label for="mes" class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Mes</label>
                    <select id="mes" name="mes" class="mt-2 w-full rounded-2xl border-slate-300 text-sm shadow-sm">
                        <option value="">Selecione</option>
                        @foreach ($meses as $valor => $rotulo)
                            <option value="{{ $valor }}" @selected((int) ($filtros['mes'] ?? 0) === $valor)>{{ $rotulo }}</option>
                        @endforeach
                    </select>
                    @error('mes')
                        <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div id="filtroAno" class="hidden">
                    <label for="ano" class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Ano</label>
                    <input id="ano" type="number" name="ano" value="{{ $filtros['ano'] ?? now()->year }}" min="2000" max="2100" class="mt-2 w-full rounded-2xl border-slate-300 text-sm shadow-sm">
                    @error('ano')
                        <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Status</label>
                    <select id="status" name="status" class="mt-2 w-full rounded-2xl border-slate-300 text-sm shadow-sm">
                        <option value="">Todos</option>
                        @foreach ($opcoesRelatorio['status'] as $valor => $rotulo)
                            <option value="{{ $valor }}" @selected(($filtros['status'] ?? '') === $valor)>{{ $rotulo }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 border-t border-slate-100 pt-6">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">Campos exibidos</p>
                        <p class="mt-1 text-sm text-slate-500">Selecione as colunas da tabela final antes de gerar a listagem.</p>
                    </div>
                </div>

                <div class="mt-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach ($camposDisponiveis as $campo => $rotulo)
                        <label class="flex items-start gap-3 rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-700 transition hover:border-cyan-200 hover:bg-cyan-50/50">
                            <input type="checkbox" name="campos[]" value="{{ $campo }}" @checked(in_array($campo, $camposSelecionados, true)) class="mt-0.5 rounded border-slate-300 text-cyan-600 shadow-sm focus:ring-cyan-500">
                            <span>{{ $rotulo }}</span>
                        </label>
                    @endforeach
                </div>
                @error('campos')
                    <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                @enderror
                @error('campos.*')
                    <p class="mt-2 text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <button type="submit" class="inline-flex items-center rounded-2xl border border-black bg-black px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-900">
                    Gerar relatorio
                </button>
                <a href="{{ route('psicologia.relatorios_atendimentos.index') }}" class="inline-flex items-center rounded-2xl border border-black bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50">
                    Voltar ao estado inicial
                </a>
            </div>
        </form>

        @if ($gerouRelatorio)
            <section id="relatorio-impressao" class="print-area rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="relatorio-cabecalho flex flex-col gap-4 border-b border-slate-100 pb-6">
                    <div class="relatorio-cabecalho-conteudo flex items-start gap-4">
                        @if ($instituicao?->brasao_url)
                            <img src="{{ $instituicao->brasao_url }}" alt="Brasao institucional" class="h-16 w-16 rounded-2xl object-contain ring-1 ring-slate-200">
                        @endif

                        <div>
                            <p class="relatorio-prefeitura text-xs font-semibold uppercase tracking-[0.24em] text-slate-500">
                                {{ $instituicao?->nome_prefeitura ?: 'Prefeitura nao configurada' }}
                                @if ($instituicao?->uf)
                                    / {{ $instituicao->uf }}
                                @endif
                            </p>
                            <h2 class="relatorio-titulo mt-2 text-2xl font-bold text-[#14363a]">Relatorio de atendimentos</h2>
                            <p class="relatorio-secretaria mt-1 text-sm text-slate-500">{{ $instituicao?->nome_secretaria ?: 'Secretaria nao configurada' }}</p>
                            <p class="relatorio-periodo mt-2 text-sm text-slate-600">{{ $periodoDescricao }}</p>
                        </div>
                    </div>

                    <div class="relatorio-resumo rounded-2xl bg-slate-50 px-4 py-3 text-sm text-slate-600">
                        <p class="font-semibold text-slate-700 whitespace-nowrap">Emitido em {{ now()->format('d/m/Y H:i') }}</p>
                        <p class="mt-1">Total de registros: {{ $resultados->count() }}</p>
                    </div>
                </div>

                @if ($resultados->isEmpty())
                    <div class="py-12 text-center">
                        <p class="text-base font-semibold text-slate-700">Nenhum atendimento encontrado.</p>
                        <p class="mt-2 text-sm text-slate-500">Revise os filtros aplicados e gere o relatorio novamente.</p>
                    </div>
                @else
                    <div class="relatorio-tabela-wrapper mt-6 overflow-x-auto">
                        <table class="relatorio-tabela min-w-full divide-y divide-slate-200 text-sm">
                            <colgroup>
                                <col class="relatorio-tabela-col-indice">
                                @foreach ($camposSelecionados as $campo)
                                    <col>
                                @endforeach
                            </colgroup>
                            <thead class="bg-slate-50">
                                <tr class="text-left text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">
                                    <th class="coluna-indice px-4 py-3">#</th>
                                    @foreach ($camposSelecionados as $campo)
                                        <th class="px-4 py-3">{{ $camposDisponiveis[$campo] }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($resultados as $indice => $atendimento)
                                    <tr class="align-top">
                                        <td class="coluna-indice px-4 py-3 font-semibold text-slate-500">{{ $indice + 1 }}</td>
                                        @foreach ($camposSelecionados as $campo)
                                            <td class="px-4 py-3 text-slate-700">
                                                <div class="celula-conteudo max-w-xs whitespace-pre-wrap break-words">{{ $formatarValor($atendimento, $campo) }}</div>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        @endif
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const seletor = document.getElementById('tipo_relatorio');
            const grupos = {
                profissional: document.getElementById('filtroProfissional'),
                dataInicio: document.getElementById('filtroDataInicio'),
                dataFim: document.getElementById('filtroDataFim'),
                mes: document.getElementById('filtroMes'),
                ano: document.getElementById('filtroAno'),
            };

            function alternarFiltros() {
                Object.values(grupos).forEach((elemento) => elemento.classList.add('hidden'));

                if (seletor.value === 'por_profissional') {
                    grupos.profissional.classList.remove('hidden');
                }

                if (seletor.value === 'por_periodo') {
                    grupos.dataInicio.classList.remove('hidden');
                    grupos.dataFim.classList.remove('hidden');
                }

                if (seletor.value === 'geral_mes') {
                    grupos.mes.classList.remove('hidden');
                    grupos.ano.classList.remove('hidden');
                }
            }

            seletor.addEventListener('change', alternarFiltros);
            alternarFiltros();
        });
    </script>
</x-psicologia-layout>
