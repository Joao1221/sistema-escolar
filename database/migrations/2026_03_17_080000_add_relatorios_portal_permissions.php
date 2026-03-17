<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        $permissoes = [
            'consultar relatorios da rede',
            'emitir relatorio institucional da rede',
            'emitir relatorio de matriculas da rede',
            'emitir relatorio de situacao de matriculas',
            'emitir relatorio de alunos aee',
            'emitir relatorio quantitativo de matriculas',
            'emitir relatorio mapa de turmas',
            'emitir relatorio de professores por turma',
            'emitir relatorio de auditoria',
            'consultar relatorios escolares',
            'emitir relatorios administrativos escolares',
            'emitir relatorio de frequencia consolidada',
            'emitir relatorio historico escolar',
            'emitir relatorio ficha individual',
            'emitir relatorio de alimentacao escolar',
            'consultar notas e conceitos em relatorios',
            'consultar relatorios pedagogicos',
            'emitir relatorios pedagogicos',
            'consultar relatorios da direcao escolar',
            'emitir relatorios da direcao escolar',
            'consultar relatorios da nutricionista',
            'emitir relatorios da nutricionista',
            'consultar relatorios tecnicos do psicossocial',
            'emitir relatorios tecnicos do psicossocial',
        ];

        foreach ($permissoes as $permissao) {
            Permission::findOrCreate($permissao, 'web');
        }

        Role::findOrCreate('Administrador da Rede', 'web')->givePermissionTo([
            'consultar relatorios da rede',
            'emitir relatorio institucional da rede',
            'emitir relatorio de matriculas da rede',
            'emitir relatorio de situacao de matriculas',
            'emitir relatorio de alunos aee',
            'emitir relatorio quantitativo de matriculas',
            'emitir relatorio mapa de turmas',
            'emitir relatorio de professores por turma',
            'emitir relatorio de auditoria',
        ]);

        foreach (['Secretário Escolar', 'Administrador da Escola'] as $perfil) {
            Role::findOrCreate($perfil, 'web')->givePermissionTo([
                'consultar relatorios escolares',
                'emitir relatorios administrativos escolares',
                'emitir relatorio de frequencia consolidada',
                'emitir relatorio historico escolar',
                'emitir relatorio ficha individual',
                'emitir relatorio de alunos aee',
                'emitir relatorio quantitativo de matriculas',
                'emitir relatorio mapa de turmas',
                'emitir relatorio de professores por turma',
                'emitir relatorio de alimentacao escolar',
                'consultar notas e conceitos em relatorios',
            ]);
        }

        Role::findOrCreate('Coordenador Pedagógico', 'web')->givePermissionTo([
            'consultar relatorios pedagogicos',
            'emitir relatorios pedagogicos',
            'emitir relatorio ficha individual',
        ]);

        Role::findOrCreate('Diretor Escolar', 'web')->givePermissionTo([
            'consultar relatorios da direcao escolar',
            'emitir relatorios da direcao escolar',
            'emitir relatorio historico escolar',
            'emitir relatorio ficha individual',
        ]);

        Role::findOrCreate('Nutricionista', 'web')->givePermissionTo([
            'consultar relatorios da nutricionista',
            'emitir relatorios da nutricionista',
        ]);

        Role::findOrCreate('Psicologia/Psicopedagogia', 'web')->givePermissionTo([
            'consultar relatorios tecnicos do psicossocial',
            'emitir relatorios tecnicos do psicossocial',
        ]);
    }

    public function down(): void
    {
        $permissoes = [
            'consultar relatorios da rede',
            'emitir relatorio institucional da rede',
            'emitir relatorio de matriculas da rede',
            'emitir relatorio de situacao de matriculas',
            'emitir relatorio de alunos aee',
            'emitir relatorio quantitativo de matriculas',
            'emitir relatorio mapa de turmas',
            'emitir relatorio de professores por turma',
            'emitir relatorio de auditoria',
            'consultar relatorios escolares',
            'emitir relatorios administrativos escolares',
            'emitir relatorio de frequencia consolidada',
            'emitir relatorio historico escolar',
            'emitir relatorio ficha individual',
            'emitir relatorio de alimentacao escolar',
            'consultar notas e conceitos em relatorios',
            'consultar relatorios pedagogicos',
            'emitir relatorios pedagogicos',
            'consultar relatorios da direcao escolar',
            'emitir relatorios da direcao escolar',
            'consultar relatorios da nutricionista',
            'emitir relatorios da nutricionista',
            'consultar relatorios tecnicos do psicossocial',
            'emitir relatorios tecnicos do psicossocial',
        ];

        foreach ($permissoes as $permissao) {
            $registro = Permission::where('name', $permissao)->where('guard_name', 'web')->first();

            if ($registro) {
                $registro->delete();
            }
        }
    }
};
