<?php

namespace App\Support;

class DocumentosPortal
{
    public static function documentosPorPortal(): array
    {
        return [
            'secretaria-escolar' => [
                'declaracao-matricula' => [
                    'titulo' => 'Declaracao de Matricula',
                    'descricao' => 'Comprova o vinculo atual do aluno com a escola.',
                    'permissao' => 'emitir declaracao de matricula',
                    'campos' => [
                        ['nome' => 'matricula_id', 'tipo' => 'select', 'label' => 'Matricula'],
                    ],
                ],
                'declaracao-frequencia' => [
                    'titulo' => 'Declaracao de Frequencia',
                    'descricao' => 'Declara a frequencia registrada do aluno no periodo atual.',
                    'permissao' => 'emitir declaracao de frequencia',
                    'campos' => [
                        ['nome' => 'matricula_id', 'tipo' => 'select', 'label' => 'Matricula'],
                    ],
                ],
                'comprovante-matricula' => [
                    'titulo' => 'Comprovante de Matricula',
                    'descricao' => 'Emissao rapida para comprovacao administrativa.',
                    'permissao' => 'emitir comprovante de matricula',
                    'campos' => [
                        ['nome' => 'matricula_id', 'tipo' => 'select', 'label' => 'Matricula'],
                    ],
                ],
                'ficha-cadastral-aluno' => [
                    'titulo' => 'Ficha Cadastral do Aluno',
                    'descricao' => 'Reune os dados pessoais, responsaveis e endereco do aluno.',
                    'permissao' => 'emitir ficha cadastral do aluno',
                    'campos' => [
                        ['nome' => 'aluno_id', 'tipo' => 'select', 'label' => 'Aluno'],
                    ],
                ],
                'ficha-individual-aluno' => [
                    'titulo' => 'Ficha Individual do Aluno',
                    'descricao' => 'Consolida dados da matricula, turma e ocorrencias academicas.',
                    'permissao' => 'emitir ficha individual do aluno',
                    'campos' => [
                        ['nome' => 'matricula_id', 'tipo' => 'select', 'label' => 'Matricula'],
                    ],
                ],
                'guia-transferencia' => [
                    'titulo' => 'Guia de Transferencia',
                    'descricao' => 'Documento operacional para transferencia escolar.',
                    'permissao' => 'emitir guia de transferencia',
                    'campos' => [
                        ['nome' => 'matricula_id', 'tipo' => 'select', 'label' => 'Matricula'],
                        ['nome' => 'destino', 'tipo' => 'text', 'label' => 'Destino'],
                    ],
                ],
                'historico-escolar' => [
                    'titulo' => 'Historico Escolar',
                    'descricao' => 'Visualizacao consolidada dos registros academicos disponiveis.',
                    'permissao' => 'emitir historico escolar',
                    'campos' => [
                        ['nome' => 'matricula_id', 'tipo' => 'select', 'label' => 'Matricula'],
                    ],
                ],
                'ata-escolar' => [
                    'titulo' => 'Ata Escolar',
                    'descricao' => 'Registro formal de reuniao, conselho ou ato administrativo.',
                    'permissao' => 'emitir ata escolar',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'titulo', 'tipo' => 'text', 'label' => 'Titulo'],
                        ['nome' => 'referencia', 'tipo' => 'text', 'label' => 'Referencia'],
                        ['nome' => 'conteudo', 'tipo' => 'textarea', 'label' => 'Conteudo'],
                    ],
                ],
                'oficio-escolar' => [
                    'titulo' => 'Oficio Escolar',
                    'descricao' => 'Oficio administrativo emitido no contexto da escola.',
                    'permissao' => 'emitir oficio escolar',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'destinatario', 'tipo' => 'text', 'label' => 'Destinatario'],
                        ['nome' => 'assunto', 'tipo' => 'text', 'label' => 'Assunto'],
                        ['nome' => 'conteudo', 'tipo' => 'textarea', 'label' => 'Conteudo'],
                    ],
                ],
            ],
            'secretaria' => [
                'oficio-institucional-rede' => [
                    'titulo' => 'Oficio Institucional da Rede',
                    'descricao' => 'Documento oficial da Secretaria de Educacao com identidade institucional.',
                    'permissao' => 'emitir oficio institucional da rede',
                    'campos' => [
                        ['nome' => 'destinatario', 'tipo' => 'text', 'label' => 'Destinatario'],
                        ['nome' => 'assunto', 'tipo' => 'text', 'label' => 'Assunto'],
                        ['nome' => 'conteudo', 'tipo' => 'textarea', 'label' => 'Conteudo'],
                    ],
                ],
                'modelo-institucional-rede' => [
                    'titulo' => 'Modelo Institucional da Rede',
                    'descricao' => 'Modelo institucional com dados da prefeitura e secretaria.',
                    'permissao' => 'emitir modelo institucional da rede',
                    'campos' => [
                        ['nome' => 'titulo', 'tipo' => 'text', 'label' => 'Titulo'],
                        ['nome' => 'conteudo', 'tipo' => 'textarea', 'label' => 'Conteudo'],
                    ],
                ],
            ],
            'direcao' => [
                'ata-escolar' => [
                    'titulo' => 'Ata Escolar',
                    'descricao' => 'Ata gerencial emitida pela Direcao Escolar.',
                    'permissao' => 'emitir documentos da direcao escolar',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'titulo', 'tipo' => 'text', 'label' => 'Titulo'],
                        ['nome' => 'referencia', 'tipo' => 'text', 'label' => 'Referencia'],
                        ['nome' => 'conteudo', 'tipo' => 'textarea', 'label' => 'Conteudo'],
                    ],
                ],
                'oficio-escolar' => [
                    'titulo' => 'Oficio Escolar',
                    'descricao' => 'Oficio escolar com acesso gerencial ampliado.',
                    'permissao' => 'emitir documentos da direcao escolar',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'destinatario', 'tipo' => 'text', 'label' => 'Destinatario'],
                        ['nome' => 'assunto', 'tipo' => 'text', 'label' => 'Assunto'],
                        ['nome' => 'conteudo', 'tipo' => 'textarea', 'label' => 'Conteudo'],
                    ],
                ],
                'declaracao-gerencial' => [
                    'titulo' => 'Declaracao Gerencial',
                    'descricao' => 'Declaracao escolar emitida sob permissao da Direcao.',
                    'permissao' => 'emitir documentos da direcao escolar',
                    'campos' => [
                        ['nome' => 'matricula_id', 'tipo' => 'select', 'label' => 'Matricula'],
                        ['nome' => 'conteudo', 'tipo' => 'textarea', 'label' => 'Texto da declaracao'],
                    ],
                ],
            ],
            'coordenacao' => [
                'ficha-individual-aluno' => [
                    'titulo' => 'Ficha Individual do Aluno',
                    'descricao' => 'Consulta pedagogica do percurso escolar do aluno.',
                    'permissao' => 'emitir documentos pedagogicos',
                    'campos' => [
                        ['nome' => 'matricula_id', 'tipo' => 'select', 'label' => 'Matricula'],
                    ],
                ],
                'acompanhamento-pedagogico' => [
                    'titulo' => 'Documento de Acompanhamento Pedagogico',
                    'descricao' => 'Consolida rendimento, risco e encaminhamentos do aluno.',
                    'permissao' => 'emitir documentos pedagogicos',
                    'campos' => [
                        ['nome' => 'matricula_id', 'tipo' => 'select', 'label' => 'Matricula'],
                    ],
                ],
            ],
            'professor' => [
                'relatorio-operacional-turma' => [
                    'titulo' => 'Relatorio Operacional da Turma',
                    'descricao' => 'Impressao operacional das turmas e diarios do proprio docente.',
                    'permissao' => 'emitir documentos do professor',
                    'campos' => [
                        ['nome' => 'diario_id', 'tipo' => 'select', 'label' => 'Diario'],
                    ],
                ],
            ],
            'psicossocial' => [
                'registro-atendimento' => [
                    'titulo' => 'Registro de Atendimento',
                    'descricao' => 'Documento sigiloso com dados essenciais do atendimento.',
                    'permissao' => 'emitir documentos psicossociais',
                    'campos' => [
                        ['nome' => 'atendimento_id', 'tipo' => 'select', 'label' => 'Atendimento'],
                    ],
                ],
                'relatorio-tecnico' => [
                    'titulo' => 'Relatorio Tecnico',
                    'descricao' => 'Documento tecnico restrito emitido a partir do atendimento.',
                    'permissao' => 'emitir documentos psicossociais',
                    'campos' => [
                        ['nome' => 'relatorio_id', 'tipo' => 'select', 'label' => 'Relatorio tecnico'],
                    ],
                ],
                'encaminhamento-psicossocial' => [
                    'titulo' => 'Encaminhamento',
                    'descricao' => 'Documento sigiloso de encaminhamento interno ou externo.',
                    'permissao' => 'emitir documentos psicossociais',
                    'campos' => [
                        ['nome' => 'encaminhamento_id', 'tipo' => 'select', 'label' => 'Encaminhamento'],
                    ],
                ],
            ],
        ];
    }

    public static function portalPorRota(?string $routeName): ?string
    {
        return match (true) {
            str_starts_with((string) $routeName, 'secretaria-escolar.documentos.') => 'secretaria-escolar',
            str_starts_with((string) $routeName, 'secretaria.documentos.') => 'secretaria',
            str_starts_with((string) $routeName, 'secretaria-escolar.direcao.documentos.') => 'direcao',
            str_starts_with((string) $routeName, 'secretaria-escolar.coordenacao.documentos.') => 'coordenacao',
            str_starts_with((string) $routeName, 'professor.documentos.') => 'professor',
            str_starts_with((string) $routeName, 'secretaria-escolar.psicossocial.documentos.') => 'psicossocial',
            default => null,
        };
    }

    public static function documentosDoPortal(string $portal): array
    {
        return self::documentosPorPortal()[$portal] ?? [];
    }

    public static function definicao(string $portal, string $tipo): ?array
    {
        return self::documentosDoPortal($portal)[$tipo] ?? null;
    }
}
