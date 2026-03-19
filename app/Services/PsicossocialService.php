<?php

namespace App\Services;

use App\Models\Aluno;
use App\Models\AtendidoExterno;
use App\Models\AtendimentoPsicossocial;
use App\Models\CasoDisciplinarSigiloso;
use App\Models\EncaminhamentoPsicossocial;
use App\Models\Escola;
use App\Models\Funcionario;
use App\Models\RelatorioTecnicoPsicossocial;
use App\Models\PlanoIntervencaoPsicossocial;
use App\Models\Usuario;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PsicossocialService
{
    public function __construct(
        private readonly AuditoriaService $auditoriaService
    ) {
    }

    public function obterPainel(Usuario $usuario): array
    {
        $escolaIds = $this->escolaIdsPermitidas($usuario);
        $atendimentos = AtendimentoPsicossocial::query()
            ->with(['escola', 'atendivel', 'profissionalResponsavel'])
            ->whereIn('escola_id', $escolaIds);
        $agendaHoje = (clone $atendimentos)
            ->whereDate('data_agendada', now()->toDateString())
            ->orderBy('data_agendada')
            ->get();

        return [
            'totais' => [
                'agendados_hoje' => $agendaHoje->count(),
                'atendimentos_abertos' => (clone $atendimentos)->whereIn('status', ['agendado', 'em_acompanhamento'])->count(),
                'atendimentos_realizados' => (clone $atendimentos)->where('status', 'realizado')->count(),
                'planos_ativos' => PlanoIntervencaoPsicossocial::query()
                    ->whereHas('atendimento', fn ($query) => $query->whereIn('escola_id', $escolaIds))
                    ->whereIn('status', ['ativo', 'em_acompanhamento'])
                    ->count(),
                'encaminhamentos_abertos' => EncaminhamentoPsicossocial::query()
                    ->whereHas('atendimento', fn ($query) => $query->whereIn('escola_id', $escolaIds))
                    ->whereIn('status', ['emitido', 'em_acompanhamento'])
                    ->count(),
                'casos_abertos' => CasoDisciplinarSigiloso::query()
                    ->whereIn('escola_id', $escolaIds)
                    ->whereIn('status', ['aberto', 'em_acompanhamento'])
                    ->count(),
                'relatorios_restritos' => RelatorioTecnicoPsicossocial::query()
                    ->whereIn('escola_id', $escolaIds)
                    ->count(),
            ],
            'porPublico' => collect(['aluno', 'professor', 'funcionario', 'responsavel'])
                ->mapWithKeys(fn (string $tipo) => [
                    $tipo => (clone $atendimentos)->where('tipo_publico', $tipo)->count(),
                ])
                ->all(),
            'agendaHoje' => $agendaHoje,
            'atendimentosRecentes' => (clone $atendimentos)
                ->latest('data_agendada')
                ->take(8)
                ->get(),
            'planosRecentes' => PlanoIntervencaoPsicossocial::query()
                ->with('atendimento.atendivel')
                ->whereHas('atendimento', fn ($query) => $query->whereIn('escola_id', $escolaIds))
                ->latest('data_inicio')
                ->take(6)
                ->get(),
            'encaminhamentosRecentes' => EncaminhamentoPsicossocial::query()
                ->with('atendimento.atendivel')
                ->whereHas('atendimento', fn ($query) => $query->whereIn('escola_id', $escolaIds))
                ->latest('data_encaminhamento')
                ->take(6)
                ->get(),
            'casosRecentes' => CasoDisciplinarSigiloso::query()
                ->with(['atendimento.atendivel'])
                ->whereIn('escola_id', $escolaIds)
                ->latest('data_ocorrencia')
                ->take(6)
                ->get(),
            'relatoriosRecentes' => RelatorioTecnicoPsicossocial::query()
                ->whereIn('escola_id', $escolaIds)
                ->latest('data_emissao')
                ->take(6)
                ->get(),
        ];
    }

    public function listarAgenda(Usuario $usuario, array $filtros = []): LengthAwarePaginator
    {
        $query = $this->baseAtendimentos($usuario, $filtros)
            ->where('status', 'agendado');

        return $query
            ->orderBy('data_agendada')
            ->paginate(15)
            ->withQueryString();
    }

    public function listarHistorico(Usuario $usuario, array $filtros = []): LengthAwarePaginator
    {
        $query = $this->baseAtendimentos($usuario, $filtros);

        if (empty($filtros['status'])) {
            $query->whereIn('status', ['realizado', 'cancelado', 'faltou']);
        }

        return $query->orderByDesc('data_agendada')->paginate(15)->withQueryString();
    }

    public function listarRelatorios(Usuario $usuario): Collection
    {
        $escolaIds = $this->escolaIdsPermitidas($usuario);

        return RelatorioTecnicoPsicossocial::query()
            ->with('atendimento.atendivel')
            ->whereIn('escola_id', $escolaIds)
            ->latest('data_emissao')
            ->get();
    }

    public function listarAtendimentos(Usuario $usuario, array $filtros = []): LengthAwarePaginator
    {
        $query = $this->baseAtendimentos($usuario, $filtros)
            ->where('status', 'realizado');

        return $query
            ->orderByDesc('data_agendada')
            ->paginate(15)
            ->withQueryString();
    }

    public function listarPlanos(Usuario $usuario, array $filtros = []): LengthAwarePaginator
    {
        return PlanoIntervencaoPsicossocial::query()
            ->with(['atendimento.atendivel', 'atendimento.escola'])
            ->whereHas('atendimento', fn ($query) => $query->whereIn('escola_id', $this->escolaIdsPermitidas($usuario)))
            ->when(! empty($filtros['escola_id']), fn ($query) => $query->whereHas('atendimento', fn ($subquery) => $subquery->where('escola_id', $filtros['escola_id'])))
            ->when(! empty($filtros['status']), fn ($query) => $query->where('status', $filtros['status']))
            ->orderByDesc('data_inicio')
            ->paginate(15)
            ->withQueryString();
    }

    public function listarEncaminhamentos(Usuario $usuario, array $filtros = []): LengthAwarePaginator
    {
        return EncaminhamentoPsicossocial::query()
            ->with(['atendimento.atendivel', 'atendimento.escola'])
            ->whereHas('atendimento', fn ($query) => $query->whereIn('escola_id', $this->escolaIdsPermitidas($usuario)))
            ->when(! empty($filtros['escola_id']), fn ($query) => $query->whereHas('atendimento', fn ($subquery) => $subquery->where('escola_id', $filtros['escola_id'])))
            ->when(! empty($filtros['tipo']), fn ($query) => $query->where('tipo', $filtros['tipo']))
            ->when(! empty($filtros['status']), fn ($query) => $query->where('status', $filtros['status']))
            ->orderByDesc('data_encaminhamento')
            ->paginate(15)
            ->withQueryString();
    }

    public function listarCasos(Usuario $usuario, array $filtros = []): LengthAwarePaginator
    {
        return CasoDisciplinarSigiloso::query()
            ->with(['atendimento.atendivel', 'escola'])
            ->whereIn('escola_id', $this->escolaIdsPermitidas($usuario))
            ->when(! empty($filtros['escola_id']), fn ($query) => $query->where('escola_id', $filtros['escola_id']))
            ->when(! empty($filtros['status']), fn ($query) => $query->where('status', $filtros['status']))
            ->orderByDesc('data_ocorrencia')
            ->paginate(15)
            ->withQueryString();
    }

    public function listarRelatoriosTecnicos(Usuario $usuario, array $filtros = []): LengthAwarePaginator
    {
        return RelatorioTecnicoPsicossocial::query()
            ->with(['atendimento.atendivel', 'atendimento.escola'])
            ->whereIn('escola_id', $this->escolaIdsPermitidas($usuario))
            ->when(! empty($filtros['escola_id']), fn ($query) => $query->where('escola_id', $filtros['escola_id']))
            ->when(! empty($filtros['tipo_relatorio']), fn ($query) => $query->where('tipo_relatorio', $filtros['tipo_relatorio']))
            ->orderByDesc('data_emissao')
            ->paginate(15)
            ->withQueryString();
    }

    public function opcoesFormulario(Usuario $usuario): array
    {
        $escolas = $this->escolasDoUsuario($usuario);
        $escolaIds = $escolas->pluck('id');

        return [
            'escolas' => $escolas,
            'alunos' => Aluno::query()->where('ativo', true)->orderBy('nome_completo')->get(),
            'funcionarios' => Funcionario::query()
                ->whereHas('escolas', fn ($query) => $query->whereIn('escolas.id', $escolaIds))
                ->orderBy('nome')
                ->get(),
            'responsaveis' => AtendidoExterno::query()
                ->whereIn('escola_id', $escolaIds)
                ->where('ativo', true)
                ->orderBy('nome')
                ->get(),
        ];
    }

    public function criarAtendimento(Usuario $usuario, array $dados): AtendimentoPsicossocial
    {
        $this->garantirEscolaPermitida($usuario, (int) $dados['escola_id']);

        return DB::transaction(function () use ($usuario, $dados) {
            [$atendivelType, $atendivelId] = $this->resolverAtendivel($dados);

            return AtendimentoPsicossocial::create([
                'escola_id' => $dados['escola_id'],
                'usuario_registro_id' => $usuario->id,
                'profissional_responsavel_id' => $dados['profissional_responsavel_id'] ?? $usuario->resolverFuncionario()?->id,
                'atendivel_type' => $atendivelType,
                'atendivel_id' => $atendivelId,
                'tipo_publico' => $dados['tipo_publico'],
                'tipo_atendimento' => $dados['tipo_atendimento'],
                'natureza' => $dados['natureza'],
                'status' => $dados['status'],
                'data_agendada' => $dados['data_agendada'],
                'data_realizacao' => $dados['data_realizacao'] ?? null,
                'local_atendimento' => $dados['local_atendimento'] ?? null,
                'motivo_demanda' => $dados['motivo_demanda'],
                'resumo_sigiloso' => $dados['resumo_sigiloso'] ?? null,
                'observacoes_restritas' => $dados['observacoes_restritas'] ?? null,
                'nivel_sigilo' => $dados['nivel_sigilo'],
                'requer_acompanhamento' => (bool) ($dados['requer_acompanhamento'] ?? false),
            ]);
        });
    }

    public function criarPlano(Usuario $usuario, AtendimentoPsicossocial $atendimento, array $dados): PlanoIntervencaoPsicossocial
    {
        $this->garantirEscolaPermitida($usuario, $atendimento->escola_id);

        return $atendimento->planosIntervencao()->create([
            ...$dados,
            'usuario_id' => $usuario->id,
        ]);
    }

    public function criarEncaminhamento(Usuario $usuario, AtendimentoPsicossocial $atendimento, array $dados): EncaminhamentoPsicossocial
    {
        $this->garantirEscolaPermitida($usuario, $atendimento->escola_id);

        return $atendimento->encaminhamentos()->create([
            ...$dados,
            'usuario_id' => $usuario->id,
        ]);
    }

    public function criarCasoDisciplinar(Usuario $usuario, AtendimentoPsicossocial $atendimento, array $dados): CasoDisciplinarSigiloso
    {
        $this->garantirEscolaPermitida($usuario, $atendimento->escola_id);

        return $atendimento->casosDisciplinares()->create([
            ...$dados,
            'escola_id' => $atendimento->escola_id,
            'usuario_id' => $usuario->id,
        ]);
    }

    public function criarRelatorio(Usuario $usuario, AtendimentoPsicossocial $atendimento, array $dados): RelatorioTecnicoPsicossocial
    {
        $this->garantirEscolaPermitida($usuario, $atendimento->escola_id);

        return $atendimento->relatoriosTecnicos()->create([
            ...$dados,
            'escola_id' => $atendimento->escola_id,
            'usuario_emissor_id' => $usuario->id,
        ]);
    }

    public function carregarAtendimento(Usuario $usuario, AtendimentoPsicossocial $atendimento): AtendimentoPsicossocial
    {
        $this->garantirEscolaPermitida($usuario, $atendimento->escola_id);

        $this->auditoriaService->registrarVisualizacaoSensivel(
            'psicossocial',
            'Atendimento Psicossocial',
            $atendimento->escola_id,
            $atendimento,
            [
                'atendimento_id' => $atendimento->id,
                'tipo_publico' => $atendimento->tipo_publico,
                'nivel_sigilo' => $atendimento->nivel_sigilo,
            ]
        );

        return $atendimento->load([
            'escola',
            'usuarioRegistro',
            'profissionalResponsavel',
            'atendivel',
            'planosIntervencao',
            'encaminhamentos',
            'casosDisciplinares',
            'relatoriosTecnicos',
        ]);
    }

    public function garantirEscolaPermitida(Usuario $usuario, int $escolaId): void
    {
        $permitida = $this->escolaIdsPermitidas($usuario)->contains($escolaId);

        if (! $permitida) {
            abort(403, 'Acesso negado a dados sigilosos de outra escola.');
        }
    }

    private function baseAtendimentos(Usuario $usuario, array $filtros)
    {
        $escolaIds = $this->escolaIdsPermitidas($usuario);

        $query = AtendimentoPsicossocial::query()
            ->with(['escola', 'atendivel', 'profissionalResponsavel'])
            ->whereIn('escola_id', $escolaIds);

        if (! empty($filtros['escola_id'])) {
            $query->where('escola_id', $filtros['escola_id']);
        }

        if (! empty($filtros['status'])) {
            $query->where('status', $filtros['status']);
        }

        if (! empty($filtros['tipo_publico'])) {
            $query->where('tipo_publico', $filtros['tipo_publico']);
        }

        return $query;
    }

    public function construirBreadcrumbs(array $itens = []): array
    {
        $breadcrumbs = [
            [
                'label' => 'Portal da Psicologia',
                'url' => route('psicologia.dashboard'),
            ],
        ];

        foreach ($itens as $item) {
            $breadcrumbs[] = $item;
        }

        return $breadcrumbs;
    }

    private function escolaIdsPermitidas(Usuario $usuario): \Illuminate\Support\Collection
    {
        if ($usuario->acessaPortalPsicossocial()) {
            return Escola::query()->pluck('id');
        }

        return $usuario->escolas()->pluck('escolas.id');
    }

    private function escolasDoUsuario(Usuario $usuario): Collection
    {
        if ($usuario->acessaPortalPsicossocial()) {
            return Escola::query()
                ->where('ativo', true)
                ->orderBy('nome')
                ->get();
        }

        return $usuario->escolas()->where('ativo', true)->orderBy('nome')->get();
    }

    private function resolverAtendivel(array $dados): array
    {
        return match ($dados['tipo_publico']) {
            'aluno' => [Aluno::class, (int) $dados['aluno_id']],
            'professor', 'funcionario' => [Funcionario::class, (int) $dados['funcionario_id']],
            'responsavel' => [AtendidoExterno::class, $this->resolverResponsavelExterno($dados)->id],
        };
    }

    private function resolverResponsavelExterno(array $dados): Model
    {
        if (! empty($dados['responsavel_existente_id'])) {
            return AtendidoExterno::query()->findOrFail($dados['responsavel_existente_id']);
        }

        return AtendidoExterno::create([
            'escola_id' => $dados['escola_id'],
            'aluno_id' => $dados['aluno_id'] ?? null,
            'nome' => $dados['responsavel_nome'],
            'tipo_vinculo' => $dados['responsavel_tipo_vinculo'],
            'cpf' => $dados['responsavel_cpf'] ?? null,
            'telefone' => $dados['responsavel_telefone'] ?? null,
            'email' => $dados['responsavel_email'] ?? null,
            'ativo' => true,
        ]);
    }
}
