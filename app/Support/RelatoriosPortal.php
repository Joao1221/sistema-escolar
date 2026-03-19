<?php

namespace App\Support;

class RelatoriosPortal
{
    public static function relatoriosPorPortal(): array
    {
        return [
            'secretaria' => [
                'institucional-rede' => [
                    'titulo' => 'Relatorio Institucional da Rede',
                    'descricao' => 'Panorama consolidado da rede municipal de ensino.',
                    'permissao' => 'emitir relatorio institucional da rede',
                    'campos' => [
                        ['nome' => 'ano_letivo', 'tipo' => 'number', 'label' => 'Ano letivo'],
                    ],
                ],
                'alunos-matriculados-rede' => [
                    'titulo' => 'Alunos Matriculados da Rede',
                    'descricao' => 'Listagem consolidada por escola, turma e ano letivo.',
                    'permissao' => 'emitir relatorio de matriculas da rede',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'turma_id', 'tipo' => 'select', 'label' => 'Turma'],
                        ['nome' => 'modalidade_id', 'tipo' => 'select', 'label' => 'Modalidade'],
                        ['nome' => 'ano_letivo', 'tipo' => 'number', 'label' => 'Ano letivo'],
                    ],
                ],
                'situacao-matriculas-rede' => [
                    'titulo' => 'Situacao de Matriculas da Rede',
                    'descricao' => 'Transferidos, desistentes, aprovados e reprovados quando houver base.',
                    'permissao' => 'emitir relatorio de situacao de matriculas',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'ano_letivo', 'tipo' => 'number', 'label' => 'Ano letivo'],
                    ],
                ],
                'alunos-aee-rede' => [
                    'titulo' => 'Relatorio AEE da Rede',
                    'descricao' => 'Alunos com AEE, somente AEE e matricula dupla.',
                    'permissao' => 'emitir relatorio de alunos aee',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'ano_letivo', 'tipo' => 'number', 'label' => 'Ano letivo'],
                    ],
                ],
                'quantitativo-matriculas-rede' => [
                    'titulo' => 'Quantitativo de Matriculas da Rede',
                    'descricao' => 'Quantitativo de matriculas regulares, AEE e duplas.',
                    'permissao' => 'emitir relatorio quantitativo de matriculas',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'ano_letivo', 'tipo' => 'number', 'label' => 'Ano letivo'],
                    ],
                ],
                'mapa-turmas-rede' => [
                    'titulo' => 'Mapa de Turmas da Rede',
                    'descricao' => 'Quadro de turmas, vagas e ocupacao.',
                    'permissao' => 'emitir relatorio mapa de turmas',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'modalidade_id', 'tipo' => 'select', 'label' => 'Modalidade'],
                        ['nome' => 'ano_letivo', 'tipo' => 'number', 'label' => 'Ano letivo'],
                    ],
                ],
                'professores-turma-disciplina-rede' => [
                    'titulo' => 'Professores por Turma e Disciplina',
                    'descricao' => 'Mapa consolidado de atribuicao docente.',
                    'permissao' => 'emitir relatorio de professores por turma',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'turma_id', 'tipo' => 'select', 'label' => 'Turma'],
                        ['nome' => 'professor_id', 'tipo' => 'select', 'label' => 'Professor'],
                    ],
                ],
                'auditoria-rede' => [
                    'titulo' => 'Relatorio de Auditoria',
                    'descricao' => 'Historico de movimentacoes relevantes quando houver base.',
                    'permissao' => 'emitir relatorio de auditoria',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'data_inicio', 'tipo' => 'date', 'label' => 'Data inicial'],
                        ['nome' => 'data_fim', 'tipo' => 'date', 'label' => 'Data final'],
                    ],
                ],
            ],
            'secretaria-escolar' => [
                'alunos-matriculados-escola' => [
                    'titulo' => 'Alunos Matriculados da Escola',
                    'descricao' => 'Listagem por escola, turma e ano letivo.',
                    'permissao' => 'emitir relatorios administrativos escolares',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'turma_id', 'tipo' => 'select', 'label' => 'Turma'],
                        ['nome' => 'modalidade_id', 'tipo' => 'select', 'label' => 'Modalidade'],
                        ['nome' => 'ano_letivo', 'tipo' => 'number', 'label' => 'Ano letivo'],
                    ],
                ],
                'situacao-matriculas-escola' => [
                    'titulo' => 'Transferidos, Desistentes e Situacoes',
                    'descricao' => 'Resumo da situacao das matriculas da escola.',
                    'permissao' => 'emitir relatorios administrativos escolares',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'ano_letivo', 'tipo' => 'number', 'label' => 'Ano letivo'],
                    ],
                ],
                'frequencia-consolidada' => [
                    'titulo' => 'Frequencia Consolidada',
                    'descricao' => 'Consolidado de presenca e faltas por aluno/turma.',
                    'permissao' => 'emitir relatorio de frequencia consolidada',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'turma_id', 'tipo' => 'select', 'label' => 'Turma'],
                        ['nome' => 'ano_letivo', 'tipo' => 'number', 'label' => 'Ano letivo'],
                        ['nome' => 'data_inicio', 'tipo' => 'date', 'label' => 'Data inicial'],
                        ['nome' => 'data_fim', 'tipo' => 'date', 'label' => 'Data final'],
                    ],
                ],
                'historico-escolar' => [
                    'titulo' => 'Historico Escolar',
                    'descricao' => 'Historico escolar com base nos registros disponiveis.',
                    'permissao' => 'emitir relatorio historico escolar',
                    'campos' => [
                        ['nome' => 'matricula_id', 'tipo' => 'select', 'label' => 'Matricula'],
                    ],
                ],
                'ficha-individual' => [
                    'titulo' => 'Ficha Individual',
                    'descricao' => 'Ficha individual do aluno para consulta administrativa.',
                    'permissao' => 'emitir relatorio ficha individual',
                    'campos' => [
                        ['nome' => 'matricula_id', 'tipo' => 'select', 'label' => 'Matricula'],
                    ],
                ],
                'alunos-aee' => [
                    'titulo' => 'Alunos com AEE',
                    'descricao' => 'Relacao de alunos com matricula AEE.',
                    'permissao' => 'emitir relatorio de alunos aee',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'ano_letivo', 'tipo' => 'number', 'label' => 'Ano letivo'],
                    ],
                ],
                'alunos-somente-aee' => [
                    'titulo' => 'Alunos com Matricula Somente AEE',
                    'descricao' => 'Filtra alunos com apenas matricula AEE.',
                    'permissao' => 'emitir relatorio de alunos aee',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'ano_letivo', 'tipo' => 'number', 'label' => 'Ano letivo'],
                    ],
                ],
                'alunos-matricula-dupla' => [
                    'titulo' => 'Alunos com Matricula Regular + AEE',
                    'descricao' => 'Identifica alunos com matricula dupla.',
                    'permissao' => 'emitir relatorio de alunos aee',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'ano_letivo', 'tipo' => 'number', 'label' => 'Ano letivo'],
                    ],
                ],
                'quantitativo-matriculas' => [
                    'titulo' => 'Quantitativo de Matriculas',
                    'descricao' => 'Quantitativo de matriculas regulares, AEE e duplas.',
                    'permissao' => 'emitir relatorio quantitativo de matriculas',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'ano_letivo', 'tipo' => 'number', 'label' => 'Ano letivo'],
                    ],
                ],
                'mapa-turmas' => [
                    'titulo' => 'Mapa de Turmas',
                    'descricao' => 'Quadro de turmas, vagas e ocupacao por unidade.',
                    'permissao' => 'emitir relatorio mapa de turmas',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'modalidade_id', 'tipo' => 'select', 'label' => 'Modalidade'],
                        ['nome' => 'ano_letivo', 'tipo' => 'number', 'label' => 'Ano letivo'],
                    ],
                ],
                'professores-turma-disciplina' => [
                    'titulo' => 'Professores por Turma e Disciplina',
                    'descricao' => 'Consulta do quadro docente por turma.',
                    'permissao' => 'emitir relatorio de professores por turma',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'turma_id', 'tipo' => 'select', 'label' => 'Turma'],
                        ['nome' => 'professor_id', 'tipo' => 'select', 'label' => 'Professor'],
                    ],
                ],
                'cardapio-escola' => [
                    'titulo' => 'Cardapio por Escola',
                    'descricao' => 'Cardapios lancados no contexto da escola.',
                    'permissao' => 'emitir relatorio de alimentacao escolar',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'data_inicio', 'tipo' => 'date', 'label' => 'Data inicial'],
                        ['nome' => 'data_fim', 'tipo' => 'date', 'label' => 'Data final'],
                    ],
                ],
                'entrada-saida-alimentos' => [
                    'titulo' => 'Entrada e Saida de Alimentos',
                    'descricao' => 'Movimentacoes do estoque por periodo.',
                    'permissao' => 'emitir relatorio de alimentacao escolar',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'data_inicio', 'tipo' => 'date', 'label' => 'Data inicial'],
                        ['nome' => 'data_fim', 'tipo' => 'date', 'label' => 'Data final'],
                    ],
                ],
                'estoque-validade' => [
                    'titulo' => 'Estoque e Validade',
                    'descricao' => 'Saldo atual e itens com validade proxima.',
                    'permissao' => 'emitir relatorio de alimentacao escolar',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                    ],
                ],
                'administrativo-escolar' => [
                    'titulo' => 'Relatorio Administrativo Escolar',
                    'descricao' => 'Consolidado administrativo da escola.',
                    'permissao' => 'emitir relatorios administrativos escolares',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'ano_letivo', 'tipo' => 'number', 'label' => 'Ano letivo'],
                    ],
                ],
                'notas-conceitos-consulta' => [
                    'titulo' => 'Consulta de Notas e Conceitos',
                    'descricao' => 'Visualizacao de notas/conceitos sem alteracao.',
                    'permissao' => 'consultar notas e conceitos em relatorios',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'turma_id', 'tipo' => 'select', 'label' => 'Turma'],
                        ['nome' => 'ano_letivo', 'tipo' => 'number', 'label' => 'Ano letivo'],
                    ],
                ],
            ],
            'coordenacao' => [
                'pedagogico-coordenacao' => [
                    'titulo' => 'Relatorios Pedagogicos',
                    'descricao' => 'Rendimento, frequencia, risco e validacao pedagogica.',
                    'permissao' => 'emitir relatorios pedagogicos',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'turma_id', 'tipo' => 'select', 'label' => 'Turma'],
                        ['nome' => 'ano_letivo', 'tipo' => 'number', 'label' => 'Ano letivo'],
                    ],
                ],
                'ficha-individual-pedagogica' => [
                    'titulo' => 'Ficha Individual Pedagogica',
                    'descricao' => 'Consulta individual com foco pedagogico.',
                    'permissao' => 'emitir relatorios pedagogicos',
                    'campos' => [
                        ['nome' => 'matricula_id', 'tipo' => 'select', 'label' => 'Matricula'],
                    ],
                ],
            ],
            'direcao' => [
                'pedagogico-direcao' => [
                    'titulo' => 'Relatorios Pedagogicos da Direcao',
                    'descricao' => 'Visao pedagogica da escola com notas, frequencia e validacoes.',
                    'permissao' => 'emitir relatorios da direcao escolar',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'turma_id', 'tipo' => 'select', 'label' => 'Turma'],
                        ['nome' => 'ano_letivo', 'tipo' => 'number', 'label' => 'Ano letivo'],
                    ],
                ],
                'administrativo-direcao' => [
                    'titulo' => 'Relatorios Administrativos da Direcao',
                    'descricao' => 'Fluxos administrativos, faltas funcionais e fechamento letivo.',
                    'permissao' => 'emitir relatorios da direcao escolar',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'ano_letivo', 'tipo' => 'number', 'label' => 'Ano letivo'],
                    ],
                ],
            ],
            'nutricionista' => [
                'cardapio-por-escola' => [
                    'titulo' => 'Cardapio por Escola',
                    'descricao' => 'Visao gerencial dos cardapios lancados.',
                    'permissao' => 'emitir relatorios da nutricionista',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'data_inicio', 'tipo' => 'date', 'label' => 'Data inicial'],
                        ['nome' => 'data_fim', 'tipo' => 'date', 'label' => 'Data final'],
                    ],
                ],
                'entrada-saida-alimentos' => [
                    'titulo' => 'Entrada e Saida de Alimentos',
                    'descricao' => 'Movimentacoes gerenciais por escola.',
                    'permissao' => 'emitir relatorios da nutricionista',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'data_inicio', 'tipo' => 'date', 'label' => 'Data inicial'],
                        ['nome' => 'data_fim', 'tipo' => 'date', 'label' => 'Data final'],
                    ],
                ],
                'estoque-validade' => [
                    'titulo' => 'Estoque e Validade',
                    'descricao' => 'Comparativo de saldo, estoque minimo e validade.',
                    'permissao' => 'emitir relatorios da nutricionista',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                    ],
                ],
                'comparativo-consumo' => [
                    'titulo' => 'Comparativo de Consumo entre Escolas',
                    'descricao' => 'Entradas, saidas e saldo operacional por escola.',
                    'permissao' => 'emitir relatorios da nutricionista',
                    'campos' => [
                        ['nome' => 'data_inicio', 'tipo' => 'date', 'label' => 'Data inicial'],
                        ['nome' => 'data_fim', 'tipo' => 'date', 'label' => 'Data final'],
                    ],
                ],
            ],
            'psicossocial' => [
                'tecnico-psicossocial' => [
                    'titulo' => 'Relatorios Tecnicos da Psicologia/Psicopedagogia',
                    'descricao' => 'Panorama tecnico restrito dos atendimentos e documentos sigilosos.',
                    'permissao' => 'emitir relatorios tecnicos do psicossocial',
                    'campos' => [
                        ['nome' => 'escola_id', 'tipo' => 'select', 'label' => 'Escola'],
                        ['nome' => 'data_inicio', 'tipo' => 'date', 'label' => 'Data inicial'],
                        ['nome' => 'data_fim', 'tipo' => 'date', 'label' => 'Data final'],
                    ],
                ],
            ],
        ];
    }

    public static function portalPorRota(?string $routeName): ?string
    {
        return match (true) {
            str_starts_with((string) $routeName, 'secretaria.relatorios.') => 'secretaria',
            str_starts_with((string) $routeName, 'secretaria-escolar.relatorios.') => 'secretaria-escolar',
            str_starts_with((string) $routeName, 'secretaria-escolar.coordenacao.relatorios.') => 'coordenacao',
            str_starts_with((string) $routeName, 'secretaria-escolar.direcao.relatorios.') => 'direcao',
            str_starts_with((string) $routeName, 'nutricionista.relatorios.') => 'nutricionista',
            str_starts_with((string) $routeName, 'psicologia.relatorios_tecnicos.') => 'psicossocial',
            str_starts_with((string) $routeName, 'psicologia.') => 'psicossocial',
            str_starts_with((string) $routeName, 'secretaria-escolar.psicossocial.relatorios') => 'psicossocial',
            default => null,
        };
    }

    public static function relatoriosDoPortal(string $portal): array
    {
        return self::relatoriosPorPortal()[$portal] ?? [];
    }

    public static function definicao(string $portal, string $tipo): ?array
    {
        return self::relatoriosDoPortal($portal)[$tipo] ?? null;
    }
}
