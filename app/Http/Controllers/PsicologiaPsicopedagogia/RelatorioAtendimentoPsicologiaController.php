<?php

namespace App\Http\Controllers\PsicologiaPsicopedagogia;

use App\Http\Controllers\Controller;
use App\Models\Instituicao;
use App\Services\PsicossocialService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;

class RelatorioAtendimentoPsicologiaController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly PsicossocialService $psicossocialService
    ) {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:consultar relatorios tecnicos do psicossocial'),
        ];
    }

    public function index(Request $request)
    {
        $opcoesRelatorio = $this->psicossocialService->opcoesRelatorioAtendimentos($request->user());
        $camposDisponiveis = $opcoesRelatorio['campos'];
        $camposPadrao = ['data_agendada', 'nome_atendido', 'profissional_responsavel', 'tipo_atendimento', 'status'];

        $filtros = $request->validate([
            'tipo_relatorio' => ['nullable', Rule::in(array_keys($opcoesRelatorio['tipos_relatorio']))],
            'escola_id' => ['nullable', 'integer', Rule::exists('escolas', 'id')],
            'profissional_id' => ['nullable', 'integer', Rule::exists('funcionarios', 'id'), 'required_if:tipo_relatorio,por_profissional'],
            'data_inicio' => ['nullable', 'date', 'required_if:tipo_relatorio,por_periodo'],
            'data_fim' => ['nullable', 'date', 'required_if:tipo_relatorio,por_periodo', 'after_or_equal:data_inicio'],
            'mes' => ['nullable', 'integer', 'between:1,12', 'required_if:tipo_relatorio,geral_mes'],
            'ano' => ['nullable', 'integer', 'min:2000', 'max:2100'],
            'tipo_atendimento' => ['nullable', Rule::in(array_keys($opcoesRelatorio['tipos_atendimento']))],
            'status' => ['nullable', Rule::in(array_keys($opcoesRelatorio['status']))],
            'campos' => ['nullable', 'array'],
            'campos.*' => ['string', Rule::in(array_keys($camposDisponiveis))],
        ]);

        $gerouRelatorio = filled($filtros['tipo_relatorio'] ?? null);

        if (($filtros['tipo_relatorio'] ?? null) === 'geral_mes' && empty($filtros['ano'])) {
            $filtros['ano'] = now()->year;
        }

        $camposSelecionados = collect($filtros['campos'] ?? $camposPadrao)
            ->filter(fn (string $campo) => array_key_exists($campo, $camposDisponiveis))
            ->values()
            ->all();

        if ($camposSelecionados === []) {
            $camposSelecionados = $camposPadrao;
        }

        return view('psicologia-psicopedagogia.relatorios-atendimentos.index', [
            'instituicao' => Instituicao::query()->first(),
            'opcoesRelatorio' => $opcoesRelatorio,
            'filtros' => $filtros,
            'camposDisponiveis' => $camposDisponiveis,
            'camposSelecionados' => $camposSelecionados,
            'resultados' => $gerouRelatorio
                ? $this->psicossocialService->gerarRelatorioAtendimentos($request->user(), $filtros)
                : collect(),
            'gerouRelatorio' => $gerouRelatorio,
            'meses' => $this->meses(),
            'tituloPagina' => 'Gerador de relatorios',
            'subtituloPagina' => 'Filtros tecnicos para consolidar atendimentos e imprimir listagens restritas.',
            'breadcrumbs' => $this->psicossocialService->construirBreadcrumbs([
                ['label' => 'Gerador de relatorios'],
            ]),
        ]);
    }

    private function meses(): array
    {
        return [
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Marco',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro',
        ];
    }
}
