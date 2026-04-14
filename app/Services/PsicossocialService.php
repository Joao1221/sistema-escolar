<?php

namespace App\Services;

use App\Models\Aluno;
use App\Models\AtendidoExterno;
use App\Models\AtendimentoPsicossocial;
use App\Models\CasoDisciplinarSigiloso;
use App\Models\DemandaPsicossocial;
use App\Models\DevolutivaPsicossocial;
use App\Models\EncaminhamentoPsicossocial;
use App\Models\Escola;
use App\Models\Funcionario;
use App\Models\PlanoIntervencaoPsicossocial;
use App\Models\ReavaliacaoPsicossocial;
use App\Models\RelatorioTecnicoPsicossocial;
use App\Models\SessaoAtendimento;
use App\Models\TriagemPsicossocial;
use App\Models\Usuario;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PsicossocialService
{
    public function __construct(
        private readonly AuditoriaService $auditoriaService
    ) {}

    public function obterPainel(Usuario $usuario): array
    {
        $escolaIds = $this->escolaIdsPermitidas($usuario);
        $atendimentos = AtendimentoPsicossocial::query()
            ->with(['escola', 'atendivel', 'profissionalResponsavel'])
            ->visivelParaUsuario($usuario);
        $agendaHoje = (clone $atendimentos)
            ->whereDate('data_agendada', now()->toDateString())
            ->orderBy('data_agendada')
            ->get();

        $demandasAbertas = DemandaPsicossocial::query()
            ->whereIn('escola_id', $escolaIds)
            ->where('status', 'aberta')
            ->count();

        return [
            'totais' => [
                'demandas_abertas' => $demandasAbertas,
                'atendimentos_abertos' => (clone $atendimentos)->whereIn('status', ['agendado', 'em_acompanhamento'])->count(),
                'atendimentos_realizados' => (clone $atendimentos)->where('status', 'realizado')->count(),
                'planos_ativos' => PlanoIntervencaoPsicossocial::query()
                    ->whereHas('atendimento', fn ($query) => $query->visivelParaUsuario($usuario))
                    ->whereIn('status', ['ativo', 'em_acompanhamento'])
                    ->count(),
                'encaminhamentos_abertos' => EncaminhamentoPsicossocial::query()
                    ->whereHas('atendimento', fn ($query) => $query->visivelParaUsuario($usuario))
                    ->whereIn('status', ['emitido', 'em_acompanhamento'])
                    ->count(),
                'casos_abertos' => CasoDisciplinarSigiloso::query()
                    ->whereHas('atendimento', fn ($query) => $query->visivelParaUsuario($usuario))
                    ->whereIn('status', ['aberto', 'em_acompanhamento'])
                    ->count(),
                'relatorios_restritos' => RelatorioTecnicoPsicossocial::query()
                    ->whereHas('atendimento', fn ($query) => $query->visivelParaUsuario($usuario))
                    ->count(),
            ],
            'porPublico' => collect(['aluno', 'professor', 'funcionario', 'responsavel', 'coletivo'])
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
                ->whereHas('atendimento', fn ($query) => $query->visivelParaUsuario($usuario))
                ->latest('data_inicio')
                ->take(6)
                ->get(),
            'encaminhamentosRecentes' => EncaminhamentoPsicossocial::query()
                ->with('atendimento.atendivel')
                ->whereHas('atendimento', fn ($query) => $query->visivelParaUsuario($usuario))
                ->latest('data_encaminhamento')
                ->take(6)
                ->get(),
            'casosRecentes' => CasoDisciplinarSigiloso::query()
                ->with(['atendimento.atendivel'])
                ->whereHas('atendimento', fn ($query) => $query->visivelParaUsuario($usuario))
                ->latest('data_ocorrencia')
                ->take(6)
                ->get(),
            'relatoriosRecentes' => RelatorioTecnicoPsicossocial::query()
                ->whereHas('atendimento', fn ($query) => $query->visivelParaUsuario($usuario))
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
            // Historico deve trazer casos ja convertidos em atendimento, incluindo os agendados.
            $query->whereIn('status', ['agendado', 'em_acompanhamento', 'realizado', 'cancelado', 'faltou', 'encerrado']);
        }

        return $query->orderByDesc('data_agendada')->paginate(15)->withQueryString();
    }

    public function listarRelatorios(Usuario $usuario): Collection
    {
        return RelatorioTecnicoPsicossocial::query()
            ->with('atendimento.atendivel')
            ->whereHas('atendimento', fn ($query) => $query->visivelParaUsuario($usuario))
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
            ->whereHas('atendimento', fn ($query) => $query->visivelParaUsuario($usuario))
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
            ->whereHas('atendimento', fn ($query) => $query->visivelParaUsuario($usuario))
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
            ->whereHas('atendimento', fn ($query) => $query->visivelParaUsuario($usuario))
            ->when(! empty($filtros['escola_id']), fn ($query) => $query->where('escola_id', $filtros['escola_id']))
            ->when(! empty($filtros['status']), fn ($query) => $query->where('status', $filtros['status']))
            ->orderByDesc('data_ocorrencia')
            ->paginate(15)
            ->withQueryString();
    }

    public function listarRelatoriosTecnicos(Usuario $usuario, array $filtros = []): LengthAwarePaginator
    {
        return RelatorioTecnicoPsicossocial::query()
            ->with(['atendimento.atendivel', 'atendimento.escola', 'escola'])
            ->whereHas('atendimento', fn ($query) => $query->visivelParaUsuario($usuario))
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
            'profissionaisPsicossociais' => $this->profissionaisPsicossociaisPorEscolas($escolaIds),
            'responsaveis' => AtendidoExterno::query()
                ->whereIn('escola_id', $escolaIds)
                ->where('ativo', true)
                ->orderBy('nome')
                ->get(),
        ];
    }

    public function dadosEscola(Usuario $usuario, int $escolaId): array
    {
        $this->garantirEscolaPermitida($usuario, $escolaId);

        return [
            'alunos' => Aluno::query()
                ->whereHas('matriculas', fn ($query) => $query->where('escola_id', $escolaId)->where('status', 'ativa'))
                ->where('ativo', true)
                ->orderBy('nome_completo')
                ->get(['id', 'nome_completo']),
            'funcionarios' => Funcionario::query()
                ->whereHas('escolas', fn ($query) => $query->where('escolas.id', $escolaId))
                ->orderBy('nome')
                ->get(['id', 'nome']),
        ];
    }

    public function opcoesRelatorioAtendimentos(Usuario $usuario): array
    {
        $profissionalIds = AtendimentoPsicossocial::query()
            ->visivelParaUsuario($usuario)
            ->whereNotNull('profissional_responsavel_id')
            ->distinct()
            ->pluck('profissional_responsavel_id');

        return [
            'escolas' => $this->escolasDoUsuario($usuario),
            'profissionais' => Funcionario::query()
                ->whereIn('id', $profissionalIds)
                ->orderBy('nome')
                ->get(),
            'tipos_relatorio' => [
                'por_periodo' => 'Atendimentos por periodo',
                'por_profissional' => 'Atendimentos por profissional',
                'geral_mes' => 'Relatorio geral do mes',
            ],
            'tipos_atendimento' => [
                'psicologia' => 'Psicologia',
                'psicopedagogia' => 'Psicopedagogia',
                'psicossocial' => 'Psicossocial',
            ],
            'status' => [
                'agendado' => 'Agendado',
                'em_acompanhamento' => 'Em acompanhamento',
                'realizado' => 'Realizado',
                'faltou' => 'Faltou',
                'cancelado' => 'Cancelado',
                'encerrado' => 'Encerrado',
            ],
            'campos' => $this->camposRelatorioAtendimentos(),
        ];
    }

    public function camposRelatorioAtendimentos(): array
    {
        return [
            'data_agendada' => 'Data agendada',
            'data_realizacao' => 'Data de realizacao',
            'nome_atendido' => 'Atendido',
            'escola' => 'Escola',
            'tipo_publico' => 'Publico',
            'tipo_atendimento' => 'Area tecnica',
            'status' => 'Status',
            'profissional_responsavel' => 'Profissional',
            'local_atendimento' => 'Local',
            'motivo_demanda' => 'Motivo da demanda',
            'nivel_sigilo' => 'Nivel de sigilo',
            'requer_acompanhamento' => 'Requer acompanhamento',
        ];
    }

    public function gerarRelatorioAtendimentos(Usuario $usuario, array $filtros): Collection
    {
        return AtendimentoPsicossocial::query()
            ->with(['escola', 'atendivel', 'profissionalResponsavel'])
            ->visivelParaUsuario($usuario)
            ->when(! empty($filtros['escola_id']), fn (Builder $query) => $query->where('escola_id', $filtros['escola_id']))
            ->when(! empty($filtros['tipo_atendimento']), fn (Builder $query) => $query->where('tipo_atendimento', $filtros['tipo_atendimento']))
            ->when(! empty($filtros['status']), fn (Builder $query) => $query->where('status', $filtros['status']))
            ->when(($filtros['tipo_relatorio'] ?? null) === 'por_profissional' && ! empty($filtros['profissional_id']), fn (Builder $query) => $query->where('profissional_responsavel_id', $filtros['profissional_id']))
            ->when(($filtros['tipo_relatorio'] ?? null) === 'por_periodo' && ! empty($filtros['data_inicio']), fn (Builder $query) => $query->whereDate('data_agendada', '>=', $filtros['data_inicio']))
            ->when(($filtros['tipo_relatorio'] ?? null) === 'por_periodo' && ! empty($filtros['data_fim']), fn (Builder $query) => $query->whereDate('data_agendada', '<=', $filtros['data_fim']))
            ->when(($filtros['tipo_relatorio'] ?? null) === 'geral_mes' && ! empty($filtros['mes']), fn (Builder $query) => $query->whereMonth('data_agendada', $filtros['mes']))
            ->when(($filtros['tipo_relatorio'] ?? null) === 'geral_mes' && ! empty($filtros['ano']), fn (Builder $query) => $query->whereYear('data_agendada', $filtros['ano']))
            ->orderBy('data_agendada')
            ->get();
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
        $this->garantirAcessoAoAtendimento($usuario, $atendimento);

        return $atendimento->planosIntervencao()->create([
            ...$dados,
            'usuario_id' => $usuario->id,
        ]);
    }

    public function criarEncaminhamento(Usuario $usuario, AtendimentoPsicossocial $atendimento, array $dados): EncaminhamentoPsicossocial
    {
        $this->garantirAcessoAoAtendimento($usuario, $atendimento);

        return $atendimento->encaminhamentos()->create([
            ...$dados,
            'usuario_id' => $usuario->id,
        ]);
    }

    public function criarCasoDisciplinar(Usuario $usuario, AtendimentoPsicossocial $atendimento, array $dados): CasoDisciplinarSigiloso
    {
        $this->garantirAcessoAoAtendimento($usuario, $atendimento);

        return $atendimento->casosDisciplinares()->create([
            ...$dados,
            'escola_id' => $atendimento->escola_id,
            'usuario_id' => $usuario->id,
        ]);
    }

    public function criarRelatorio(Usuario $usuario, AtendimentoPsicossocial $atendimento, array $dados): RelatorioTecnicoPsicossocial
    {
        $this->garantirAcessoAoAtendimento($usuario, $atendimento);

        return $atendimento->relatoriosTecnicos()->create([
            ...$dados,
            'escola_id' => $atendimento->escola_id,
            'usuario_emissor_id' => $usuario->id,
        ]);
    }

    public function carregarRelatorioTecnico(Usuario $usuario, RelatorioTecnicoPsicossocial $relatorio): RelatorioTecnicoPsicossocial
    {
        $relatorio->loadMissing(['atendimento.escola', 'atendimento.atendivel', 'usuarioEmissor']);

        if (! $relatorio->atendimento) {
            abort(404, 'Relatorio tecnico sem atendimento vinculado.');
        }

        $this->garantirAcessoAoAtendimento($usuario, $relatorio->atendimento);

        return $relatorio;
    }

    public function atualizarRelatorio(Usuario $usuario, RelatorioTecnicoPsicossocial $relatorio, array $dados): RelatorioTecnicoPsicossocial
    {
        $relatorio = $this->carregarRelatorioTecnico($usuario, $relatorio);

        $relatorio->update($dados);

        return $relatorio->fresh(['atendimento.escola', 'atendimento.atendivel', 'usuarioEmissor']);
    }

    public function excluirRelatorio(Usuario $usuario, RelatorioTecnicoPsicossocial $relatorio): void
    {
        $relatorio = $this->carregarRelatorioTecnico($usuario, $relatorio);

        $relatorio->delete();
    }

    public function carregarAtendimento(Usuario $usuario, AtendimentoPsicossocial $atendimento): AtendimentoPsicossocial
    {
        $this->garantirAcessoAoAtendimento($usuario, $atendimento);

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

    private function baseAtendimentos(Usuario $usuario, array $filtros): Builder
    {
        $query = AtendimentoPsicossocial::query()
            ->with(['escola', 'atendivel', 'profissionalResponsavel'])
            ->visivelParaUsuario($usuario);

        if (! empty($filtros['escola_id'])) {
            $query->where('escola_id', $filtros['escola_id']);
        }

        if (! empty($filtros['status'])) {
            $query->where('status', $filtros['status']);
        }

        if (! empty($filtros['tipo_publico'])) {
            $query->where('tipo_publico', $filtros['tipo_publico']);
        }

        if ($usuario->possuiAcessoIrrestritoPsicossocial() && ! empty($filtros['profissional_id'])) {
            $query->where('profissional_responsavel_id', $filtros['profissional_id']);
        }

        if (! empty($filtros['nome'])) {
            $nome = $filtros['nome'];
            $query->where(function ($sub) use ($nome) {
                $sub->whereHasMorph('atendivel', [Aluno::class], function ($q) use ($nome) {
                    $q->where('nome_completo', 'like', "%{$nome}%");
                })->orWhereHasMorph('atendivel', [Funcionario::class], function ($q) use ($nome) {
                    $q->where('nome', 'like', "%{$nome}%");
                })->orWhereHasMorph('atendivel', [AtendidoExterno::class], function ($q) use ($nome) {
                    $q->where('nome', 'like', "%{$nome}%");
                });
            });
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
            'coletivo' => [AtendidoExterno::class, $this->criarAtendidoColetivo($dados)->id],
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

    private function criarAtendidoColetivo(array $dados): AtendidoExterno
    {
        // Mantem o relacionamento polimorfico sem exigir uma pessoa especifica no fluxo coletivo.
        return AtendidoExterno::create([
            'escola_id' => $dados['escola_id'],
            'aluno_id' => null,
            'nome' => 'Atendimento coletivo',
            'tipo_vinculo' => 'coletivo',
            'cpf' => null,
            'telefone' => null,
            'email' => null,
            'ativo' => false,
        ]);
    }

    public function listarDemandasa(Usuario $usuario, array $filtros = []): LengthAwarePaginator
    {
        $this->sincronizarDemandasPorStatusDoAtendimento();

        return DemandaPsicossocial::query()
            ->with(['escola', 'aluno', 'funcionario', 'profissionalResponsavel'])
            ->whereIn('escola_id', $this->escolaIdsPermitidas($usuario))
            ->whereNull('atendimento_id')
            ->when(! empty($filtros['escola_id']), fn ($query) => $query->where('escola_id', $filtros['escola_id']))
            ->when(! empty($filtros['status']), fn ($query) => $query->where('status', $filtros['status']))
            ->when(! empty($filtros['tipo_publico']), fn ($query) => $query->where('tipo_publico', $filtros['tipo_publico']))
            ->when(! empty($filtros['prioridade']), fn ($query) => $query->where('prioridade', $filtros['prioridade']))
            ->orderByDesc('data_solicitacao')
            ->paginate(15)
            ->withQueryString();
    }

    public function listarDemandasEscolares(Usuario $usuario, array $filtros = []): LengthAwarePaginator
    {
        $this->sincronizarDemandasPorStatusDoAtendimento();

        $destinatarios = $this->destinatariosEscolaParaUsuario($usuario);

        return DemandaPsicossocial::query()
            ->select('demandas_psicossociais.*')
            ->selectSub(
                DevolutivaPsicossocial::query()
                    ->selectRaw('count(*)')
                    ->whereColumn('devolutivas_psicossociais.atendimento_id', 'demandas_psicossociais.atendimento_id')
                    ->whereIn('destinatario', $destinatarios),
                'devolutivas_escolares_count'
            )
            ->with([
                'escola',
                'aluno',
                'funcionario',
                'usuarioRegistro',
                'atendimento' => $this->dadosBasicosAtendimentoParaEscola(),
            ])
            ->whereIn('escola_id', $this->escolaIdsPermitidas($usuario))
            ->where('aberta_pela_escola', true)
            ->when(! empty($filtros['escola_id']), fn ($query) => $query->where('escola_id', $filtros['escola_id']))
            ->when(! empty($filtros['status']), fn ($query) => $query->where('status', $filtros['status']))
            ->when(! empty($filtros['tipo_publico']), fn ($query) => $query->where('tipo_publico', $filtros['tipo_publico']))
            ->when(! empty($filtros['tipo_atendimento']), fn ($query) => $query->where('tipo_atendimento', $filtros['tipo_atendimento']))
            ->orderByDesc('data_solicitacao')
            ->paginate(15)
            ->withQueryString();
    }

    public function criarDemanda(Usuario $usuario, array $dados): DemandaPsicossocial
    {
        $this->garantirEscolaPermitida($usuario, (int) $dados['escola_id']);
        $dados = $this->normalizarDadosDemanda($dados);

        return DB::transaction(function () use ($usuario, $dados) {
            return DemandaPsicossocial::create([
                'escola_id' => $dados['escola_id'],
                'usuario_registro_id' => $usuario->id,
                'profissional_responsavel_id' => $dados['profissional_responsavel_id'] ?? null,
                'tipo_atendimento' => $dados['tipo_atendimento'] ?? 'psicologia',
                'origem_demanda' => $dados['origem_demanda'],
                'tipo_publico' => $dados['tipo_publico'],
                'aluno_id' => $dados['aluno_id'] ?? null,
                'funcionario_id' => $dados['funcionario_id'] ?? null,
                'responsavel_nome' => $dados['responsavel_nome'] ?? null,
                'responsavel_telefone' => $dados['responsavel_telefone'] ?? null,
                'responsavel_vinculo' => $dados['responsavel_vinculo'] ?? null,
                'motivo_inicial' => $dados['motivo_inicial'],
                'prioridade' => $dados['prioridade'] ?? 'media',
                'status' => 'aberta',
                'data_solicitacao' => $dados['data_solicitacao'] ?? now()->toDateString(),
                'observacoes' => $dados['observacoes'] ?? null,
                'aberta_pela_escola' => (bool) ($dados['aberta_pela_escola'] ?? false),
            ]);
        });
    }

    public function criarDemandaEscolar(Usuario $usuario, array $dados): DemandaPsicossocial
    {
        return $this->criarDemanda($usuario, [
            ...$dados,
            'origem_demanda' => $this->resolverOrigemDemandaEscolar($usuario),
            'aberta_pela_escola' => true,
        ]);
    }

    public function carregarDemanda(Usuario $usuario, DemandaPsicossocial $demanda): DemandaPsicossocial
    {
        $this->garantirEscolaPermitida($usuario, $demanda->escola_id);
        $this->sincronizarDemandaComAtendimento($demanda);
        $demanda->loadMissing('atendimento');

        if ($demanda->atendimento) {
            $this->garantirAcessoAoAtendimento($usuario, $demanda->atendimento);
        }

        return $demanda->load(['escola', 'aluno', 'funcionario', 'usuarioRegistro', 'profissionalResponsavel', 'atendimento']);
    }

    public function carregarDemandaEscolar(Usuario $usuario, DemandaPsicossocial $demanda): array
    {
        $this->garantirEscolaPermitida($usuario, $demanda->escola_id);
        abort_unless($demanda->aberta_pela_escola, 404);

        $this->sincronizarDemandaComAtendimento($demanda);

        $demanda->load([
            'escola',
            'aluno',
            'funcionario',
            'usuarioRegistro',
            'atendimento' => $this->dadosBasicosAtendimentoParaEscola(),
        ]);

        $devolutivas = collect();

        if ($demanda->atendimento_id) {
            $devolutivas = DevolutivaPsicossocial::query()
                ->with('usuarioResponsavel')
                ->where('atendimento_id', $demanda->atendimento_id)
                ->whereIn('destinatario', $this->destinatariosEscolaParaUsuario($usuario))
                ->latest('data_devolutiva')
                ->get();
        }

        return [
            'demanda' => $demanda,
            'devolutivas' => $devolutivas,
        ];
    }

    public function criarTriagem(Usuario $usuario, DemandaPsicossocial $demanda, array $dados): TriagemPsicossocial
    {
        $this->garantirEscolaPermitida($usuario, $demanda->escola_id);

        return DB::transaction(function () use ($usuario, $demanda, $dados) {
            $triagem = $demanda->triagem()->create([
                ...$dados,
                'usuario_triador_id' => $usuario->id,
                'data_triagem' => $dados['data_triagem'] ?? now()->toDateString(),
            ]);

            $demanda->update(['status' => 'em_triagem']);

            return $triagem;
        });
    }

    public function finalizarTriagem(Usuario $usuario, DemandaPsicossocial $demanda, array $dados): ?AtendimentoPsicossocial
    {
        $this->garantirEscolaPermitida($usuario, $demanda->escola_id);

        if ($dados['decisao'] !== 'iniciar_atendimento') {
            $demanda->update(['status' => $dados['decisao'] === 'encerrar_sem_atendimento' ? 'encerrada' : 'observacao']);

            return null;
        }

        $atendimento = $this->criarAtendimentoAPartirDeDemanda(
            $usuario,
            $demanda,
            $dados['profissional_responsavel_id'] ?? null
        );
        $demanda->update([
            'status' => 'em_atendimento',
            'profissional_responsavel_id' => $dados['profissional_responsavel_id'] ?? null,
            'encaminhado_para_atendimento' => true,
            'atendimento_id' => $atendimento->id,
        ]);

        return $atendimento;
    }

    private function criarAtendimentoAPartirDeDemanda(
        Usuario $usuario,
        DemandaPsicossocial $demanda,
        ?int $profissionalResponsavelId = null
    ): AtendimentoPsicossocial {
        $dados = [
            'escola_id' => $demanda->escola_id,
            'usuario_registro_id' => $usuario->id,
            'profissional_responsavel_id' => $profissionalResponsavelId ?? $demanda->profissional_responsavel_id,
            'tipo_publico' => $demanda->tipo_publico,
            'tipo_atendimento' => $demanda->tipo_atendimento,
            'natureza' => 'agendado',
            'status' => 'agendado',
            'data_agendada' => now(),
            'motivo_demanda' => $demanda->motivo_inicial,
            'nivel_sigilo' => $demanda->origem_demanda === 'familia' ? 'restrito' : 'normal',
            'requer_acompanhamento' => false,
        ];

        if ($demanda->tipo_publico === 'aluno' && $demanda->aluno_id) {
            $dados['aluno_id'] = $demanda->aluno_id;
        } elseif (in_array($demanda->tipo_publico, ['professor', 'funcionario']) && $demanda->funcionario_id) {
            $dados['funcionario_id'] = $demanda->funcionario_id;
        } elseif ($demanda->tipo_publico === 'responsavel') {
            $dados['responsavel_nome'] = $demanda->responsavel_nome;
            $dados['responsavel_tipo_vinculo'] = $demanda->responsavel_vinculo;
            $dados['responsavel_telefone'] = $demanda->responsavel_telefone;
        }

        return $this->criarAtendimento($usuario, $dados);
    }

    public function criarSessao(Usuario $usuario, AtendimentoPsicossocial $atendimento, array $dados): SessaoAtendimento
    {
        $this->garantirAcessoAoAtendimento($usuario, $atendimento);

        return DB::transaction(function () use ($usuario, $atendimento, $dados) {
            $sessao = $atendimento->sessoes()->create([
                ...$dados,
                'usuario_profissional_id' => $usuario->id,
                'funcionario_profissional_id' => $dados['funcionario_profissional_id'] ?? $usuario->resolverFuncionario()?->id,
            ]);

            if ($dados['status'] === 'realizado') {
                $atendimento->update([
                    'status' => 'em_acompanhamento',
                    'data_realizacao' => $dados['data_sessao'],
                ]);
            }

            return $sessao;
        });
    }

    public function criarDevolutiva(Usuario $usuario, AtendimentoPsicossocial $atendimento, array $dados): DevolutivaPsicossocial
    {
        $this->garantirAcessoAoAtendimento($usuario, $atendimento);

        return $atendimento->devolutivas()->create([
            ...$dados,
            'usuario_responsavel_id' => $usuario->id,
        ]);
    }

    public function criarReavaliacao(Usuario $usuario, AtendimentoPsicossocial $atendimento, array $dados): ReavaliacaoPsicossocial
    {
        $this->garantirAcessoAoAtendimento($usuario, $atendimento);

        return DB::transaction(function () use ($usuario, $atendimento, $dados) {
            $reavaliacao = $atendimento->reavaliacoes()->create([
                ...$dados,
                'usuario_responsavel_id' => $usuario->id,
            ]);

            if ($dados['decisao'] === 'encerrar') {
                $atendimento->update(['status' => 'encerrado']);
                $this->sincronizarDemandaComAtendimento($atendimento->demanda);
            }

            return $reavaliacao;
        });
    }

    public function encerrarAtendimento(Usuario $usuario, AtendimentoPsicossocial $atendimento, array $dados): AtendimentoPsicossocial
    {
        $this->garantirAcessoAoAtendimento($usuario, $atendimento);

        return DB::transaction(function () use ($atendimento, $dados) {
            $atendimento->update([
                'status' => 'encerrado',
                'data_encerramento' => $dados['data_encerramento'] ?? now()->toDateString(),
                'motivo_encerramento' => $dados['motivo_encerramento'] ?? null,
                'resumo_encerramento' => $dados['resumo_encerramento'] ?? null,
                'orientacoes_finais' => $dados['orientacoes_finais'] ?? null,
            ]);

            $this->sincronizarDemandaComAtendimento($atendimento->demanda);

            return $atendimento;
        });
    }

    private function sincronizarDemandasPorStatusDoAtendimento(): void
    {
        DemandaPsicossocial::query()
            ->where('status', 'em_atendimento')
            ->whereNotNull('atendimento_id')
            ->whereHas('atendimento', fn ($query) => $query->where('status', 'encerrado'))
            ->update(['status' => 'encerrada']);
    }

    private function sincronizarDemandaComAtendimento(?DemandaPsicossocial $demanda): void
    {
        if (! $demanda || ! $demanda->atendimento_id) {
            return;
        }

        $demanda->loadMissing('atendimento');

        if ($demanda->atendimento?->status === 'encerrado' && $demanda->status !== 'encerrada') {
            $demanda->update(['status' => 'encerrada']);
        }
    }

    private function garantirAcessoAoAtendimento(Usuario $usuario, AtendimentoPsicossocial $atendimento): void
    {
        if (! $atendimento->visivelParaUsuario($usuario)) {
            abort(403, 'Acesso negado a atendimento sigiloso de outro profissional.');
        }
    }

    private function normalizarDadosDemanda(array $dados): array
    {
        $tipoPublico = $dados['tipo_publico'];

        $dados['aluno_id'] = $tipoPublico === 'aluno'
            ? ($dados['aluno_id'] ?? null)
            : null;

        $dados['funcionario_id'] = in_array($tipoPublico, ['professor', 'funcionario'], true)
            ? ($dados['funcionario_id'] ?? null)
            : null;

        if ($tipoPublico !== 'responsavel') {
            $dados['responsavel_nome'] = null;
            $dados['responsavel_telefone'] = null;
            $dados['responsavel_vinculo'] = null;
        }

        return $dados;
    }

    private function profissionaisPsicossociaisPorEscolas(\Illuminate\Support\Collection $escolaIds): Collection
    {
        $usuariosPsicossociais = Usuario::query()
            ->role('Psicologia/Psicopedagogia')
            ->where(function (Builder $query) use ($escolaIds) {
                $query->whereHas('escolas', fn ($subquery) => $subquery->whereIn('escolas.id', $escolaIds))
                    ->orWhereHas('funcionario.escolas', fn ($subquery) => $subquery->whereIn('escolas.id', $escolaIds));
            })
            ->get(['funcionario_id', 'email']);

        $funcionarioIds = $usuariosPsicossociais
            ->pluck('funcionario_id')
            ->filter()
            ->unique()
            ->values();

        $emails = $usuariosPsicossociais
            ->pluck('email')
            ->filter()
            ->unique()
            ->values();

        if ($funcionarioIds->isEmpty() && $emails->isEmpty()) {
            return new Collection;
        }

        return Funcionario::query()
            ->whereHas('escolas', fn ($query) => $query->whereIn('escolas.id', $escolaIds))
            ->where(function (Builder $query) use ($funcionarioIds, $emails) {
                if ($funcionarioIds->isNotEmpty()) {
                    $query->whereIn('id', $funcionarioIds);
                }

                if ($emails->isNotEmpty()) {
                    $metodo = $funcionarioIds->isNotEmpty() ? 'orWhereIn' : 'whereIn';
                    $query->{$metodo}('email', $emails);
                }
            })
            ->orderBy('nome')
            ->get();
    }

    private function dadosBasicosAtendimentoParaEscola(): \Closure
    {
        return function ($query): void {
            $query->select([
                'id',
                'escola_id',
                'profissional_responsavel_id',
                'status',
                'data_agendada',
                'data_realizacao',
                'data_encerramento',
            ])->with('profissionalResponsavel:id,nome');
        };
    }

    private function destinatariosEscolaParaUsuario(Usuario $usuario): array
    {
        $destinatarios = [];

        if ($usuario->hasRole("Coordenador Pedag\u{00F3}gico")) {
            $destinatarios[] = 'coordenacao';
        }

        if ($usuario->hasRole('Diretor Escolar')) {
            $destinatarios[] = 'direcao';
        }

        return $destinatarios !== []
            ? $destinatarios
            : ['coordenacao', 'direcao'];
    }

    private function resolverOrigemDemandaEscolar(Usuario $usuario): string
    {
        if ($usuario->hasRole('Diretor Escolar')) {
            return 'direcao';
        }

        if ($usuario->hasRole("Coordenador Pedag\u{00F3}gico")) {
            return 'coordenacao';
        }

        abort(403, 'Perfil sem permissao para registrar demanda escolar.');
    }
}
