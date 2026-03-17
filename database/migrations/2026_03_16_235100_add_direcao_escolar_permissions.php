<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        $permissoes = [
            'consultar diarios',
            'ver horarios',
            'acompanhar diarios da direcao',
            'validar planejamento pela direcao',
            'validar aulas pela direcao',
            'justificar faltas de alunos',
            'liberar prazo de lancamento',
            'registrar faltas de funcionarios',
            'iniciar fechamento letivo',
            'concluir fechamento letivo',
        ];

        foreach ($permissoes as $permissao) {
            Permission::findOrCreate($permissao, 'web');
        }

        foreach (['Diretor Escolar', 'Administrador da Rede'] as $perfil) {
            $papel = Role::firstOrCreate(['name' => $perfil, 'guard_name' => 'web']);
            $papel->givePermissionTo($permissoes);
        }

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    public function down(): void
    {
        $permissoes = [
            'acompanhar diarios da direcao',
            'validar planejamento pela direcao',
            'validar aulas pela direcao',
            'justificar faltas de alunos',
            'liberar prazo de lancamento',
            'registrar faltas de funcionarios',
            'iniciar fechamento letivo',
            'concluir fechamento letivo',
        ];

        foreach (['Diretor Escolar', 'Administrador da Rede'] as $perfil) {
            $papel = Role::where('name', $perfil)->where('guard_name', 'web')->first();
            if ($papel) {
                $papel->revokePermissionTo($permissoes);
            }
        }

        Permission::whereIn('name', $permissoes)->delete();

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
};
