<?php

namespace App\Support;

class AuditoriaPortal
{
    public static function configuracoes(): array
    {
        return [
            'secretaria' => [
                'titulo' => 'Auditoria da Rede',
                'descricao' => 'Visao ampla dos eventos criticos, administrativos e institucionais da rede.',
                'modulos' => ['usuarios', 'instituicao', 'escolas', 'funcionarios', 'alunos', 'turmas', 'matriculas', 'aee', 'horarios', 'alimentacao', 'documentos', 'avaliacoes', 'frequencia', 'planejamentos', 'aulas', 'psicossocial', 'gestao_escolar', 'diarios', 'pedagogico'],
                'escopo' => 'rede',
            ],
            'secretaria-escolar' => [
                'titulo' => 'Auditoria Escolar',
                'descricao' => 'Auditoria operacional e administrativa da escola vinculada ao usuario.',
                'modulos' => ['alunos', 'turmas', 'matriculas', 'aee', 'horarios', 'alimentacao', 'documentos'],
                'escopo' => 'escola',
            ],
            'coordenacao' => [
                'titulo' => 'Auditoria Pedagogica',
                'descricao' => 'Rastros pedagogicos de planejamentos, aulas, frequencia, avaliacoes e acompanhamento.',
                'modulos' => ['planejamentos', 'aulas', 'frequencia', 'avaliacoes', 'pedagogico', 'diarios'],
                'escopo' => 'escola',
            ],
            'direcao' => [
                'titulo' => 'Auditoria da Direcao',
                'descricao' => 'Auditoria pedagogica e administrativa da escola com maior alcance gerencial.',
                'modulos' => ['alunos', 'turmas', 'matriculas', 'aee', 'horarios', 'aulas', 'planejamentos', 'frequencia', 'avaliacoes', 'documentos', 'alimentacao', 'funcionarios', 'gestao_escolar', 'diarios', 'pedagogico'],
                'escopo' => 'escola',
            ],
            'professor' => [
                'titulo' => 'Meus Rastros',
                'descricao' => 'Historico do proprio trabalho docente, incluindo lancamentos, planejamentos e validacoes relacionadas.',
                'modulos' => ['diarios', 'planejamentos', 'aulas', 'frequencia', 'avaliacoes', 'pedagogico'],
                'escopo' => 'proprio',
            ],
            'nutricionista' => [
                'titulo' => 'Auditoria da Alimentacao Escolar',
                'descricao' => 'Auditoria tecnica e gerencial da alimentacao escolar.',
                'modulos' => ['alimentacao'],
                'escopo' => 'rede',
            ],
            'psicossocial' => [
                'titulo' => 'Auditoria Psicossocial',
                'descricao' => 'Auditoria altamente restrita dos registros tecnicos e sigilosos.',
                'modulos' => ['psicossocial'],
                'escopo' => 'escola',
            ],
        ];
    }

    public static function configuracao(string $portal): array
    {
        return self::configuracoes()[$portal] ?? [];
    }

    public static function portalPorRota(?string $routeName): ?string
    {
        return match (true) {
            str_starts_with((string) $routeName, 'secretaria.auditoria.') => 'secretaria',
            str_starts_with((string) $routeName, 'secretaria-escolar.auditoria.') => 'secretaria-escolar',
            str_starts_with((string) $routeName, 'secretaria-escolar.coordenacao.auditoria.') => 'coordenacao',
            str_starts_with((string) $routeName, 'secretaria-escolar.direcao.auditoria.') => 'direcao',
            str_starts_with((string) $routeName, 'professor.auditoria.') => 'professor',
            str_starts_with((string) $routeName, 'nutricionista.auditoria.') => 'nutricionista',
            str_starts_with((string) $routeName, 'psicologia.auditoria.') => 'psicossocial',
            str_starts_with((string) $routeName, 'psicologia.') => 'psicossocial',
            str_starts_with((string) $routeName, 'secretaria-escolar.psicossocial.auditoria.') => 'psicossocial',
            str_starts_with((string) $routeName, 'secretaria.') => 'secretaria',
            str_starts_with((string) $routeName, 'secretaria-escolar.coordenacao.') => 'coordenacao',
            str_starts_with((string) $routeName, 'secretaria-escolar.direcao.') => 'direcao',
            str_starts_with((string) $routeName, 'secretaria-escolar.psicossocial.') => 'psicossocial',
            str_starts_with((string) $routeName, 'secretaria-escolar.') => 'secretaria-escolar',
            str_starts_with((string) $routeName, 'professor.') => 'professor',
            str_starts_with((string) $routeName, 'nutricionista.') => 'nutricionista',
            default => null,
        };
    }
}
