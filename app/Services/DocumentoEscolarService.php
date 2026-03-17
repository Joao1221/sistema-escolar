<?php

namespace App\Services;

use App\Models\AcompanhamentoPedagogicoAluno;
use App\Models\Aluno;
use App\Models\AtendimentoPsicossocial;
use App\Models\DiarioProfessor;
use App\Models\EncaminhamentoPsicossocial;
use App\Models\Escola;
use App\Models\Instituicao;
use App\Models\LancamentoAvaliativo;
use App\Models\Matricula;
use App\Models\RelatorioTecnicoPsicossocial;
use App\Models\Usuario;
use App\Support\DocumentosPortal;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentoEscolarService
{
    public function __construct(
        private readonly AuditoriaService $auditoriaService
    ) {
    }

    public function documentosDisponiveis(string $portal, Usuario $usuario): array
    {
        return collect(DocumentosPortal::documentosDoPortal($portal))
            ->filter(fn (array $definicao) => $usuario->can($definicao['permissao']))
            ->map(function (array $definicao, string $tipo) {
                return [
                    'tipo' => $tipo,
                    ...$definicao,
                ];
            })
            ->values()
            ->all();
    }

    public function opcoesFormulario(string $portal, Usuario $usuario): array
    {
        $escolaIds = $this->escolaIdsPermitidas($portal, $usuario);

        $escolas = Escola::query()
            ->whereIn('id', $escolaIds)
            ->orderBy('nome')
            ->get(['id', 'nome']);

        $alunos = Aluno::query()
            ->whereHas('matriculas', fn ($query) => $query->whereIn('escola_id', $escolaIds))
            ->orderBy('nome_completo')
            ->get(['id', 'nome_completo', 'rgm']);

        $matriculas = Matricula::query()
            ->with(['aluno:id,nome_completo,rgm', 'turma:id,nome', 'escola:id,nome'])
            ->whereIn('escola_id', $escolaIds)
            ->orderByDesc('ano_letivo')
            ->get();

        $diarios = DiarioProfessor::query()
            ->with(['turma:id,nome', 'disciplina:id,nome', 'escola:id,nome'])
            ->when(
                $portal === 'professor',
                fn ($query) => $query->where('professor_id', $usuario->resolverFuncionario()?->id ?? 0),
                fn ($query) => $query->whereIn('escola_id', $escolaIds)
            )
            ->orderByDesc('ano_letivo')
            ->get();

        $atendimentos = AtendimentoPsicossocial::query()
            ->with(['escola:id,nome', 'atendivel'])
            ->whereIn('escola_id', $escolaIds)
            ->orderByDesc('data_agendada')
            ->get();

        $relatoriosPsicossociais = RelatorioTecnicoPsicossocial::query()
            ->with('atendimento.atendivel')
            ->whereIn('escola_id', $escolaIds)
            ->orderByDesc('data_emissao')
            ->get();

        $encaminhamentosPsicossociais = EncaminhamentoPsicossocial::query()
            ->with('atendimento.atendivel')
            ->whereHas('atendimento', fn ($query) => $query->whereIn('escola_id', $escolaIds))
            ->latest('data_encaminhamento')
            ->get();

        return compact(
            'escolas',
            'alunos',
            'matriculas',
            'diarios',
            'atendimentos',
            'relatoriosPsicossociais',
            'encaminhamentosPsicossociais'
        );
    }

    public function emitir(string $portal, string $tipo, Usuario $usuario, array $dados): array
    {
        $definicao = DocumentosPortal::definicao($portal, $tipo);

        if (! $definicao) {
            abort(404);
        }

        if (! $usuario->can($definicao['permissao'])) {
            throw new AuthorizationException('Voce nao possui permissao para emitir este documento.');
        }

        $documento = match ($portal . ':' . $tipo) {
            'secretaria-escolar:declaracao-matricula' => $this->declaracaoMatricula($usuario, $dados),
            'secretaria-escolar:declaracao-frequencia' => $this->declaracaoFrequencia($usuario, $dados),
            'secretaria-escolar:comprovante-matricula' => $this->comprovanteMatricula($usuario, $dados),
            'secretaria-escolar:ficha-cadastral-aluno' => $this->fichaCadastralAluno($usuario, $dados),
            'secretaria-escolar:ficha-individual-aluno' => $this->fichaIndividualAluno($usuario, $dados),
            'secretaria-escolar:guia-transferencia' => $this->guiaTransferencia($usuario, $dados),
            'secretaria-escolar:historico-escolar' => $this->historicoEscolar($usuario, $dados),
            'secretaria-escolar:ata-escolar', 'direcao:ata-escolar' => $this->ataEscolar($portal, $usuario, $dados),
            'secretaria-escolar:oficio-escolar', 'direcao:oficio-escolar' => $this->oficioEscolar($portal, $usuario, $dados),
            'secretaria:oficio-institucional-rede' => $this->oficioInstitucionalRede($usuario, $dados),
            'secretaria:modelo-institucional-rede' => $this->modeloInstitucionalRede($usuario, $dados),
            'direcao:declaracao-gerencial' => $this->declaracaoGerencial($usuario, $dados),
            'coordenacao:ficha-individual-aluno' => $this->fichaIndividualAluno($usuario, $dados, true),
            'coordenacao:acompanhamento-pedagogico' => $this->acompanhamentoPedagogico($usuario, $dados),
            'professor:relatorio-operacional-turma' => $this->relatorioOperacionalTurma($usuario, $dados),
            'psicossocial:registro-atendimento' => $this->registroAtendimento($usuario, $dados),
            'psicossocial:relatorio-tecnico' => $this->relatorioTecnico($usuario, $dados),
            'psicossocial:encaminhamento-psicossocial' => $this->encaminhamentoPsicossocial($usuario, $dados),
            default => abort(404),
        };

        $this->auditoriaService->registrarEvento([
            'usuario_id' => $usuario->id,
            'escola_id' => data_get($documento, 'escola.id'),
            'modulo' => $portal === 'psicossocial' ? 'psicossocial' : 'documentos',
            'acao' => 'emissao_documento',
            'tipo_registro' => 'Emissao de Documento',
            'nivel_sensibilidade' => $portal === 'psicossocial' ? 'sigiloso' : 'medio',
            'contexto' => [
                'portal' => $portal,
                'tipo_documento' => $tipo,
                'titulo_documento' => $documento['titulo'] ?? null,
            ],
        ]);

        return $documento;
    }

    private function declaracaoMatricula(Usuario $usuario, array $dados): array
    {
        $matricula = $this->carregarMatricula($usuario, (int) $dados['matricula_id']);

        return $this->montarDocumentoBase(
            'Declaracao de Matricula',
            $matricula->escola,
            [
                'Aluno' => $matricula->aluno->nome_completo,
                'RGM' => $matricula->aluno->rgm ?: 'Nao informado',
                'Turma' => $matricula->turma?->nome ?: 'Nao enturmado',
                'Ano letivo' => (string) $matricula->ano_letivo,
                'Tipo de matricula' => Str::upper($matricula->tipo),
                'Situacao' => Str::title($matricula->status),
            ],
            [
                'Declaro, para os devidos fins, que o(a) aluno(a) acima identificado(a) possui matricula registrada nesta unidade escolar na data de emissao deste documento.',
            ]
        );
    }

    private function declaracaoFrequencia(Usuario $usuario, array $dados): array
    {
        $matricula = $this->carregarMatricula($usuario, (int) $dados['matricula_id']);
        $frequencias = $matricula->frequenciasAula()->get();
        $total = $frequencias->count();
        $presentes = $frequencias->where('situacao', 'presente')->count();
        $percentual = $total > 0 ? number_format(($presentes / $total) * 100, 2, ',', '.') . '%' : 'Sem lancamentos';

        return $this->montarDocumentoBase(
            'Declaracao de Frequencia',
            $matricula->escola,
            [
                'Aluno' => $matricula->aluno->nome_completo,
                'Turma' => $matricula->turma?->nome ?: 'Nao enturmado',
                'Ano letivo' => (string) $matricula->ano_letivo,
                'Frequencia apurada' => $percentual,
                'Registros contabilizados' => (string) $total,
            ],
            [
                'Declaro, para os devidos fins, que o(a) aluno(a) acima identificado(a) possui frequencia registrada no sistema escolar conforme os lancamentos disponiveis ate a presente data.',
            ]
        );
    }

    private function comprovanteMatricula(Usuario $usuario, array $dados): array
    {
        $matricula = $this->carregarMatricula($usuario, (int) $dados['matricula_id']);

        return $this->montarDocumentoBase(
            'Comprovante de Matricula',
            $matricula->escola,
            [
                'Aluno' => $matricula->aluno->nome_completo,
                'Escola' => $matricula->escola->nome,
                'Turma' => $matricula->turma?->nome ?: 'Nao enturmado',
                'Ano letivo' => (string) $matricula->ano_letivo,
                'Data da matricula' => optional($matricula->data_matricula)->format('d/m/Y') ?: 'Nao informada',
            ],
            [
                'Este comprovante atesta o vinculo escolar ativo do(a) estudante, para apresentacao onde necessario.',
            ]
        );
    }

    private function fichaCadastralAluno(Usuario $usuario, array $dados): array
    {
        $aluno = $this->carregarAluno($usuario, (int) $dados['aluno_id']);
        $matricula = $aluno->matriculas()->latest('ano_letivo')->with('escola', 'turma')->first();
        $escola = $matricula?->escola;

        return $this->montarDocumentoBase(
            'Ficha Cadastral do Aluno',
            $escola,
            [
                'Aluno' => $aluno->nome_completo,
                'RGM' => $aluno->rgm ?: 'Nao informado',
                'Nascimento' => optional($aluno->data_nascimento)->format('d/m/Y') ?: 'Nao informado',
                'CPF' => $aluno->cpf ?: 'Nao informado',
                'Responsavel' => $aluno->responsavel_nome ?: 'Nao informado',
                'Telefone do responsavel' => $aluno->responsavel_telefone ?: 'Nao informado',
                'Endereco' => trim(collect([$aluno->logradouro, $aluno->numero, $aluno->bairro, $aluno->cidade, $aluno->uf])->filter()->implode(', ')) ?: 'Nao informado',
            ],
            [
                'Mae: ' . ($aluno->nome_mae ?: 'Nao informado'),
                'Pai: ' . ($aluno->nome_pai ?: 'Nao informado'),
                'Saude/observacoes: ' . ($aluno->obs_saude ?: 'Sem observacoes registradas'),
            ]
        );
    }

    private function fichaIndividualAluno(Usuario $usuario, array $dados, bool $pedagogica = false): array
    {
        $matricula = $this->carregarMatricula($usuario, (int) $dados['matricula_id']);
        $matricula->load(['historico.usuario', 'lancamentosAvaliativos', 'observacoesAluno']);

        $avaliacoes = $matricula->lancamentosAvaliativos
            ->map(fn (LancamentoAvaliativo $avaliacao) => [
                'Referencia' => $avaliacao->avaliacao_referencia,
                'Tipo' => $avaliacao->tipo_avaliacao,
                'Valor' => $avaliacao->valor_numerico ?: ($avaliacao->conceito ?: '-'),
            ])
            ->all();

        $secoes = [
            [
                'titulo' => 'Identificacao escolar',
                'tipo' => 'lista',
                'itens' => [
                    ['label' => 'Aluno', 'valor' => $matricula->aluno->nome_completo],
                    ['label' => 'Turma', 'valor' => $matricula->turma?->nome ?: 'Nao enturmado'],
                    ['label' => 'Ano letivo', 'valor' => (string) $matricula->ano_letivo],
                    ['label' => 'Status', 'valor' => Str::title($matricula->status)],
                ],
            ],
            [
                'titulo' => 'Historico de movimentacoes',
                'tipo' => 'tabela',
                'colunas' => ['Data', 'Acao', 'Descricao', 'Usuario'],
                'linhas' => $matricula->historico->map(fn ($item) => [
                    optional($item->created_at)->format('d/m/Y H:i') ?: '-',
                    $item->acao,
                    $item->descricao,
                    $item->usuario?->name ?: '-',
                ])->all(),
            ],
            [
                'titulo' => $pedagogica ? 'Indicadores pedagogicos' : 'Lancamentos avaliativos',
                'tipo' => 'tabela',
                'colunas' => ['Referencia', 'Tipo', 'Valor'],
                'linhas' => collect($avaliacoes)->map(fn ($linha) => array_values($linha))->all(),
            ],
        ];

        return $this->montarDocumentoBase(
            'Ficha Individual do Aluno',
            $matricula->escola,
            [
                'Aluno' => $matricula->aluno->nome_completo,
                'Escola' => $matricula->escola->nome,
                'Turma' => $matricula->turma?->nome ?: 'Nao enturmado',
            ],
            $pedagogica
                ? ['Consulta pedagogica emitida conforme escopo de acompanhamento da coordenacao.']
                : ['Documento individual do aluno para apoio a rotina administrativa e escolar.'],
            $secoes
        );
    }

    private function guiaTransferencia(Usuario $usuario, array $dados): array
    {
        $matricula = $this->carregarMatricula($usuario, (int) $dados['matricula_id']);

        return $this->montarDocumentoBase(
            'Guia de Transferencia',
            $matricula->escola,
            [
                'Aluno' => $matricula->aluno->nome_completo,
                'Escola de origem' => $matricula->escola->nome,
                'Destino informado' => $dados['destino'],
                'Turma atual' => $matricula->turma?->nome ?: 'Nao enturmado',
            ],
            [
                'A presente guia formaliza a transferencia escolar do(a) estudante, preservando os registros ja existentes no sistema.',
            ]
        );
    }

    private function historicoEscolar(Usuario $usuario, array $dados): array
    {
        $matricula = $this->carregarMatricula($usuario, (int) $dados['matricula_id']);
        $matricula->load(['historico.usuario', 'lancamentosAvaliativos']);

        $linhasAvaliacoes = $matricula->lancamentosAvaliativos->map(function (LancamentoAvaliativo $avaliacao) {
            return [
                $avaliacao->avaliacao_referencia,
                $avaliacao->tipo_avaliacao,
                $avaliacao->valor_numerico ?: ($avaliacao->conceito ?: '-'),
                $avaliacao->observacoes ?: '-',
            ];
        })->all();

        return $this->montarDocumentoBase(
            'Historico Escolar',
            $matricula->escola,
            [
                'Aluno' => $matricula->aluno->nome_completo,
                'Ano letivo' => (string) $matricula->ano_letivo,
                'Turma' => $matricula->turma?->nome ?: 'Nao enturmado',
            ],
            [
                'Historico emitido com base nos registros escolares disponiveis ate a presente data.',
                'O sistema preserva o historico sem sobrescrita de eventos anteriores.',
            ],
            [
                [
                    'titulo' => 'Avaliacao e rendimento',
                    'tipo' => 'tabela',
                    'colunas' => ['Referencia', 'Tipo', 'Valor', 'Observacoes'],
                    'linhas' => $linhasAvaliacoes,
                ],
                [
                    'titulo' => 'Movimentacoes registradas',
                    'tipo' => 'tabela',
                    'colunas' => ['Data', 'Acao', 'Descricao'],
                    'linhas' => $matricula->historico->map(fn ($item) => [
                        optional($item->created_at)->format('d/m/Y H:i') ?: '-',
                        $item->acao,
                        $item->descricao,
                    ])->all(),
                ],
            ]
        );
    }

    private function ataEscolar(string $portal, Usuario $usuario, array $dados): array
    {
        $escola = $this->carregarEscola($usuario, (int) $dados['escola_id'], $portal);

        return $this->montarDocumentoBase(
            'Ata Escolar',
            $escola,
            [
                'Titulo' => $dados['titulo'],
                'Referencia' => $dados['referencia'] ?? 'Sem referencia formal',
                'Portal emissor' => $portal === 'direcao' ? 'Direcao Escolar' : 'Secretaria Escolar',
            ],
            [],
            [
                [
                    'titulo' => 'Registro',
                    'tipo' => 'texto',
                    'conteudo' => $dados['conteudo'],
                ],
            ]
        );
    }

    private function oficioEscolar(string $portal, Usuario $usuario, array $dados): array
    {
        $escola = $this->carregarEscola($usuario, (int) $dados['escola_id'], $portal);

        return $this->montarDocumentoBase(
            'Oficio Escolar',
            $escola,
            [
                'Destinatario' => $dados['destinatario'],
                'Assunto' => $dados['assunto'],
                'Portal emissor' => $portal === 'direcao' ? 'Direcao Escolar' : 'Secretaria Escolar',
            ],
            [],
            [
                [
                    'titulo' => 'Corpo do oficio',
                    'tipo' => 'texto',
                    'conteudo' => $dados['conteudo'],
                ],
            ]
        );
    }

    private function oficioInstitucionalRede(Usuario $usuario, array $dados): array
    {
        $this->garantirUsuarioDeRede($usuario);

        return $this->montarDocumentoBase(
            'Oficio Institucional da Rede',
            null,
            [
                'Destinatario' => $dados['destinatario'],
                'Assunto' => $dados['assunto'],
                'Emissor' => 'Secretaria de Educacao',
            ],
            [],
            [
                [
                    'titulo' => 'Corpo do oficio',
                    'tipo' => 'texto',
                    'conteudo' => $dados['conteudo'],
                ],
            ]
        );
    }

    private function modeloInstitucionalRede(Usuario $usuario, array $dados): array
    {
        $this->garantirUsuarioDeRede($usuario);

        return $this->montarDocumentoBase(
            $dados['titulo'],
            null,
            [
                'Origem' => 'Secretaria de Educacao',
                'Emissao' => now()->format('d/m/Y H:i'),
            ],
            [],
            [
                [
                    'titulo' => 'Texto institucional',
                    'tipo' => 'texto',
                    'conteudo' => $dados['conteudo'],
                ],
            ]
        );
    }

    private function declaracaoGerencial(Usuario $usuario, array $dados): array
    {
        $matricula = $this->carregarMatricula($usuario, (int) $dados['matricula_id']);

        return $this->montarDocumentoBase(
            'Declaracao Gerencial da Escola',
            $matricula->escola,
            [
                'Aluno' => $matricula->aluno->nome_completo,
                'Turma' => $matricula->turma?->nome ?: 'Nao enturmado',
                'Ano letivo' => (string) $matricula->ano_letivo,
            ],
            [],
            [
                [
                    'titulo' => 'Texto da declaracao',
                    'tipo' => 'texto',
                    'conteudo' => $dados['conteudo'],
                ],
            ]
        );
    }

    private function acompanhamentoPedagogico(Usuario $usuario, array $dados): array
    {
        $matricula = $this->carregarMatricula($usuario, (int) $dados['matricula_id']);
        $acompanhamentos = AcompanhamentoPedagogicoAluno::query()
            ->with('usuarioCoordenador')
            ->where('matricula_id', $matricula->id)
            ->latest()
            ->get();

        return $this->montarDocumentoBase(
            'Documento de Acompanhamento Pedagogico',
            $matricula->escola,
            [
                'Aluno' => $matricula->aluno->nome_completo,
                'Turma' => $matricula->turma?->nome ?: 'Nao enturmado',
                'Quantidade de registros' => (string) $acompanhamentos->count(),
            ],
            [
                'Documento restrito ao acompanhamento pedagogico, emitido conforme permissao do portal.',
            ],
            [
                [
                    'titulo' => 'Registros pedagogicos',
                    'tipo' => 'tabela',
                    'colunas' => ['Rendimento', 'Risco', 'Frequencia', 'Intervencao'],
                    'linhas' => $acompanhamentos->map(fn (AcompanhamentoPedagogicoAluno $item) => [
                        $item->nivel_rendimento,
                        $item->situacao_risco,
                        $item->percentual_frequencia ? number_format((float) $item->percentual_frequencia, 2, ',', '.') . '%' : '-',
                        $item->precisa_intervencao ? 'Sim' : 'Nao',
                    ])->all(),
                ],
            ]
        );
    }

    private function relatorioOperacionalTurma(Usuario $usuario, array $dados): array
    {
        $diario = $this->carregarDiarioDoProfessor($usuario, (int) $dados['diario_id']);
        $diario->load(['turma.matriculas.aluno', 'disciplina', 'registrosAula']);

        return $this->montarDocumentoBase(
            'Relatorio Operacional da Turma',
            $diario->escola,
            [
                'Turma' => $diario->turma->nome,
                'Disciplina' => $diario->disciplina->nome,
                'Periodo' => Str::title($diario->periodo_tipo) . ' ' . $diario->periodo_referencia,
                'Professor' => $diario->professor->nome,
            ],
            [
                'Relatorio restrito ao docente responsavel pela turma e disciplina.',
            ],
            [
                [
                    'titulo' => 'Alunos da turma',
                    'tipo' => 'tabela',
                    'colunas' => ['Aluno', 'RGM', 'Status'],
                    'linhas' => $diario->turma->matriculas->map(fn (Matricula $matricula) => [
                        $matricula->aluno->nome_completo,
                        $matricula->aluno->rgm ?: '-',
                        Str::title($matricula->status),
                    ])->all(),
                ],
                [
                    'titulo' => 'Aulas registradas',
                    'tipo' => 'tabela',
                    'colunas' => ['Data', 'Titulo', 'Quantidade', 'Aula dada'],
                    'linhas' => $diario->registrosAula->map(fn ($registro) => [
                        optional($registro->data_aula)->format('d/m/Y') ?: '-',
                        $registro->titulo,
                        (string) $registro->quantidade_aulas,
                        $registro->aula_dada ? 'Sim' : 'Nao',
                    ])->all(),
                ],
            ]
        );
    }

    private function registroAtendimento(Usuario $usuario, array $dados): array
    {
        $atendimento = $this->carregarAtendimento($usuario, (int) $dados['atendimento_id']);

        return $this->montarDocumentoBase(
            'Registro de Atendimento',
            $atendimento->escola,
            [
                'Atendido' => $atendimento->nome_atendido,
                'Tipo de publico' => Str::title($atendimento->tipo_publico),
                'Natureza' => Str::title($atendimento->natureza),
                'Data agendada' => optional($atendimento->data_agendada)->format('d/m/Y H:i') ?: '-',
            ],
            [
                'Documento sigiloso restrito a profissionais autorizados.',
                $atendimento->motivo_demanda,
            ]
        );
    }

    private function relatorioTecnico(Usuario $usuario, array $dados): array
    {
        $relatorio = RelatorioTecnicoPsicossocial::query()
            ->with('atendimento.escola', 'atendimento.atendivel')
            ->findOrFail($dados['relatorio_id']);

        $this->garantirAtendimentoPermitido($usuario, $relatorio->atendimento);

        return $this->montarDocumentoBase(
            'Relatorio Tecnico',
            $relatorio->atendimento->escola,
            [
                'Atendido' => $relatorio->atendimento->nome_atendido,
                'Tipo de relatorio' => Str::title(str_replace('_', ' ', $relatorio->tipo_relatorio)),
                'Data de emissao' => optional($relatorio->data_emissao)->format('d/m/Y') ?: '-',
            ],
            [
                'Documento tecnico sigiloso emitido no modulo psicossocial.',
            ],
            [
                [
                    'titulo' => $relatorio->titulo,
                    'tipo' => 'texto',
                    'conteudo' => $relatorio->conteudo_sigiloso,
                ],
            ]
        );
    }

    private function encaminhamentoPsicossocial(Usuario $usuario, array $dados): array
    {
        $encaminhamento = EncaminhamentoPsicossocial::query()
            ->with('atendimento.escola', 'atendimento.atendivel')
            ->findOrFail($dados['encaminhamento_id']);

        $this->garantirAtendimentoPermitido($usuario, $encaminhamento->atendimento);

        return $this->montarDocumentoBase(
            'Encaminhamento Psicossocial',
            $encaminhamento->atendimento->escola,
            [
                'Atendido' => $encaminhamento->atendimento->nome_atendido,
                'Destino' => $encaminhamento->destino,
                'Instituicao destino' => $encaminhamento->instituicao_destino ?: 'Nao informada',
                'Data' => optional($encaminhamento->data_encaminhamento)->format('d/m/Y') ?: '-',
            ],
            [
                'Documento sigiloso de encaminhamento emitido no contexto psicossocial.',
                $encaminhamento->motivo,
            ],
            [
                [
                    'titulo' => 'Orientacoes restritas',
                    'tipo' => 'texto',
                    'conteudo' => $encaminhamento->orientacoes_sigilosas ?: 'Sem orientacoes adicionais.',
                ],
            ]
        );
    }

    private function montarDocumentoBase(
        string $titulo,
        ?Escola $escola,
        array $dadosChave,
        array $paragrafos = [],
        array $secoes = []
    ): array {
        $instituicao = Instituicao::query()->first();
        $assinaturas = collect(preg_split('/\r\n|\r|\n/', (string) ($instituicao?->assinaturas_cargos ?? '')))
            ->filter()
            ->values()
            ->all();

        return [
            'titulo' => $titulo,
            'codigo' => now()->format('YmdHis'),
            'emitido_em' => now(),
            'instituicao' => [
                'nome_prefeitura' => $instituicao?->nome_prefeitura,
                'nome_secretaria' => $instituicao?->nome_secretaria,
                'sigla_secretaria' => $instituicao?->sigla_secretaria,
                'municipio' => $instituicao?->municipio,
                'uf' => $instituicao?->uf,
                'telefone' => $instituicao?->telefone,
                'email' => $instituicao?->email,
                'texto' => $instituicao?->textos_institucionais,
                'brasao_url' => $this->resolverArquivoPublico($instituicao?->brasao_path),
                'logo_prefeitura_url' => $this->resolverArquivoPublico($instituicao?->logo_prefeitura_path),
                'logo_secretaria_url' => $this->resolverArquivoPublico($instituicao?->logo_secretaria_path),
                'assinaturas' => $assinaturas,
            ],
            'escola' => $escola ? [
                'id' => $escola->id,
                'nome' => $escola->nome,
                'cnpj' => $escola->cnpj,
                'telefone' => $escola->telefone,
                'email' => $escola->email,
                'endereco' => trim(collect([$escola->endereco, $escola->bairro, $escola->cidade, $escola->uf, $escola->cep])->filter()->implode(', ')),
                'gestor' => $escola->nome_gestor,
            ] : null,
            'dados_chave' => $dadosChave,
            'paragrafos' => $paragrafos,
            'secoes' => $secoes,
        ];
    }

    private function carregarAluno(Usuario $usuario, int $alunoId): Aluno
    {
        $aluno = Aluno::query()->findOrFail($alunoId);
        $possuiMatriculaPermitida = $aluno->matriculas()->whereIn('escola_id', $this->escolaIdsPermitidas('secretaria-escolar', $usuario))->exists();

        if (! $possuiMatriculaPermitida) {
            throw new AuthorizationException('Aluno fora do contexto escolar permitido.');
        }

        return $aluno;
    }

    private function carregarMatricula(Usuario $usuario, int $matriculaId): Matricula
    {
        $matricula = Matricula::query()
            ->with(['aluno', 'escola', 'turma', 'historico.usuario'])
            ->findOrFail($matriculaId);

        if (! $usuario->escolas()->where('escolas.id', $matricula->escola_id)->exists()) {
            throw new AuthorizationException('Matricula fora do contexto escolar permitido.');
        }

        return $matricula;
    }

    private function carregarEscola(Usuario $usuario, int $escolaId, string $portal): Escola
    {
        if ($portal === 'secretaria') {
            $this->garantirUsuarioDeRede($usuario);
        } elseif (! $usuario->escolas()->where('escolas.id', $escolaId)->exists()) {
            throw new AuthorizationException('Escola fora do contexto permitido.');
        }

        return Escola::query()->findOrFail($escolaId);
    }

    private function carregarDiarioDoProfessor(Usuario $usuario, int $diarioId): DiarioProfessor
    {
        $diario = DiarioProfessor::query()->with(['escola', 'turma', 'disciplina', 'professor'])->findOrFail($diarioId);

        if ($diario->professor_id !== ($usuario->resolverFuncionario()?->id ?? 0)) {
            throw new AuthorizationException('Diario fora do escopo do professor autenticado.');
        }

        return $diario;
    }

    private function carregarAtendimento(Usuario $usuario, int $atendimentoId): AtendimentoPsicossocial
    {
        $atendimento = AtendimentoPsicossocial::query()->with(['escola', 'atendivel'])->findOrFail($atendimentoId);
        $this->garantirAtendimentoPermitido($usuario, $atendimento);

        return $atendimento;
    }

    private function garantirAtendimentoPermitido(Usuario $usuario, AtendimentoPsicossocial $atendimento): void
    {
        if (! $usuario->escolas()->where('escolas.id', $atendimento->escola_id)->exists()) {
            throw new AuthorizationException('Documento sigiloso fora do contexto escolar permitido.');
        }
    }

    private function escolaIdsPermitidas(string $portal, Usuario $usuario): Collection
    {
        if ($portal === 'secretaria') {
            $this->garantirUsuarioDeRede($usuario);

            return Escola::query()->pluck('id');
        }

        return $usuario->escolas()->pluck('escolas.id');
    }

    private function garantirUsuarioDeRede(Usuario $usuario): void
    {
        if (! Gate::forUser($usuario)->any([
            'emitir oficio institucional da rede',
            'emitir modelo institucional da rede',
        ])) {
            throw new AuthorizationException('Usuario sem escopo institucional da rede.');
        }
    }

    private function resolverArquivoPublico(?string $caminho): ?string
    {
        if (! $caminho) {
            return null;
        }

        return Storage::disk('public')->exists($caminho)
            ? Storage::disk('public')->url($caminho)
            : null;
    }
}
