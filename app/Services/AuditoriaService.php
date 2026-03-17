<?php

namespace App\Services;

use App\Models\Escola;
use App\Models\RegistroAuditoria;
use App\Models\Usuario;
use App\Support\AuditoriaModelos;
use App\Support\AuditoriaPortal;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class AuditoriaService
{
    public function registrarModelo(string $acao, Model $model, array $antes = [], array $depois = []): void
    {
        $configuracao = AuditoriaModelos::configuracao($model);

        if (! $configuracao) {
            return;
        }

        $camposCriticos = collect($configuracao['campos_criticos'] ?? [])
            ->reject(fn (string $campo) => in_array($campo, ['created_at', 'updated_at', 'deleted_at'], true))
            ->values()
            ->all();

        $valoresAntes = $this->extrairValores($antes, $camposCriticos);
        $valoresDepois = $this->extrairValores($depois, $camposCriticos);

        if ($acao === 'alteracao') {
            $camposAlterados = collect($valoresDepois)
                ->keys()
                ->filter(fn (string $campo) => Arr::get($valoresAntes, $campo) != Arr::get($valoresDepois, $campo))
                ->values()
                ->all();

            $valoresAntes = Arr::only($valoresAntes, $camposAlterados);
            $valoresDepois = Arr::only($valoresDepois, $camposAlterados);

            if ($valoresAntes === [] && $valoresDepois === []) {
                return;
            }
        }

        $this->registrarEvento([
            'usuario_id' => auth()->id(),
            'escola_id' => $this->resolverEscolaId($configuracao, $model),
            'modulo' => is_callable($configuracao['modulo']) ? $configuracao['modulo']($model) : $configuracao['modulo'],
            'acao' => $acao,
            'tipo_registro' => $configuracao['tipo_registro'],
            'registro_type' => $model::class,
            'registro_id' => $model->getKey(),
            'nivel_sensibilidade' => $configuracao['sensibilidade'],
            'valores_antes' => $valoresAntes ?: null,
            'valores_depois' => $valoresDepois ?: null,
            'contexto' => array_filter(array_merge(
                $this->contextoRequisicao(),
                is_callable($configuracao['resolver_contexto'] ?? null) ? ($configuracao['resolver_contexto'])($model) : []
            ), fn ($valor) => $valor !== null && $valor !== [] && $valor !== ''),
        ]);
    }

    public function registrarEvento(array $dados): RegistroAuditoria
    {
        return RegistroAuditoria::create([
            'usuario_id' => $dados['usuario_id'] ?? auth()->id(),
            'escola_id' => $dados['escola_id'] ?? null,
            'modulo' => $dados['modulo'],
            'acao' => $dados['acao'],
            'tipo_registro' => $dados['tipo_registro'],
            'registro_type' => $dados['registro_type'] ?? null,
            'registro_id' => $dados['registro_id'] ?? null,
            'nivel_sensibilidade' => $dados['nivel_sensibilidade'] ?? 'medio',
            'ip' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
            'valores_antes' => $dados['valores_antes'] ?? null,
            'valores_depois' => $dados['valores_depois'] ?? null,
            'contexto' => array_filter(array_merge(
                $this->contextoRequisicao(),
                $dados['contexto'] ?? []
            ), fn ($valor) => $valor !== null && $valor !== [] && $valor !== ''),
        ]);
    }

    public function registrarVisualizacaoSensivel(
        string $modulo,
        string $tipoRegistro,
        ?int $escolaId,
        ?Model $registro = null,
        array $contexto = []
    ): RegistroAuditoria {
        return $this->registrarEvento([
            'escola_id' => $escolaId,
            'modulo' => $modulo,
            'acao' => 'visualizacao_sensivel',
            'tipo_registro' => $tipoRegistro,
            'registro_type' => $registro ? $registro::class : null,
            'registro_id' => $registro?->getKey(),
            'nivel_sensibilidade' => 'sigiloso',
            'contexto' => $contexto,
        ]);
    }

    public function listarRegistros(string $portal, Usuario $usuario, array $filtros = [], int $paginacao = 20): LengthAwarePaginator
    {
        $query = RegistroAuditoria::query()
            ->with(['usuario', 'escola'])
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        $this->aplicarEscopoPortal($query, $portal, $usuario);
        $this->aplicarFiltros($query, $filtros);

        return $query->paginate($paginacao)->withQueryString();
    }

    public function opcoesFiltros(string $portal, Usuario $usuario): array
    {
        $query = RegistroAuditoria::query();
        $this->aplicarEscopoPortal($query, $portal, $usuario);

        $queryUsuarios = clone $query;
        $queryModulos = clone $query;
        $queryAcoes = clone $query;
        $queryTipos = clone $query;

        return [
            'escolas' => Escola::query()
                ->when($portal !== 'secretaria' && $portal !== 'nutricionista' && ! $usuario->hasRole('Administrador da Rede'), fn (Builder $builder) => $builder->whereIn('id', $usuario->escolas()->pluck('escolas.id')->all() ?: [0]))
                ->orderBy('nome')
                ->get(),
            'usuarios' => Usuario::query()
                ->whereIn('id', $queryUsuarios->select('usuario_id')->whereNotNull('usuario_id')->distinct()->pluck('usuario_id'))
                ->orderBy('name')
                ->get(),
            'modulos' => $queryModulos->select('modulo')->distinct()->pluck('modulo')->filter()->sort()->values(),
            'acoes' => $queryAcoes->select('acao')->distinct()->pluck('acao')->filter()->sort()->values(),
            'tiposRegistro' => $queryTipos->select('tipo_registro')->distinct()->pluck('tipo_registro')->filter()->sort()->values(),
            'sensibilidades' => collect(['baixo', 'medio', 'alto', 'sigiloso']),
        ];
    }

    public function metricas(string $portal, Usuario $usuario, array $filtros = []): array
    {
        $query = RegistroAuditoria::query();
        $this->aplicarEscopoPortal($query, $portal, $usuario);
        $this->aplicarFiltros($query, $filtros);

        return [
            'total' => (clone $query)->count(),
            'usuarios' => (clone $query)->whereNotNull('usuario_id')->distinct('usuario_id')->count('usuario_id'),
            'modulos' => (clone $query)->distinct('modulo')->count('modulo'),
            'eventos_criticos' => (clone $query)->whereIn('nivel_sensibilidade', ['alto', 'sigiloso'])->count(),
            'visualizacoes_sensiveis' => (clone $query)->where('acao', 'visualizacao_sensivel')->count(),
        ];
    }

    public function configuracaoPortal(string $portal): array
    {
        return AuditoriaPortal::configuracao($portal);
    }

    private function aplicarEscopoPortal(Builder $query, string $portal, Usuario $usuario): void
    {
        $configuracao = AuditoriaPortal::configuracao($portal);

        if (! empty($configuracao['modulos'])) {
            $query->whereIn('modulo', $configuracao['modulos']);
        }

        if (($configuracao['escopo'] ?? null) === 'escola' && ! $usuario->hasRole('Administrador da Rede')) {
            $query->whereIn('escola_id', $usuario->escolas()->pluck('escolas.id')->all() ?: [0]);
        }

        if ($portal === 'psicossocial') {
            $query->where('nivel_sensibilidade', 'sigiloso');
        }

        if ($portal === 'professor') {
            $funcionarioId = $usuario->resolverFuncionario()?->id;

            $query->where(function (Builder $builder) use ($usuario, $funcionarioId) {
                $builder->where('usuario_id', $usuario->id);

                if ($funcionarioId) {
                    $builder->orWhere('contexto->professor_id', $funcionarioId);
                }
            });
        }
    }

    private function aplicarFiltros(Builder $query, array $filtros): void
    {
        if (! empty($filtros['data_inicio'])) {
            $query->whereDate('created_at', '>=', $filtros['data_inicio']);
        }

        if (! empty($filtros['data_fim'])) {
            $query->whereDate('created_at', '<=', $filtros['data_fim']);
        }

        foreach (['usuario_id', 'escola_id', 'modulo', 'acao', 'tipo_registro', 'nivel_sensibilidade'] as $campo) {
            if (! empty($filtros[$campo])) {
                $query->where($campo, $filtros[$campo]);
            }
        }
    }

    private function extrairValores(array $origem, array $campos): array
    {
        return collect(Arr::only($origem, $campos))
            ->map(function ($valor) {
                if (is_array($valor)) {
                    return $valor;
                }

                if ($valor instanceof \DateTimeInterface) {
                    return $valor->format('Y-m-d H:i:s');
                }

                return $valor;
            })
            ->all();
    }

    private function resolverEscolaId(array $configuracao, Model $model): ?int
    {
        $resolver = $configuracao['resolver_escola'] ?? null;

        if (is_callable($resolver)) {
            return $resolver($model);
        }

        return null;
    }

    private function contextoRequisicao(): array
    {
        $request = request();

        if (! $request instanceof Request) {
            return [];
        }

        $rota = $request->route()?->getName();

        return [
            'rota' => $rota,
            'portal_origem' => AuditoriaPortal::portalPorRota($rota),
            'metodo_http' => $request->method(),
        ];
    }
}
