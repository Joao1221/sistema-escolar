<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SecretariaEscolarPermissoesSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Alunos
            'visualizar alunos',
            'criar aluno',
            'editar aluno',
            'ativar inativar aluno',
            'detalhar aluno',

            // Turmas
            'consultar turmas',
            'cadastrar turmas',
            'editar turmas',
            'excluir turmas',
            'detalhar turma',

// Matrículas
            'consultar matrículas',
            'cadastrar matrícula',
            'visualizar detalhes da matrícula',
            'editar matrícula',
            'enturmar',
            'transferir',
            'rematricular',

            // Base Curricular
            'consultar matrizes',
            'gerenciar matrizes',
            'gerenciar disciplinas',

            // Horários
            'ver horarios',
            'gerenciar horarios',
            'cadastrar horarios da direcao',
            'consultar horarios da direcao',
            'cadastrar horarios pedagogicamente',
            'consultar horarios pedagogicamente',

            // Diários
            'consultar diarios',
            'criar diarios',
            
            // Coordenação Pedagógica
            'acompanhar diarios pedagogicamente',
            'validar planejamento anual',
            'validar planejamento semanal',
            'validar aulas registradas',
            'validar planejamento por periodo',
            'acompanhar frequencia pedagogica',
            'acompanhar rendimento pedagogico',
            'acompanhar alunos em risco',
            'gerenciar pendencias docentes',
            'consultar notas e conceitos pedagogicos',
            'alterar notas e conceitos pedagogicos',
            'consultar aulas pedagogicamente',
            'ajustar aulas pedagogicamente',

            // Direção Escolar
            'acompanhar diarios da direcao',
            'validar planejamento pela direcao',
            'validar aulas pela direcao',
            'validar planejamento por periodo pela direcao',
            'consultar notas e conceitos da direcao',
            'alterar notas e conceitos da direcao',
            'consultar aulas da direcao',
            'ajustar aulas da direcao',
            'justificar faltas de alunos',
            'liberar prazo de lancamento',

            // Psicossocial
            'consultar demandas psicossociais escolares',
            'registrar demandas psicossociais escolares',

            // Alimentação Escolar
            'consultar alimentacao escolar',

            //Documentos
            'consultar documentos escolares',
            'consultar documentos pedagogicos',
            'consultar documentos da direcao escolar',

            // Relatórios
            'consultar relatorios escolares',
            'consultar relatorios pedagogicos',
            'consultar relatorios da direcao escolar',

            // Auditoria
            'consultar auditoria escolar',
            'consultar auditoria pedagogica',
            'consultar auditoria da direcao escolar',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Técnico Administrativo - permissions de visualização + registro básico
        $roleTecnico = Role::firstOrCreate(['name' => 'Técnico Administrativo']);
        $roleTecnico->givePermissionTo([
            'visualizar alunos',
            'criar aluno',
            'editar aluno',
            'ativar inativar aluno',
            'detalhar aluno',
            'consultar turmas',
            'cadastrar turmas',
            'editar turmas',
            'consultar matrículas',
            'cadastrar matrícula',
            'visualizar detalhes da matrícula',
            'editar matrícula',
            'enturmar',
            'transferir',
            'rematricular',
            'consultar matrizes',
            'ver horarios',
            'gerenciar horarios',
            'consultar diarios',
            'consultar demandas psicossociais escolares',
            'registrar demandas psicossociais escolares',
            'consultar alimentacao escolar',
            'consultar documentos escolares',
            'consultar relatorios escolares',
            'consultar auditoria escolar',
        ]);

        // Coordenador Pedagógico - herda do técnico + validação pedagógica
        $roleCoordenador = Role::firstOrCreate(['name' => 'Coordenador Pedagógico']);
        $roleCoordenador->givePermissionTo([
            // Herda do técnico
            'visualizar alunos',
            'criar aluno',
            'editar aluno',
            'detalhar aluno',
            'consultar turmas',
            'cadastrar turmas',
            'editar turmas',
            'consultar matrículas',
            'cadastrar matrícula',
            'visualizar detalhes da matrícula',
            'editar matrícula',
            'enturmar',
            'transferir',
            'rematricular',
            'justificar faltas de alunos',
            'liberar prazo de lancamento',
            'consultar matrizes',
            'ver horarios',
            'gerenciar horarios',
            'consultar diarios',
            'consultar demandas psicossociais escolares',
            'registrar demandas psicossociais escolares',
            'consultar alimentacao escolar',
            'consultar documentos escolares',
            'consultar relatorios escolares',
            'consultar auditoria escolar',
            
            // Permissões específicas de coordenação
            'gerenciar matrizes',
            'gerenciar disciplinas',
            'cadastrar horarios pedagogicamente',
            'consultar horarios pedagogicamente',
            'acompanhar diarios pedagogicamente',
            'validar planejamento anual',
            'validar planejamento semanal',
            'validar aulas registradas',
            'validar planejamento por periodo',
            'acompanhar frequencia pedagogica',
            'acompanhar rendimento pedagogico',
            'acompanhar alunos em risco',
            'gerenciar pendencias docentes',
            'consultar notas e conceitos pedagogicos',
            'alterar notas e conceitos pedagogicos',
            'consultar aulas pedagogicamente',
            'ajustar aulas pedagogicamente',
            'consultar documentos pedagogicos',
            'consultar relatorios pedagogicos',
            'consultar auditoria pedagogica',
        ]);

        // Diretor Escolar - herda do coordenador + gestão completa
        $roleDiretor = Role::firstOrCreate(['name' => 'Diretor Escolar']);
        $roleDiretor->givePermissionTo([
            // Herda do coordenador
            'visualizar alunos',
            'criar aluno',
            'editar aluno',
            'ativar inativar aluno',
            'detalhar aluno',
            'consultar turmas',
            'cadastrar turmas',
            'editar turmas',
            'excluir turmas',
            'detalhar turma',
            'consultar matrículas',
            'cadastrar matrícula',
            'visualizar detalhes da matrícula',
            'editar matrícula',
            'enturmar',
            'transferir',
            'rematricular',
            'consultar matrizes',
            'gerenciar matrizes',
            'gerenciar disciplinas',
            'ver horarios',
            'gerenciar horarios',
            'cadastrar horarios pedagogicamente',
            'consultar horarios pedagogicamente',
            'acompanhar diarios pedagogicamente',
            'validar planejamento anual',
            'validar planejamento semanal',
            'validar aulas registradas',
            'validar planejamento por periodo',
            'acompanhar frequencia pedagogica',
            'acompanhar rendimento pedagogico',
            'acompanhar alunos em risco',
            'gerenciar pendencias docentes',
            'consultar notas e conceitos pedagogicos',
            'alterar notas e conceitos pedagogicos',
            'consultar aulas pedagogicamente',
            'ajustar aulas pedagogicamente',
            'consultar demandas psicossociais escolares',
            'registrar demandas psicossociais escolares',
            'consultar alimentacao escolar',
            'consultar documentos escolares',
            'consultar documentos pedagogicos',
            'consultar relatorios escolares',
            'consultar relatorios pedagogicos',
            'consultar auditoria escolar',
            'consultar auditoria pedagogica',
            
            // Permissões específicas de direção
            'cadastrar horarios da direcao',
            'consultar horarios da direcao',
            'acompanhar diarios da direcao',
            'validar planejamento pela direcao',
            'validar aulas pela direcao',
            'validar planejamento por periodo pela direcao',
            'consultar notas e conceitos da direcao',
            'alterar notas e conceitos da direcao',
            'consultar aulas da direcao',
            'ajustar aulas da direcao',
            'justificar faltas de alunos',
            'liberar prazo de lancamento',
            'consultar documentos da direcao escolar',
            'consultar relatorios da direcao escolar',
            'consultar auditoria da direcao escolar',
        ]);
    }
}