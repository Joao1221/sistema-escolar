<?php

namespace App\Services;

use App\Models\Alimento;
use App\Models\CardapioDiario;
use App\Models\CategoriaAlimento;
use App\Models\Escola;
use App\Models\FornecedorAlimento;
use App\Models\MovimentacaoAlimento;
use App\Models\Usuario;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AlimentacaoEscolarService
{
    public function escolasDisponiveis(Usuario $usuario): Collection
    {
        if ($this->usuarioPossuiEscopoAmplo($usuario)) {
            return Escola::query()->where('ativo', true)->orderBy('nome')->get();
        }

        return $usuario->escolas()->where('ativo', true)->orderBy('nome')->get();
    }

    public function garantirEscolaPermitida(Usuario $usuario, int $escolaId): void
    {
        if ($this->usuarioPossuiEscopoAmplo($usuario)) {
            $existe = Escola::query()->where('id', $escolaId)->where('ativo', true)->exists();

            if (! $existe) {
                abort(403, 'A escola informada nao esta disponivel para esta operacao.');
            }

            return;
        }

        $permitida = $usuario->escolas()->where('escolas.id', $escolaId)->exists();

        if (! $permitida) {
            abort(403, 'Acesso negado: esta operacao pertence a outra escola.');
        }
    }

    public function listarPainel(Usuario $usuario, ?int $escolaId = null): array
    {
        $escolas = $this->escolasDisponiveis($usuario);
        $escolaId = $this->resolverEscolaPadrao($escolas, $escolaId);

        $estoque = $this->consultarEstoque($usuario, $escolaId);
        $movimentacoesRecentes = $this->listarMovimentacoes($usuario, [
            'escola_id' => $escolaId,
        ], 8);

        $cardapios = CardapioDiario::query()
            ->with(['escola', 'itens.alimento'])
            ->where('escola_id', $escolaId)
            ->orderByDesc('data_cardapio')
            ->take(5)
            ->get();

        $validade = MovimentacaoAlimento::query()
            ->with('alimento')
            ->where('escola_id', $escolaId)
            ->where('tipo', 'entrada')
            ->whereNotNull('data_validade')
            ->orderBy('data_validade')
            ->take(8)
            ->get();

        return [
            'escolas' => $escolas,
            'escolaSelecionada' => $escolaId,
            'indicadores' => [
                'total_alimentos' => Alimento::query()->where('ativo', true)->count(),
                'total_categorias' => CategoriaAlimento::query()->where('ativo', true)->count(),
                'baixo_estoque' => $estoque->where('abaixo_minimo', true)->count(),
                'vencendo' => $validade->filter(function (MovimentacaoAlimento $movimentacao) {
                    return $movimentacao->data_validade && $movimentacao->data_validade->between(now()->startOfDay(), now()->addDays(30)->endOfDay());
                })->count(),
            ],
            'estoque' => $estoque,
            'movimentacoesRecentes' => $movimentacoesRecentes,
            'cardapiosRecentes' => $cardapios,
            'itensValidade' => $validade,
        ];
    }

    public function listarCategorias(): Collection
    {
        return CategoriaAlimento::query()->orderBy('nome')->get();
    }

    public function salvarCategoria(array $dados): CategoriaAlimento
    {
        return CategoriaAlimento::create($dados);
    }

    public function atualizarCategoria(CategoriaAlimento $categoria, array $dados): CategoriaAlimento
    {
        $categoria->update($dados);

        return $categoria->refresh();
    }

    public function listarFornecedores(): Collection
    {
        return FornecedorAlimento::query()->orderBy('nome')->get();
    }

    public function salvarFornecedor(array $dados): FornecedorAlimento
    {
        return FornecedorAlimento::create($dados);
    }

    public function atualizarFornecedor(FornecedorAlimento $fornecedor, array $dados): FornecedorAlimento
    {
        $fornecedor->update($dados);

        return $fornecedor->refresh();
    }

    public function listarAlimentos(): Collection
    {
        return Alimento::query()->with('categoria')->orderBy('nome')->get();
    }

    public function salvarAlimento(array $dados): Alimento
    {
        return Alimento::create($dados);
    }

    public function atualizarAlimento(Alimento $alimento, array $dados): Alimento
    {
        $alimento->update($dados);

        return $alimento->refresh();
    }

    public function listarMovimentacoes(Usuario $usuario, array $filtros = [], int $porPagina = 20): LengthAwarePaginator
    {
        $escolas = $this->escolasDisponiveis($usuario);
        $escolaIds = $escolas->pluck('id');

        $query = MovimentacaoAlimento::query()
            ->with(['escola', 'alimento', 'fornecedor', 'usuario'])
            ->whereIn('escola_id', $escolaIds);

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
            ->paginate($porPagina)
            ->withQueryString();
    }

    public function registrarMovimentacao(Usuario $usuario, array $dados): MovimentacaoAlimento
    {
        $this->garantirEscolaPermitida($usuario, (int) $dados['escola_id']);

        /** @var Alimento $alimento */
        $alimento = Alimento::query()->findOrFail($dados['alimento_id']);
        $saldoAtual = $this->saldoAtual((int) $dados['escola_id'], (int) $dados['alimento_id']);

        if ($dados['tipo'] === 'saida' && (float) $dados['quantidade'] > $saldoAtual) {
            throw ValidationException::withMessages([
                'quantidade' => 'Estoque insuficiente para esta saida.',
            ]);
        }

        if (
            $dados['tipo'] === 'entrada' &&
            $alimento->controla_validade &&
            empty($dados['data_validade'])
        ) {
            throw ValidationException::withMessages([
                'data_validade' => 'Informe a data de validade para este alimento.',
            ]);
        }

        $saldoResultante = $dados['tipo'] === 'entrada'
            ? $saldoAtual + (float) $dados['quantidade']
            : $saldoAtual - (float) $dados['quantidade'];

        return DB::transaction(function () use ($usuario, $dados, $saldoResultante) {
            return MovimentacaoAlimento::create([
                'escola_id' => $dados['escola_id'],
                'alimento_id' => $dados['alimento_id'],
                'fornecedor_alimento_id' => $dados['fornecedor_alimento_id'] ?? null,
                'usuario_id' => $usuario->id,
                'tipo' => $dados['tipo'],
                'quantidade' => $dados['quantidade'],
                'saldo_resultante' => $saldoResultante,
                'data_movimentacao' => $dados['data_movimentacao'],
                'data_validade' => $dados['data_validade'] ?? null,
                'lote' => $dados['lote'] ?? null,
                'valor_unitario' => $dados['valor_unitario'] ?? null,
                'observacoes' => $dados['observacoes'] ?? null,
            ]);
        });
    }

    public function consultarEstoque(Usuario $usuario, ?int $escolaId = null): Collection
    {
        $escolas = $this->escolasDisponiveis($usuario);
        $escolaId = $this->resolverEscolaPadrao($escolas, $escolaId);
        $this->garantirEscolaPermitida($usuario, $escolaId);

        $saldos = MovimentacaoAlimento::query()
            ->select('alimento_id')
            ->selectRaw("SUM(CASE WHEN tipo = 'entrada' THEN quantidade ELSE -quantidade END) as saldo")
            ->where('escola_id', $escolaId)
            ->groupBy('alimento_id');

        return Alimento::query()
            ->with('categoria')
            ->leftJoinSub($saldos, 'saldos', function ($join) {
                $join->on('alimentos.id', '=', 'saldos.alimento_id');
            })
            ->select('alimentos.*')
            ->selectRaw('COALESCE(saldos.saldo, 0) as saldo_atual')
            ->orderBy('alimentos.nome')
            ->get()
            ->map(function (Alimento $alimento) {
                $saldoAtual = (float) ($alimento->saldo_atual ?? 0);
                $alimento->saldo_atual = $saldoAtual;
                $alimento->abaixo_minimo = $saldoAtual <= (float) $alimento->estoque_minimo;

                return $alimento;
            });
    }

    public function listarCardapios(Usuario $usuario, array $filtros = []): LengthAwarePaginator
    {
        $escolas = $this->escolasDisponiveis($usuario);
        $escolaIds = $escolas->pluck('id');

        $query = CardapioDiario::query()
            ->with(['escola', 'usuario', 'itens.alimento'])
            ->whereIn('escola_id', $escolaIds);

        if (! empty($filtros['escola_id'])) {
            $query->where('escola_id', $filtros['escola_id']);
        }

        if (! empty($filtros['data_cardapio'])) {
            $query->whereDate('data_cardapio', $filtros['data_cardapio']);
        }

        return $query->orderByDesc('data_cardapio')->paginate(15)->withQueryString();
    }

    public function criarCardapio(Usuario $usuario, array $dados): CardapioDiario
    {
        $this->garantirEscolaPermitida($usuario, (int) $dados['escola_id']);
        $itens = $this->normalizarItens($dados['itens']);

        if (count($itens) === 0) {
            throw ValidationException::withMessages([
                'itens' => 'Informe ao menos um item no cardapio.',
            ]);
        }

        return DB::transaction(function () use ($usuario, $dados, $itens) {
            $cardapio = CardapioDiario::create([
                'escola_id' => $dados['escola_id'],
                'usuario_id' => $usuario->id,
                'data_cardapio' => $dados['data_cardapio'],
                'observacoes' => $dados['observacoes'] ?? null,
            ]);

            $cardapio->itens()->createMany($itens);

            return $cardapio->load(['escola', 'usuario', 'itens.alimento']);
        });
    }

    public function atualizarCardapio(Usuario $usuario, CardapioDiario $cardapio, array $dados): CardapioDiario
    {
        $this->garantirEscolaPermitida($usuario, $cardapio->escola_id);
        $itens = $this->normalizarItens($dados['itens']);

        if (count($itens) === 0) {
            throw ValidationException::withMessages([
                'itens' => 'Informe ao menos um item no cardapio.',
            ]);
        }

        return DB::transaction(function () use ($cardapio, $dados, $itens) {
            $cardapio->update([
                'data_cardapio' => $dados['data_cardapio'],
                'observacoes' => $dados['observacoes'] ?? null,
            ]);

            $cardapio->itens()->delete();
            $cardapio->itens()->createMany($itens);

            return $cardapio->load(['escola', 'usuario', 'itens.alimento']);
        });
    }

    public function opcoesFormulario(Usuario $usuario): array
    {
        return [
            'escolas' => $this->escolasDisponiveis($usuario),
            'categorias' => CategoriaAlimento::query()->where('ativo', true)->orderBy('nome')->get(),
            'fornecedores' => FornecedorAlimento::query()->where('ativo', true)->orderBy('nome')->get(),
            'alimentos' => Alimento::query()->with('categoria')->where('ativo', true)->orderBy('nome')->get(),
        ];
    }

    private function saldoAtual(int $escolaId, int $alimentoId): float
    {
        return (float) MovimentacaoAlimento::query()
            ->where('escola_id', $escolaId)
            ->where('alimento_id', $alimentoId)
            ->selectRaw("COALESCE(SUM(CASE WHEN tipo = 'entrada' THEN quantidade ELSE -quantidade END), 0) as saldo")
            ->value('saldo');
    }

    private function normalizarItens(array $itens): array
    {
        return collect($itens)
            ->filter(function (array $item) {
                return ! empty($item['alimento_id']) && ! empty($item['refeicao']);
            })
            ->map(function (array $item) {
                return [
                    'alimento_id' => $item['alimento_id'],
                    'refeicao' => $item['refeicao'],
                    'quantidade_prevista' => $item['quantidade_prevista'] ?? null,
                    'observacoes' => $item['observacoes'] ?? null,
                ];
            })
            ->values()
            ->all();
    }

    private function resolverEscolaPadrao(Collection $escolas, ?int $escolaId): int
    {
        if ($escolaId !== null) {
            return $escolaId;
        }

        $primeiraEscola = $escolas->first();

        if (! $primeiraEscola instanceof Escola) {
            abort(403, 'Usuario sem escola vinculada para operar alimentacao escolar.');
        }

        return $primeiraEscola->id;
    }

    private function usuarioPossuiEscopoAmplo(Usuario $usuario): bool
    {
        return $usuario->hasRole('Administrador da Rede') || $usuario->can('acessar portal da nutricionista');
    }
}
