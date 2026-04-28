<?php

namespace App\Services;

use App\Models\AtendimentoPsicossocial;
use App\Models\CardapioDiario;
use App\Models\Escola;
use App\Models\Funcionario;
use App\Models\HorarioAula;
use App\Models\Instituicao;
use App\Models\LancamentoAvaliativo;
use App\Models\Matricula;
use App\Models\MatriculaHistorico;
use App\Models\ModalidadeEnsino;
use App\Models\MovimentacaoAlimento;
use App\Models\RelatorioTecnicoPsicossocial;
use App\Models\Turma;
use App\Models\Usuario;
use App\Support\ArquivoPublicoUrl;
use App\Support\RelatoriosPortal;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RelatorioPortalService
{
    public function relatoriosDisponiveis(string $portal, Usuario $usuario): array
    {
        return collect(RelatoriosPortal::relatoriosDoPortal($portal))
            ->map(function (array $definicao, string $tipo) use ($usuario) {
                return [...$definicao, 'tipo' => $tipo, 'habilitado' => $usuario->can($definicao['permissao'])];
            })
            ->filter(fn (array $definicao) => $definicao['habilitado'])
            ->values()
            ->all();
    }

    public function opcoesFormulario(string $portal, Usuario $usuario): array
    {
        $escolas = $this->escolasDoPortal($portal, $usuario);
        $escolaIds = $escolas->pluck('id')->all();

        return [
            'escolas' => $escolas,
            'turmas' => Turma::query()
                ->with('escola')
                ->when($portal !== 'secretaria' && $portal !== 'nutricionista', fn (Builder $query) => $query->whereIn('escola_id', $escolaIds ?: [0]))
                ->when($portal === 'secretaria' || $portal === 'nutricionista', fn (Builder $query) => $query)
                ->orderByDesc('ano_letivo')
                ->orderBy('nome')
                ->get(),
            'modalidades' => ModalidadeEnsino::query()->where('ativo', true)->orderBy('nome')->get(),
            'matriculas' => Matricula::query()
                ->with(['aluno', 'turma', 'escola'])
                ->when($portal !== 'secretaria' && $portal !== 'nutricionista', fn (Builder $query) => $query->whereIn('escola_id', $escolaIds ?: [0]))
                ->orderByDesc('ano_letivo')
                ->orderByDesc('id')
                ->limit(200)
                ->get(),
            'professores' => Funcionario::query()
                ->where('cargo', 'like', '%Professor%')
                ->orderBy('nome')
                ->get(),
        ];
    }

    public function gerar(string $portal, string $tipo, Usuario $usuario, array $filtros): array
    {
        $definicao = RelatoriosPortal::definicao($portal, $tipo);

        if (! $definicao) {
            throw new HttpException(404, 'Relatorio nao encontrado para este portal.');
        }

        if (! $usuario->can($definicao['permissao'])) {
            throw new HttpException(403, 'Voce nao possui permissao para emitir este relatorio.');
        }

        return match ($tipo) {
            'institucional-rede' => $this->relatorioInstitucionalRede($portal, $filtros),
            'alunos-matriculados-rede', 'alunos-matriculados-escola' => $this->relatorioAlunosMatriculados($portal, $usuario, $filtros),
            'situacao-matriculas-rede', 'situacao-matriculas-escola' => $this->relatorioSituacaoMatriculas($portal, $usuario, $filtros),
            'alunos-aee-rede', 'alunos-aee', 'alunos-somente-aee', 'alunos-matricula-dupla' => $this->relatorioAee($portal, $tipo, $usuario, $filtros),
            'quantitativo-matriculas-rede', 'quantitativo-matriculas' => $this->relatorioQuantitativoMatriculas($portal, $usuario, $filtros),
            'mapa-turmas-rede', 'mapa-turmas' => $this->relatorioMapaTurmas($portal, $usuario, $filtros),
            'professores-turma-disciplina-rede', 'professores-turma-disciplina' => $this->relatorioProfessoresTurma($portal, $usuario, $filtros),
            'auditoria-rede' => $this->relatorioAuditoriaRede($portal, $usuario, $filtros),
            'frequencia-consolidada' => $this->relatorioFrequenciaConsolidada($portal, $usuario, $filtros),
            'historico-escolar' => $this->relatorioHistoricoEscolar($portal, $usuario, $filtros),
            'ficha-individual', 'ficha-individual-pedagogica' => $this->relatorioFichaIndividual($portal, $usuario, $filtros),
            'cardapio-escola', 'cardapio-por-escola' => $this->relatorioCardapio($portal, $usuario, $filtros),
            'entrada-saida-alimentos' => $this->relatorioMovimentacaoAlimentos($portal, $usuario, $filtros),
            'estoque-validade' => $this->relatorioEstoqueValidade($portal, $usuario, $filtros),
            'comparativo-consumo' => $this->relatorioComparativoConsumo($portal, $filtros),
            'administrativo-escolar', 'administrativo-direcao' => $this->relatorioAdministrativo($portal, $usuario, $filtros),
            'notas-conceitos-consulta' => $this->relatorioNotasConceitos($portal, $usuario, $filtros),
            'pedagogico-coordenacao', 'pedagogico-direcao' => $this->relatorioPedagogico($portal, $usuario, $filtros),
            'tecnico-psicossocial' => $this->relatorioTecnicoPsicossocial($portal, $usuario, $filtros),
            default => throw new HttpException(404, 'Relatorio nao implementado.'),
        };
    }

    private function relatorioInstitucionalRede(string $portal, array $filtros): array
    {
        $instituicao = Instituicao::query()->first();
        $escolas = Escola::query()->where('ativo', true)->orderBy('nome')->get();
        $anoLetivo = $filtros['ano_letivo'] ?? now()->year;

        return $this->baseRelatorio(
            $portal,
            'Relatorio Institucional da Rede',
            'Panorama institucional e gerencial consolidado da rede municipal.',
            null,
            [
                'Ano letivo' => $anoLetivo,
                'Escolas ativas' => $escolas->count(),
                'Municipio' => trim(($instituicao?->municipio ?? '') . ' / ' . ($instituicao?->uf ?? '')),
            ],
            [
                [
                    'titulo' => 'Dados institucionais',
                    'tipo' => 'lista',
                    'itens' => [
                        ['label' => 'Prefeitura', 'valor' => $instituicao?->nome_prefeitura ?: 'Nao informado'],
                        ['label' => 'Secretaria', 'valor' => $instituicao?->nome_secretaria ?: 'Nao informado'],
                        ['label' => 'Secretario(a)', 'valor' => $instituicao?->nome_secretario ?: 'Nao informado'],
                        ['label' => 'Telefone', 'valor' => $instituicao?->telefone ?: 'Nao informado'],
                    ],
                ],
                [
                    'titulo' => 'Escolas monitoradas',
                    'tipo' => 'tabela',
                    'colunas' => ['Escola', 'Cidade', 'UF', 'Gestor'],
                    'linhas' => $escolas->map(fn (Escola $escola) => [
                        $escola->nome,
                        $escola->cidade ?: '-',
                        $escola->uf ?: '-',
                        $escola->nome_gestor ?: '-',
                    ])->all(),
                ],
            ],
            ['ano_letivo' => $anoLetivo]
        );
    }

    private function relatorioAlunosMatriculados(string $portal, Usuario $usuario, array $filtros): array
    {
        $matriculas = $this->baseMatriculas($portal, $usuario, $filtros)
            ->with(['aluno', 'escola', 'turma.modalidade'])
            ->get();

        return $this->baseRelatorio(
            $portal,
            'Alunos Matriculados',
            'Listagem de alunos matriculados por escola, turma e ano letivo.',
            $this->resolverEscolaDoRelatorio($portal, $usuario, $filtros),
            [
                'Total de matriculas' => $matriculas->count(),
                'Alunos unicos' => $matriculas->pluck('aluno_id')->unique()->count(),
                'Turmas envolvidas' => $matriculas->pluck('turma_id')->filter()->unique()->count(),
            ],
            [[
                'titulo' => 'Matriculas encontradas',
                'tipo' => 'tabela',
                'colunas' => ['Aluno', 'Escola', 'Turma', 'Modalidade', 'Tipo', 'Status', 'Ano'],
                'linhas' => $matriculas->map(fn (Matricula $matricula) => [
                    $matricula->aluno?->nome_completo ?? '-',
                    $matricula->escola?->nome ?? '-',
                    $matricula->turma?->nome ?? '-',
                    $matricula->turma?->modalidade?->nome ?? '-',
                    Str::upper($matricula->tipo),
                    Str::title($matricula->status),
                    $matricula->ano_letivo,
                ])->all(),
            ]],
            $filtros
        );
    }

    private function relatorioSituacaoMatriculas(string $portal, Usuario $usuario, array $filtros): array
    {
        $matriculas = $this->baseMatriculas($portal, $usuario, $filtros)
            ->with(['aluno', 'escola', 'turma'])
            ->get();

        $historicos = MatriculaHistorico::query()
            ->whereIn('matricula_id', $matriculas->pluck('id'))
            ->get();

        $aprovados = $historicos->where('acao', 'aprovacao')->count();
        $reprovados = $historicos->where('acao', 'reprovacao')->count();

        return $this->baseRelatorio(
            $portal,
            'Situacao de Matriculas',
            'Transferidos, desistentes, aprovados e reprovados conforme a base disponivel.',
            $this->resolverEscolaDoRelatorio($portal, $usuario, $filtros),
            [
                'Transferidas' => $matriculas->where('status', 'transferida')->count(),
                'Desistentes' => $matriculas->where('status', 'desistente')->count(),
                'Aprovadas' => $aprovados,
                'Reprovadas' => $reprovados,
            ],
            [[
                'titulo' => 'Resumo por matricula',
                'tipo' => 'tabela',
                'colunas' => ['Aluno', 'Turma', 'Status atual', 'Ultima movimentacao'],
                'linhas' => $matriculas->map(function (Matricula $matricula) use ($historicos) {
                    $ultimaAcao = $historicos->where('matricula_id', $matricula->id)->sortByDesc('created_at')->first();

                    return [
                        $matricula->aluno?->nome_completo ?? '-',
                        $matricula->turma?->nome ?? '-',
                        Str::title($matricula->status),
                        $ultimaAcao ? Str::title(str_replace('_', ' ', $ultimaAcao->acao)) : 'Sem historico',
                    ];
                })->all(),
            ]],
            $filtros
        );
    }

    private function relatorioAee(string $portal, string $tipo, Usuario $usuario, array $filtros): array
    {
        $matriculas = $this->baseMatriculas($portal, $usuario, $filtros)
            ->with(['aluno', 'escola', 'turma'])
            ->get();

        $aee = $matriculas->where('tipo', 'aee');
        $somenteAee = $aee->filter(fn (Matricula $matricula) => ! $matriculas->contains(fn (Matricula $outra) => $outra->aluno_id === $matricula->aluno_id && $outra->tipo === 'regular'));
        $dupla = $aee->filter(fn (Matricula $matricula) => $matriculas->contains(fn (Matricula $outra) => $outra->aluno_id === $matricula->aluno_id && $outra->tipo === 'regular'));

        $colecao = match ($tipo) {
            'alunos-somente-aee' => $somenteAee,
            'alunos-matricula-dupla' => $dupla,
            default => $aee,
        };

        return $this->baseRelatorio(
            $portal,
            'Relatorio AEE',
            'Identificacao de alunos com atendimento educacional especializado.',
            $this->resolverEscolaDoRelatorio($portal, $usuario, $filtros),
            [
                'Matriculas AEE' => $aee->count(),
                'Somente AEE' => $somenteAee->count(),
                'Matricula dupla' => $dupla->count(),
            ],
            [[
                'titulo' => 'Registros encontrados',
                'tipo' => 'tabela',
                'colunas' => ['Aluno', 'Escola', 'Turma', 'Tipo de vinculo', 'Ano'],
                'linhas' => $colecao->map(function (Matricula $matricula) use ($matriculas) {
                    $possuiRegular = $matriculas->contains(fn (Matricula $outra) => $outra->aluno_id === $matricula->aluno_id && $outra->tipo === 'regular');

                    return [
                        $matricula->aluno?->nome_completo ?? '-',
                        $matricula->escola?->nome ?? '-',
                        $matricula->turma?->nome ?? '-',
                        $possuiRegular ? 'Regular + AEE' : 'Somente AEE',
                        $matricula->ano_letivo,
                    ];
                })->values()->all(),
            ]],
            $filtros
        );
    }

    private function relatorioQuantitativoMatriculas(string $portal, Usuario $usuario, array $filtros): array
    {
        $matriculas = $this->baseMatriculas($portal, $usuario, $filtros)
            ->with(['escola', 'turma'])
            ->get();
        $duplas = $matriculas->where('tipo', 'aee')
            ->filter(fn (Matricula $matricula) => $matriculas->contains(fn (Matricula $outra) => $outra->aluno_id === $matricula->aluno_id && $outra->tipo === 'regular'));

        return $this->baseRelatorio(
            $portal,
            'Quantitativo de Matriculas',
            'Consolidado de matriculas regulares, AEE e duplas.',
            $this->resolverEscolaDoRelatorio($portal, $usuario, $filtros),
            [
                'Matriculas regulares' => $matriculas->where('tipo', 'regular')->count(),
                'Matriculas AEE' => $matriculas->where('tipo', 'aee')->count(),
                'Matriculas duplas' => $duplas->count(),
                'Total geral' => $matriculas->count(),
            ],
            [[
                'titulo' => 'Consolidado por escola',
                'tipo' => 'tabela',
                'colunas' => ['Escola', 'Regulares', 'AEE', 'Duplas', 'Total'],
                'linhas' => $matriculas->groupBy('escola_id')->map(function (Collection $grupo) {
                    $duplas = $grupo->where('tipo', 'aee')
                        ->filter(fn (Matricula $matricula) => $grupo->contains(fn (Matricula $outra) => $outra->aluno_id === $matricula->aluno_id && $outra->tipo === 'regular'))
                        ->count();

                    return [
                        $grupo->first()?->escola?->nome ?? '-',
                        $grupo->where('tipo', 'regular')->count(),
                        $grupo->where('tipo', 'aee')->count(),
                        $duplas,
                        $grupo->count(),
                    ];
                })->values()->all(),
            ]],
            $filtros
        );
    }

    private function relatorioMapaTurmas(string $portal, Usuario $usuario, array $filtros): array
    {
        $turmas = $this->baseTurmas($portal, $usuario, $filtros)
            ->with(['escola', 'modalidade', 'matriculas'])
            ->get();

        return $this->baseRelatorio(
            $portal,
            'Mapa de Turmas',
            'Quadro de turmas, vagas e ocupacao.',
            $this->resolverEscolaDoRelatorio($portal, $usuario, $filtros),
            [
                'Turmas encontradas' => $turmas->count(),
                'Vagas totais' => $turmas->sum(fn (Turma $turma) => (int) $turma->vagas),
                'Matriculas ativas' => $turmas->sum(fn (Turma $turma) => $turma->matriculas->where('status', 'ativa')->count()),
            ],
            [[
                'titulo' => 'Quadro de turmas',
                'tipo' => 'tabela',
                'colunas' => ['Escola', 'Turma', 'Modalidade', 'Turno', 'Ano', 'Vagas', 'Ocupacao'],
                'linhas' => $turmas->map(fn (Turma $turma) => [
                    $turma->escola?->nome ?? '-',
                    $turma->nome,
                    $turma->modalidade?->nome ?? '-',
                    Str::title($turma->turno),
                    $turma->ano_letivo,
                    $turma->vagas ?: 0,
                    $turma->matriculas->where('status', 'ativa')->count(),
                ])->all(),
            ]],
            $filtros
        );
    }

    private function relatorioProfessoresTurma(string $portal, Usuario $usuario, array $filtros): array
    {
        $horarios = $this->baseHorarios($portal, $usuario, $filtros)
            ->with(['escola', 'turma', 'disciplina', 'professor'])
            ->get();

        return $this->baseRelatorio(
            $portal,
            'Professores por Turma e Disciplina',
            'Mapa de atribuicao docente por turma e componente curricular.',
            $this->resolverEscolaDoRelatorio($portal, $usuario, $filtros),
            [
                'Horarios vinculados' => $horarios->count(),
                'Professores distintos' => $horarios->pluck('professor_id')->filter()->unique()->count(),
                'Turmas distintas' => $horarios->pluck('turma_id')->filter()->unique()->count(),
            ],
            [[
                'titulo' => 'Atribuicao docente',
                'tipo' => 'tabela',
                'colunas' => ['Escola', 'Turma', 'Disciplina', 'Professor', 'Dia', 'Horario'],
                'linhas' => $horarios->map(fn (HorarioAula $horario) => [
                    $horario->escola?->nome ?? '-',
                    $horario->turma?->nome ?? '-',
                    $horario->disciplina?->nome ?? '-',
                    $horario->professor?->nome ?? '-',
                    $this->rotuloDiaSemana((int) $horario->dia_semana),
                    trim(substr((string) $horario->horario_inicial, 0, 5) . ' - ' . substr((string) $horario->horario_final, 0, 5)),
                ])->all(),
            ]],
            $filtros
        );
    }

    private function relatorioAuditoriaRede(string $portal, Usuario $usuario, array $filtros): array
    {
        $movimentos = MatriculaHistorico::query()
            ->with(['matricula.aluno', 'matricula.escola', 'usuario'])
            ->when(! empty($filtros['escola_id']), fn (Builder $query) => $query->whereHas('matricula', fn (Builder $subquery) => $subquery->where('escola_id', $filtros['escola_id'])))
            ->when(! empty($filtros['data_inicio']), fn (Builder $query) => $query->whereDate('created_at', '>=', $filtros['data_inicio']))
            ->when(! empty($filtros['data_fim']), fn (Builder $query) => $query->whereDate('created_at', '<=', $filtros['data_fim']))
            ->orderByDesc('created_at')
            ->limit(150)
            ->get();

        return $this->baseRelatorio(
            $portal,
            'Relatorio de Auditoria',
            'Historico de movimentacoes relevantes ja registradas no sistema.',
            null,
            [
                'Eventos encontrados' => $movimentos->count(),
                'Usuarios distintos' => $movimentos->pluck('usuario_id')->filter()->unique()->count(),
                'Escolas envolvidas' => $movimentos->pluck('matricula.escola_id')->filter()->unique()->count(),
            ],
            [[
                'titulo' => 'Eventos recentes',
                'tipo' => 'tabela',
                'colunas' => ['Data', 'Escola', 'Aluno', 'Acao', 'Usuario'],
                'linhas' => $movimentos->map(fn (MatriculaHistorico $movimento) => [
                    optional($movimento->created_at)->format('d/m/Y H:i') ?: '-',
                    $movimento->matricula?->escola?->nome ?? '-',
                    $movimento->matricula?->aluno?->nome_completo ?? '-',
                    Str::title(str_replace('_', ' ', $movimento->acao)),
                    $movimento->usuario?->name ?? '-',
                ])->all(),
            ]],
            $filtros
        );
    }

    private function relatorioFrequenciaConsolidada(string $portal, Usuario $usuario, array $filtros): array
    {
        $matriculas = $this->baseMatriculas($portal, $usuario, $filtros)
            ->with(['aluno', 'turma', 'frequenciasAula.registroAula'])
            ->get();

        return $this->baseRelatorio(
            $portal,
            'Frequencia Consolidada',
            'Consolidado de presencas e faltas por aluno.',
            $this->resolverEscolaDoRelatorio($portal, $usuario, $filtros),
            [
                'Matriculas analisadas' => $matriculas->count(),
                'Presencas' => $matriculas->sum(fn (Matricula $matricula) => $matricula->frequenciasAula->where('situacao', 'presenca')->count()),
                'Faltas' => $matriculas->sum(fn (Matricula $matricula) => $matricula->frequenciasAula->whereIn('situacao', ['falta', 'falta_justificada'])->count()),
            ],
            [[
                'titulo' => 'Resumo por aluno',
                'tipo' => 'tabela',
                'colunas' => ['Aluno', 'Turma', 'Presencas', 'Faltas', 'Percentual'],
                'linhas' => $matriculas->map(function (Matricula $matricula) {
                    $total = $matricula->frequenciasAula->count();
                    $presencas = $matricula->frequenciasAula->where('situacao', 'presenca')->count();
                    $faltas = $matricula->frequenciasAula->whereIn('situacao', ['falta', 'falta_justificada'])->count();
                    $percentual = $total > 0 ? number_format(($presencas / $total) * 100, 1, ',', '.') . '%' : '-';

                    return [
                        $matricula->aluno?->nome_completo ?? '-',
                        $matricula->turma?->nome ?? '-',
                        $presencas,
                        $faltas,
                        $percentual,
                    ];
                })->all(),
            ]],
            $filtros
        );
    }

    private function relatorioHistoricoEscolar(string $portal, Usuario $usuario, array $filtros): array
    {
        $matricula = $this->carregarMatricula($portal, $usuario, (int) $filtros['matricula_id']);
        $matricula->loadMissing(['aluno', 'escola', 'turma.modalidade', 'historico.usuario', 'lancamentosAvaliativos']);

        return $this->baseRelatorio(
            $portal,
            'Historico Escolar',
            'Historico escolar com base na matricula selecionada e nas movimentacoes registradas.',
            $matricula->escola,
            [
                'Aluno' => $matricula->aluno?->nome_completo ?? '-',
                'Turma' => $matricula->turma?->nome ?? '-',
                'Ano letivo' => $matricula->ano_letivo,
                'Status atual' => Str::title($matricula->status),
            ],
            [
                [
                    'titulo' => 'Dados da matricula',
                    'tipo' => 'lista',
                    'itens' => [
                        ['label' => 'Modalidade', 'valor' => $matricula->turma?->modalidade?->nome ?? '-'],
                        ['label' => 'Tipo de matricula', 'valor' => Str::upper($matricula->tipo)],
                        ['label' => 'Data da matricula', 'valor' => optional($matricula->data_matricula)->format('d/m/Y') ?: '-'],
                        ['label' => 'Data de encerramento', 'valor' => optional($matricula->data_encerramento)->format('d/m/Y') ?: '-'],
                    ],
                ],
                [
                    'titulo' => 'Movimentacoes registradas',
                    'tipo' => 'tabela',
                    'colunas' => ['Data', 'Acao', 'Descricao', 'Usuario'],
                    'linhas' => $matricula->historico->sortBy('created_at')->map(fn (MatriculaHistorico $item) => [
                        optional($item->created_at)->format('d/m/Y H:i') ?: '-',
                        Str::title(str_replace('_', ' ', $item->acao)),
                        $item->descricao ?: '-',
                        $item->usuario?->name ?? '-',
                    ])->values()->all(),
                ],
            ],
            $filtros
        );
    }

    private function relatorioFichaIndividual(string $portal, Usuario $usuario, array $filtros): array
    {
        $matricula = $this->carregarMatricula($portal, $usuario, (int) $filtros['matricula_id']);
        $matricula->loadMissing(['aluno', 'escola', 'turma.modalidade', 'lancamentosAvaliativos', 'frequenciasAula']);

        $presencas = $matricula->frequenciasAula->where('situacao', 'presenca')->count();
        $faltas = $matricula->frequenciasAula->whereIn('situacao', ['falta', 'falta_justificada'])->count();

        return $this->baseRelatorio(
            $portal,
            'Ficha Individual',
            'Ficha consolidada do aluno no contexto da matricula selecionada.',
            $matricula->escola,
            [
                'Aluno' => $matricula->aluno?->nome_completo ?? '-',
                'RGM' => $matricula->aluno?->rgm ?? '-',
                'Turma' => $matricula->turma?->nome ?? '-',
                'Ano letivo' => $matricula->ano_letivo,
            ],
            [
                [
                    'titulo' => 'Dados cadastrais',
                    'tipo' => 'lista',
                    'itens' => [
                        ['label' => 'Nascimento', 'valor' => optional($matricula->aluno?->data_nascimento)->format('d/m/Y') ?: '-'],
                        ['label' => 'Responsavel', 'valor' => $matricula->aluno?->responsavel_nome ?: '-'],
                        ['label' => 'Telefone', 'valor' => $matricula->aluno?->responsavel_telefone ?: '-'],
                        ['label' => 'Endereco', 'valor' => trim(($matricula->aluno?->logradouro ?? '') . ', ' . ($matricula->aluno?->numero ?? '')) ?: '-'],
                    ],
                ],
                [
                    'titulo' => 'Indicadores escolares',
                    'tipo' => 'lista',
                    'itens' => [
                        ['label' => 'Presencas', 'valor' => $presencas],
                        ['label' => 'Faltas', 'valor' => $faltas],
                        ['label' => 'Avaliacoes lancadas', 'valor' => $matricula->lancamentosAvaliativos->count()],
                        ['label' => 'Status da matricula', 'valor' => Str::title($matricula->status)],
                    ],
                ],
            ],
            $filtros
        );
    }

    private function relatorioCardapio(string $portal, Usuario $usuario, array $filtros): array
    {
        $cardapios = CardapioDiario::query()
            ->with(['escola', 'itens.alimento'])
            ->when(! empty($filtros['escola_id']), fn (Builder $query) => $query->where('escola_id', $filtros['escola_id']))
            ->when($portal !== 'secretaria' && $portal !== 'nutricionista', fn (Builder $query) => $query->whereIn('escola_id', $this->resolverEscolaIds($usuario, $portal)))
            ->when(! empty($filtros['data_inicio']), fn (Builder $query) => $query->whereDate('data_cardapio', '>=', $filtros['data_inicio']))
            ->when(! empty($filtros['data_fim']), fn (Builder $query) => $query->whereDate('data_cardapio', '<=', $filtros['data_fim']))
            ->orderByDesc('data_cardapio')
            ->get();

        return $this->baseRelatorio(
            $portal,
            'Cardapio por Escola',
            'Relacao de cardapios diarios lancados.',
            $this->resolverEscolaDoRelatorio($portal, $usuario, $filtros),
            [
                'Cardapios encontrados' => $cardapios->count(),
                'Escolas com cardapio' => $cardapios->pluck('escola_id')->filter()->unique()->count(),
                'Itens servidos' => $cardapios->sum(fn (CardapioDiario $cardapio) => $cardapio->itens->count()),
            ],
            [[
                'titulo' => 'Lancamentos de cardapio',
                'tipo' => 'tabela',
                'colunas' => ['Data', 'Escola', 'Itens', 'Observacoes'],
                'linhas' => $cardapios->map(fn (CardapioDiario $cardapio) => [
                    optional($cardapio->data_cardapio)->format('d/m/Y') ?: '-',
                    $cardapio->escola?->nome ?? '-',
                    $cardapio->itens->pluck('alimento.nome')->filter()->implode(', '),
                    $cardapio->observacoes ?: '-',
                ])->all(),
            ]],
            $filtros
        );
    }

    private function relatorioMovimentacaoAlimentos(string $portal, Usuario $usuario, array $filtros): array
    {
        $movimentacoes = $this->baseMovimentacoesAlimentos($portal, $usuario, $filtros)
            ->with(['escola', 'alimento', 'fornecedor'])
            ->get();

        return $this->baseRelatorio(
            $portal,
            'Entrada e Saida de Alimentos',
            'Relatorio das movimentacoes de alimentacao escolar.',
            $this->resolverEscolaDoRelatorio($portal, $usuario, $filtros),
            [
                'Entradas' => number_format((float) $movimentacoes->where('tipo', 'entrada')->sum('quantidade'), 3, ',', '.'),
                'Saidas' => number_format((float) $movimentacoes->where('tipo', 'saida')->sum('quantidade'), 3, ',', '.'),
                'Movimentacoes' => $movimentacoes->count(),
            ],
            [[
                'titulo' => 'Movimentacoes registradas',
                'tipo' => 'tabela',
                'colunas' => ['Data', 'Escola', 'Alimento', 'Tipo', 'Quantidade', 'Saldo', 'Validade'],
                'linhas' => $movimentacoes->map(fn (MovimentacaoAlimento $movimentacao) => [
                    optional($movimentacao->data_movimentacao)->format('d/m/Y') ?: '-',
                    $movimentacao->escola?->nome ?? '-',
                    $movimentacao->alimento?->nome ?? '-',
                    Str::title($movimentacao->tipo),
                    number_format((float) $movimentacao->quantidade, 3, ',', '.'),
                    number_format((float) $movimentacao->saldo_resultante, 3, ',', '.'),
                    optional($movimentacao->data_validade)->format('d/m/Y') ?: '-',
                ])->all(),
            ]],
            $filtros
        );
    }

    private function relatorioEstoqueValidade(string $portal, Usuario $usuario, array $filtros): array
    {
        $movimentacoes = $this->baseMovimentacoesAlimentos($portal, $usuario, $filtros)
            ->with(['escola', 'alimento.categoria'])
            ->get();

        $saldoPorEscolaAlimento = $movimentacoes->groupBy(fn (MovimentacaoAlimento $item) => $item->escola_id . '-' . $item->alimento_id);

        return $this->baseRelatorio(
            $portal,
            'Estoque e Validade',
            'Saldo consolidado de estoque com alertas de validade.',
            $this->resolverEscolaDoRelatorio($portal, $usuario, $filtros),
            [
                'Itens monitorados' => $saldoPorEscolaAlimento->count(),
                'Validades criticas' => $movimentacoes->filter(fn (MovimentacaoAlimento $item) => $item->data_validade && $item->data_validade->between(now()->startOfDay(), now()->addDays(30)->endOfDay()))->count(),
                'Registros abaixo do minimo' => $saldoPorEscolaAlimento->filter(function (Collection $grupo) {
                    $saldo = (float) $grupo->last()->saldo_resultante;
                    $minimo = (float) ($grupo->last()->alimento?->estoque_minimo ?? 0);

                    return $saldo <= $minimo;
                })->count(),
            ],
            [[
                'titulo' => 'Saldo por alimento',
                'tipo' => 'tabela',
                'colunas' => ['Escola', 'Alimento', 'Categoria', 'Saldo atual', 'Estoque minimo', 'Validade mais proxima'],
                'linhas' => $saldoPorEscolaAlimento->map(function (Collection $grupo) {
                    $ultimo = $grupo->sortBy('data_movimentacao')->last();
                    $validade = $grupo->whereNotNull('data_validade')->sortBy('data_validade')->first()?->data_validade;

                    return [
                        $ultimo?->escola?->nome ?? '-',
                        $ultimo?->alimento?->nome ?? '-',
                        $ultimo?->alimento?->categoria?->nome ?? '-',
                        number_format((float) ($ultimo?->saldo_resultante ?? 0), 3, ',', '.'),
                        number_format((float) ($ultimo?->alimento?->estoque_minimo ?? 0), 3, ',', '.'),
                        optional($validade)->format('d/m/Y') ?: '-',
                    ];
                })->values()->all(),
            ]],
            $filtros
        );
    }

    private function relatorioComparativoConsumo(string $portal, array $filtros): array
    {
        $movimentacoes = MovimentacaoAlimento::query()
            ->with(['escola', 'alimento'])
            ->when(! empty($filtros['data_inicio']), fn (Builder $query) => $query->whereDate('data_movimentacao', '>=', $filtros['data_inicio']))
            ->when(! empty($filtros['data_fim']), fn (Builder $query) => $query->whereDate('data_movimentacao', '<=', $filtros['data_fim']))
            ->get()
            ->groupBy('escola_id');

        return $this->baseRelatorio(
            $portal,
            'Comparativo de Consumo entre Escolas',
            'Visao gerencial comparativa de entradas, saidas e saldo operacional.',
            null,
            [
                'Escolas comparadas' => $movimentacoes->count(),
                'Entradas totais' => number_format((float) $movimentacoes->flatten()->where('tipo', 'entrada')->sum('quantidade'), 3, ',', '.'),
                'Saidas totais' => number_format((float) $movimentacoes->flatten()->where('tipo', 'saida')->sum('quantidade'), 3, ',', '.'),
            ],
            [[
                'titulo' => 'Comparativo por escola',
                'tipo' => 'tabela',
                'colunas' => ['Escola', 'Entradas', 'Saidas', 'Saldo operacional'],
                'linhas' => $movimentacoes->map(function (Collection $grupo) {
                    $entradas = (float) $grupo->where('tipo', 'entrada')->sum('quantidade');
                    $saidas = (float) $grupo->where('tipo', 'saida')->sum('quantidade');

                    return [
                        $grupo->first()?->escola?->nome ?? '-',
                        number_format($entradas, 3, ',', '.'),
                        number_format($saidas, 3, ',', '.'),
                        number_format($entradas - $saidas, 3, ',', '.'),
                    ];
                })->values()->all(),
            ]],
            $filtros
        );
    }

    private function relatorioAdministrativo(string $portal, Usuario $usuario, array $filtros): array
    {
        $matriculas = $this->baseMatriculas($portal, $usuario, $filtros)->with(['escola', 'turma'])->get();
        $horarios = $this->baseHorarios($portal, $usuario, $filtros)->get();

        return $this->baseRelatorio(
            $portal,
            'Relatorio Administrativo',
            'Consolidado administrativo da escola com matriculas, turmas e operacao.',
            $this->resolverEscolaDoRelatorio($portal, $usuario, $filtros),
            [
                'Matriculas ativas' => $matriculas->where('status', 'ativa')->count(),
                'Turmas ocupadas' => $matriculas->pluck('turma_id')->filter()->unique()->count(),
                'Horarios cadastrados' => $horarios->count(),
            ],
            [
                [
                    'titulo' => 'Resumo da operacao',
                    'tipo' => 'lista',
                    'itens' => [
                        ['label' => 'Transferencias', 'valor' => $matriculas->where('status', 'transferida')->count()],
                        ['label' => 'Rematriculas', 'valor' => $matriculas->where('status', 'rematriculada')->count()],
                        ['label' => 'Matriculas AEE', 'valor' => $matriculas->where('tipo', 'aee')->count()],
                        ['label' => 'Matriculas regulares', 'valor' => $matriculas->where('tipo', 'regular')->count()],
                    ],
                ],
                [
                    'titulo' => 'Turmas com mais matriculas',
                    'tipo' => 'tabela',
                    'colunas' => ['Turma', 'Ano', 'Matriculas'],
                    'linhas' => $matriculas->groupBy('turma_id')->map(function (Collection $grupo) {
                        return [
                            $grupo->first()?->turma?->nome ?? '-',
                            $grupo->first()?->turma?->ano_letivo ?? '-',
                            $grupo->count(),
                        ];
                    })->sortByDesc(fn (array $linha) => $linha[2])->values()->take(10)->all(),
                ],
            ],
            $filtros
        );
    }

    private function relatorioNotasConceitos(string $portal, Usuario $usuario, array $filtros): array
    {
        $avaliacoes = LancamentoAvaliativo::query()
            ->with(['matricula.aluno', 'matricula.turma', 'matricula.escola', 'diarioProfessor.disciplina'])
            ->whereHas('matricula', function (Builder $query) use ($portal, $usuario, $filtros) {
                $this->aplicarEscopoMatriculas($query, $portal, $usuario, $filtros);
            })
            ->when(! empty($filtros['turma_id']), fn (Builder $query) => $query->whereHas('matricula', fn (Builder $subquery) => $subquery->where('turma_id', $filtros['turma_id'])))
            ->orderBy('avaliacao_referencia')
            ->get();

        return $this->baseRelatorio(
            $portal,
            'Consulta de Notas e Conceitos',
            'Visualizacao de notas e conceitos sem alteracao de dados.',
            $this->resolverEscolaDoRelatorio($portal, $usuario, $filtros),
            [
                'Lancamentos' => $avaliacoes->count(),
                'Turmas' => $avaliacoes->pluck('matricula.turma_id')->filter()->unique()->count(),
                'Alunos avaliados' => $avaliacoes->pluck('matricula.aluno_id')->filter()->unique()->count(),
            ],
            [[
                'titulo' => 'Lancamentos avaliativos',
                'tipo' => 'tabela',
                'colunas' => ['Aluno', 'Turma', 'Disciplina', 'Referencia', 'Tipo', 'Valor', 'Observacoes'],
                'linhas' => $avaliacoes->map(fn (LancamentoAvaliativo $avaliacao) => [
                    $avaliacao->matricula?->aluno?->nome_completo ?? '-',
                    $avaliacao->matricula?->turma?->nome ?? '-',
                    $avaliacao->diarioProfessor?->disciplina?->nome ?? '-',
                    $avaliacao->avaliacao_referencia,
                    Str::title($avaliacao->tipo_avaliacao),
                    $avaliacao->valor_numerico !== null ? number_format((float) $avaliacao->valor_numerico, 2, ',', '.') : ($avaliacao->conceito ?: '-'),
                    $avaliacao->observacoes ?: '-',
                ])->all(),
            ]],
            $filtros
        );
    }

    private function relatorioPedagogico(string $portal, Usuario $usuario, array $filtros): array
    {
        $diarios = $this->baseDiarios($portal, $usuario, $filtros)
            ->with([
                'escola',
                'turma',
                'disciplina',
                'professor',
                'acompanhamentosPedagogicos',
                'lancamentosAvaliativos',
                'pendencias',
                'registrosAula.frequencias',
            ])
            ->get();

        $alunosRisco = $diarios->flatMap->acompanhamentosPedagogicos
            ->whereIn('situacao_risco', ['moderado', 'alto', 'critico']);

        return $this->baseRelatorio(
            $portal,
            'Relatorio Pedagogico',
            'Consolidado de acompanhamento pedagogico, frequencia, rendimento e pendencias.',
            $this->resolverEscolaDoRelatorio($portal, $usuario, $filtros),
            [
                'Diarios acompanhados' => $diarios->count(),
                'Alunos em risco' => $alunosRisco->count(),
                'Pendencias abertas' => $diarios->flatMap->pendencias->whereIn('status', ['aberta', 'em_andamento'])->count(),
            ],
            [
                [
                    'titulo' => 'Panorama dos diarios',
                    'tipo' => 'tabela',
                    'colunas' => ['Turma', 'Disciplina', 'Professor', 'Avaliacoes', 'Pendencias', 'Alunos em risco'],
                    'linhas' => $diarios->map(fn ($diario) => [
                        $diario->turma?->nome ?? '-',
                        $diario->disciplina?->nome ?? '-',
                        $diario->professor?->nome ?? '-',
                        $diario->lancamentosAvaliativos->count(),
                        $diario->pendencias->whereIn('status', ['aberta', 'em_andamento'])->count(),
                        $diario->acompanhamentosPedagogicos->whereIn('situacao_risco', ['moderado', 'alto', 'critico'])->count(),
                    ])->all(),
                ],
                [
                    'titulo' => 'Alunos com risco pedagogico',
                    'tipo' => 'tabela',
                    'colunas' => ['Aluno', 'Turma', 'Risco', 'Rendimento', 'Intervencao'],
                    'linhas' => $alunosRisco->map(fn ($item) => [
                        $item->matricula?->aluno?->nome_completo ?? '-',
                        $item->matricula?->turma?->nome ?? '-',
                        Str::title($item->situacao_risco),
                        Str::title($item->nivel_rendimento),
                        $item->precisa_intervencao ? 'Sim' : 'Nao',
                    ])->values()->all(),
                ],
            ],
            $filtros
        );
    }

    private function relatorioTecnicoPsicossocial(string $portal, Usuario $usuario, array $filtros): array
    {
        $escolaIds = $this->resolverEscolaIds($usuario, $portal);

        $relatorios = RelatorioTecnicoPsicossocial::query()
            ->with(['atendimento.atendivel', 'atendimento.escola'])
            ->when($portal === 'psicossocial', fn (Builder $query) => $query->whereHas('atendimento', fn (Builder $subquery) => $subquery->visivelParaUsuario($usuario)))
            ->when(! empty($filtros['escola_id']), fn (Builder $query) => $query->where('escola_id', $filtros['escola_id']))
            ->when($portal !== 'secretaria', fn (Builder $query) => $query->whereIn('escola_id', $escolaIds ?: [0]))
            ->when(! empty($filtros['data_inicio']), fn (Builder $query) => $query->whereDate('data_emissao', '>=', $filtros['data_inicio']))
            ->when(! empty($filtros['data_fim']), fn (Builder $query) => $query->whereDate('data_emissao', '<=', $filtros['data_fim']))
            ->orderByDesc('data_emissao')
            ->get();

        $atendimentos = AtendimentoPsicossocial::query()
            ->when($portal === 'psicossocial', fn (Builder $query) => $query->visivelParaUsuario($usuario))
            ->when(! empty($filtros['escola_id']), fn (Builder $query) => $query->where('escola_id', $filtros['escola_id']))
            ->when($portal !== 'secretaria', fn (Builder $query) => $query->whereIn('escola_id', $escolaIds ?: [0]))
            ->get();

        return $this->baseRelatorio(
            $portal,
            'Relatorio Tecnico da Psicologia/Psicopedagogia',
            'Relatorio tecnico restrito com controle de sigilo.',
            $this->resolverEscolaDoRelatorio($portal, $usuario, $filtros),
            [
                'Atendimentos' => $atendimentos->count(),
                'Relatorios tecnicos' => $relatorios->count(),
                'Casos com acompanhamento' => $atendimentos->where('requer_acompanhamento', true)->count(),
            ],
            [[
                'titulo' => 'Producoes tecnicas recentes',
                'tipo' => 'tabela',
                'colunas' => ['Data', 'Escola', 'Atendido', 'Tipo', 'Titulo'],
                'linhas' => $relatorios->map(fn (RelatorioTecnicoPsicossocial $relatorio) => [
                    optional($relatorio->data_emissao)->format('d/m/Y') ?: '-',
                    $relatorio->atendimento?->escola?->nome ?? '-',
                    $relatorio->atendimento?->nome_atendido ?? '-',
                    Str::title(str_replace('_', ' ', $relatorio->tipo_relatorio)),
                    $relatorio->titulo,
                ])->all(),
            ]],
            $filtros
        );
    }

    private function baseMatriculas(string $portal, Usuario $usuario, array $filtros): Builder
    {
        $query = Matricula::query();
        $this->aplicarEscopoMatriculas($query, $portal, $usuario, $filtros);

        return $query;
    }

    private function aplicarEscopoMatriculas(Builder $query, string $portal, Usuario $usuario, array $filtros): void
    {
        if (! empty($filtros['escola_id'])) {
            $query->where('escola_id', $filtros['escola_id']);
        } elseif ($portal !== 'secretaria' && $portal !== 'nutricionista') {
            $query->whereIn('escola_id', $this->resolverEscolaIds($usuario, $portal) ?: [0]);
        }

        if (! empty($filtros['turma_id'])) {
            $query->where('turma_id', $filtros['turma_id']);
        }

        if (! empty($filtros['ano_letivo'])) {
            $query->where('ano_letivo', $filtros['ano_letivo']);
        }

        if (! empty($filtros['modalidade_id'])) {
            $query->whereHas('turma', fn (Builder $subquery) => $subquery->where('modalidade_id', $filtros['modalidade_id']));
        }
    }

    private function baseTurmas(string $portal, Usuario $usuario, array $filtros): Builder
    {
        return Turma::query()
            ->when(! empty($filtros['escola_id']), fn (Builder $query) => $query->where('escola_id', $filtros['escola_id']))
            ->when($portal !== 'secretaria' && $portal !== 'nutricionista' && empty($filtros['escola_id']), fn (Builder $query) => $query->whereIn('escola_id', $this->resolverEscolaIds($usuario, $portal) ?: [0]))
            ->when(! empty($filtros['modalidade_id']), fn (Builder $query) => $query->where('modalidade_id', $filtros['modalidade_id']))
            ->when(! empty($filtros['ano_letivo']), fn (Builder $query) => $query->where('ano_letivo', $filtros['ano_letivo']));
    }

    private function baseDiarios(string $portal, Usuario $usuario, array $filtros): Builder
    {
        return \App\Models\DiarioProfessor::query()
            ->when(! empty($filtros['escola_id']), fn (Builder $query) => $query->where('escola_id', $filtros['escola_id']))
            ->when($portal !== 'secretaria' && empty($filtros['escola_id']), fn (Builder $query) => $query->whereIn('escola_id', $this->resolverEscolaIds($usuario, $portal) ?: [0]))
            ->when(! empty($filtros['turma_id']), fn (Builder $query) => $query->where('turma_id', $filtros['turma_id']))
            ->when(! empty($filtros['ano_letivo']), fn (Builder $query) => $query->where('ano_letivo', $filtros['ano_letivo']));
    }

    private function baseHorarios(string $portal, Usuario $usuario, array $filtros): Builder
    {
        return HorarioAula::query()
            ->when(! empty($filtros['escola_id']), fn (Builder $query) => $query->where('escola_id', $filtros['escola_id']))
            ->when($portal !== 'secretaria' && $portal !== 'nutricionista' && empty($filtros['escola_id']), fn (Builder $query) => $query->whereIn('escola_id', $this->resolverEscolaIds($usuario, $portal) ?: [0]))
            ->when(! empty($filtros['turma_id']), fn (Builder $query) => $query->where('turma_id', $filtros['turma_id']))
            ->when(! empty($filtros['professor_id']), fn (Builder $query) => $query->where('professor_id', $filtros['professor_id']));
    }

    private function baseMovimentacoesAlimentos(string $portal, Usuario $usuario, array $filtros): Builder
    {
        return MovimentacaoAlimento::query()
            ->when(! empty($filtros['escola_id']), fn (Builder $query) => $query->where('escola_id', $filtros['escola_id']))
            ->when($portal !== 'secretaria' && $portal !== 'nutricionista' && empty($filtros['escola_id']), fn (Builder $query) => $query->whereIn('escola_id', $this->resolverEscolaIds($usuario, $portal) ?: [0]))
            ->when(! empty($filtros['data_inicio']), fn (Builder $query) => $query->whereDate('data_movimentacao', '>=', $filtros['data_inicio']))
            ->when(! empty($filtros['data_fim']), fn (Builder $query) => $query->whereDate('data_movimentacao', '<=', $filtros['data_fim']));
    }

    private function escolasDoPortal(string $portal, Usuario $usuario): EloquentCollection
    {
        return Escola::query()
            ->where('ativo', true)
            ->when($portal !== 'secretaria' && $portal !== 'nutricionista', fn (Builder $query) => $query->whereIn('id', $this->resolverEscolaIds($usuario, $portal) ?: [0]))
            ->orderBy('nome')
            ->get();
    }

    private function resolverEscolaIds(Usuario $usuario, string $portal): array
    {
        if ($portal === 'secretaria' || $portal === 'nutricionista' || $usuario->hasRole('Administrador da Rede') || ($portal === 'psicossocial' && $usuario->acessaPortalPsicossocial())) {
            return Escola::query()->pluck('id')->all();
        }

        return $usuario->escolas()->pluck('escolas.id')->all();
    }

    private function resolverEscolaDoRelatorio(string $portal, Usuario $usuario, array $filtros): ?Escola
    {
        if (! empty($filtros['escola_id'])) {
            return Escola::query()->find($filtros['escola_id']);
        }

        if ($portal === 'secretaria' || $portal === 'nutricionista') {
            return null;
        }

        return $usuario->escolas()->first();
    }

    private function carregarMatricula(string $portal, Usuario $usuario, int $matriculaId): Matricula
    {
        $query = Matricula::query()->whereKey($matriculaId);

        if ($portal !== 'secretaria' && $portal !== 'nutricionista' && ! $usuario->hasRole('Administrador da Rede') && ! ($portal === 'psicossocial' && $usuario->acessaPortalPsicossocial())) {
            $query->whereIn('escola_id', $this->resolverEscolaIds($usuario, $portal) ?: [0]);
        }

        return $query->firstOrFail();
    }

    private function baseRelatorio(
        string $portal,
        string $titulo,
        string $subtitulo,
        ?Escola $escola,
        array $metricas,
        array $secoes,
        array $filtros
    ): array {
        return [
            'portal' => $portal,
            'titulo' => $titulo,
            'subtitulo' => $subtitulo,
            'emitido_em' => now(),
            'codigo' => 'REL-' . Str::upper(Str::random(8)),
            'instituicao' => $this->dadosInstitucionais(),
            'escola' => $escola ? [
                'nome' => $escola->nome,
                'endereco' => trim(($escola->endereco ?? '') . ' - ' . ($escola->cidade ?? '')),
            ] : null,
            'filtros_aplicados' => $this->formatarFiltros($filtros),
            'metricas' => $metricas,
            'secoes' => $secoes,
        ];
    }

    private function dadosInstitucionais(): array
    {
        return Cache::remember('instituicao_dados', now()->addDay(), function () {
            $instituicao = Instituicao::query()->first();

            return [
                'nome_prefeitura' => $instituicao?->nome_prefeitura,
                'nome_secretaria' => $instituicao?->nome_secretaria,
                'municipio' => $instituicao?->municipio,
                'uf' => $instituicao?->uf,
                'telefone' => $instituicao?->telefone,
                'email' => $instituicao?->email,
                'texto' => $instituicao?->textos_institucionais,
                'assinaturas' => collect(preg_split('/\r\n|\r|\n/', (string) $instituicao?->assinaturas_cargos))
                    ->map(fn (?string $linha) => trim((string) $linha))
                    ->filter()
                    ->values()
                    ->all(),
                'brasao_url' => $this->resolverArquivoPublico($instituicao?->brasao_path),
                'logo_prefeitura_url' => $this->resolverArquivoPublico($instituicao?->logo_prefeitura_path),
                'logo_secretaria_url' => $this->resolverArquivoPublico($instituicao?->logo_secretaria_path),
            ];
        });
    }

    private function resolverArquivoPublico(?string $path): ?string
    {
        return ArquivoPublicoUrl::resolver($path);
    }

    private function formatarFiltros(array $filtros): array
    {
        return collect($filtros)
            ->filter(fn ($valor) => filled($valor))
            ->mapWithKeys(function ($valor, $chave) {
                $label = Str::title(str_replace('_', ' ', $chave));

                return [$label => is_scalar($valor) ? (string) $valor : json_encode($valor)];
            })
            ->all();
    }

    private function rotuloDiaSemana(int $dia): string
    {
        return [
            1 => 'Segunda-feira',
            2 => 'Terca-feira',
            3 => 'Quarta-feira',
            4 => 'Quinta-feira',
            5 => 'Sexta-feira',
            6 => 'Sabado',
            7 => 'Domingo',
        ][$dia] ?? 'Nao definido';
    }
}
