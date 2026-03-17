<?php

namespace App\Services;

use App\Models\Alimento;
use App\Models\CardapioDiario;
use App\Models\CategoriaAlimento;
use App\Models\Escola;
use App\Models\FornecedorAlimento;
use App\Models\MovimentacaoAlimento;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PortalNutricionistaService
{
    public function obterDadosDashboard(): array
    {
        $hoje = now()->toDateString();
        $limiteValidade = now()->addDays(30)->toDateString();
        $comparativoEscolas = $this->obterComparativoEscolas();

        return [
            'totais' => [
                'escolas_monitoradas' => Escola::query()->where('ativo', true)->count(),
                'alimentos_ativos' => Alimento::query()->where('ativo', true)->count(),
                'movimentacoes_mes' => MovimentacaoAlimento::query()->whereMonth('data_movimentacao', now()->month)->whereYear('data_movimentacao', now()->year)->count(),
                'validades_criticas' => MovimentacaoAlimento::query()
                    ->where('tipo', 'entrada')
                    ->whereBetween('data_validade', [$hoje, $limiteValidade])
                    ->count(),
            ],
            'comparativoEscolas' => $comparativoEscolas,
            'cardapiosRecentes' => CardapioDiario::query()
                ->with(['escola', 'itens.alimento'])
                ->orderByDesc('data_cardapio')
                ->take(6)
                ->get(),
            'topSaidas' => MovimentacaoAlimento::query()
                ->select('alimento_id')
                ->selectRaw("SUM(CASE WHEN tipo = 'saida' THEN quantidade ELSE 0 END) as total_saida")
                ->with('alimento')
                ->groupBy('alimento_id')
                ->orderByDesc('total_saida')
                ->take(5)
                ->get(),
            'alertasValidade' => $this->obterAlertasValidade()->take(6),
        ];
    }

    public function listarAlimentos(): Collection
    {
        return Alimento::query()
            ->with('categoria')
            ->orderBy('nome')
            ->get();
    }

    public function listarCategorias(): Collection
    {
        return CategoriaAlimento::query()
            ->withCount('alimentos')
            ->orderBy('nome')
            ->get();
    }

    public function listarFornecedores(): Collection
    {
        return FornecedorAlimento::query()
            ->withCount('movimentacoes')
            ->orderBy('nome')
            ->get();
    }

    public function listarCardapios(array $filtros = []): LengthAwarePaginator
    {
        $query = CardapioDiario::query()
            ->with(['escola', 'usuario', 'itens.alimento']);

        if (! empty($filtros['escola_id'])) {
            $query->where('escola_id', $filtros['escola_id']);
        }

        if (! empty($filtros['data_cardapio'])) {
            $query->whereDate('data_cardapio', $filtros['data_cardapio']);
        }

        return $query->orderByDesc('data_cardapio')->paginate(12)->withQueryString();
    }

    public function listarMovimentacoes(array $filtros = []): LengthAwarePaginator
    {
        $query = MovimentacaoAlimento::query()
            ->with(['escola', 'alimento.categoria', 'fornecedor', 'usuario']);

        if (! empty($filtros['escola_id'])) {
            $query->where('escola_id', $filtros['escola_id']);
        }

        if (! empty($filtros['tipo'])) {
            $query->where('tipo', $filtros['tipo']);
        }

        if (! empty($filtros['alimento_id'])) {
            $query->where('alimento_id', $filtros['alimento_id']);
        }

        return $query
            ->orderByDesc('data_movimentacao')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();
    }

    public function listarEstoqueComparativo(array $filtros = []): Collection
    {
        $query = MovimentacaoAlimento::query()
            ->select('escola_id', 'alimento_id')
            ->selectRaw("SUM(CASE WHEN tipo = 'entrada' THEN quantidade ELSE -quantidade END) as saldo_atual")
            ->with(['escola', 'alimento.categoria'])
            ->groupBy('escola_id', 'alimento_id');

        if (! empty($filtros['escola_id'])) {
            $query->where('escola_id', $filtros['escola_id']);
        }

        if (! empty($filtros['alimento_id'])) {
            $query->where('alimento_id', $filtros['alimento_id']);
        }

        return $query
            ->get()
            ->map(function (MovimentacaoAlimento $movimentacao) {
                $movimentacao->abaixo_minimo = (float) $movimentacao->saldo_atual <= (float) ($movimentacao->alimento?->estoque_minimo ?? 0);

                return $movimentacao;
            })
            ->sortBy(fn (MovimentacaoAlimento $movimentacao) => ($movimentacao->escola?->nome ?? '') . ($movimentacao->alimento?->nome ?? ''))
            ->values();
    }

    public function obterAlertasValidade(): Collection
    {
        return MovimentacaoAlimento::query()
            ->with(['escola', 'alimento'])
            ->where('tipo', 'entrada')
            ->whereNotNull('data_validade')
            ->orderBy('data_validade')
            ->get();
    }

    public function obterRelatoriosGerenciais(): array
    {
        return [
            'comparativoEscolas' => $this->obterComparativoEscolas(),
            'topSaidas' => MovimentacaoAlimento::query()
                ->select('escola_id', 'alimento_id')
                ->selectRaw("SUM(CASE WHEN tipo = 'saida' THEN quantidade ELSE 0 END) as total_saida")
                ->with(['escola', 'alimento'])
                ->groupBy('escola_id', 'alimento_id')
                ->orderByDesc('total_saida')
                ->take(10)
                ->get(),
            'validadesCriticas' => $this->obterAlertasValidade()
                ->filter(fn (MovimentacaoAlimento $movimentacao) => $movimentacao->data_validade && $movimentacao->data_validade->between(now()->startOfDay(), now()->addDays(30)->endOfDay()))
                ->values(),
        ];
    }

    public function obterOpcoesFiltros(): array
    {
        return [
            'escolas' => Escola::query()->where('ativo', true)->orderBy('nome')->get(),
            'alimentos' => Alimento::query()->where('ativo', true)->orderBy('nome')->get(),
        ];
    }

    public function construirBreadcrumbs(array $itens): array
    {
        $breadcrumbs = [
            [
                'label' => 'Portal da Nutricionista',
                'url' => route('nutricionista.dashboard'),
            ],
        ];

        foreach ($itens as $item) {
            $breadcrumbs[] = $item;
        }

        return $breadcrumbs;
    }

    private function obterComparativoEscolas(): Collection
    {
        return Escola::query()
            ->where('ativo', true)
            ->orderBy('nome')
            ->get()
            ->map(function (Escola $escola) {
                $entradas = (float) MovimentacaoAlimento::query()
                    ->where('escola_id', $escola->id)
                    ->where('tipo', 'entrada')
                    ->sum('quantidade');

                $saidas = (float) MovimentacaoAlimento::query()
                    ->where('escola_id', $escola->id)
                    ->where('tipo', 'saida')
                    ->sum('quantidade');

                $saldos = MovimentacaoAlimento::query()
                    ->select('alimento_id')
                    ->selectRaw("SUM(CASE WHEN tipo = 'entrada' THEN quantidade ELSE -quantidade END) as saldo")
                    ->where('escola_id', $escola->id)
                    ->groupBy('alimento_id')
                    ->get()
                    ->keyBy('alimento_id');

                $baixoEstoque = Alimento::query()
                    ->where('ativo', true)
                    ->get()
                    ->filter(function (Alimento $alimento) use ($saldos) {
                        $saldo = (float) ($saldos->get($alimento->id)?->saldo ?? 0);

                        return $saldo <= (float) $alimento->estoque_minimo;
                    })
                    ->count();

                $cardapioMaisRecente = CardapioDiario::query()
                    ->where('escola_id', $escola->id)
                    ->latest('data_cardapio')
                    ->first();

                $validadesCriticas = MovimentacaoAlimento::query()
                    ->where('escola_id', $escola->id)
                    ->where('tipo', 'entrada')
                    ->whereBetween('data_validade', [now()->toDateString(), now()->addDays(30)->toDateString()])
                    ->count();

                return (object) [
                    'escola' => $escola,
                    'entradas' => $entradas,
                    'saidas' => $saidas,
                    'saldo_operacional' => $entradas - $saidas,
                    'baixo_estoque' => $baixoEstoque,
                    'validades_criticas' => $validadesCriticas,
                    'cardapio_recente' => $cardapioMaisRecente?->data_cardapio,
                ];
            });
    }
}
