<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissoes = [
            'acompanhar diarios pedagogicamente',
            'validar planejamento anual',
            'validar planejamento semanal',
            'validar aulas registradas',
            'acompanhar frequencia pedagogica',
            'acompanhar rendimento pedagogico',
            'acompanhar alunos em risco',
            'gerenciar pendencias docentes',
        ];

        foreach ($permissoes as $permissao) {
            Permission::findOrCreate($permissao, 'web');
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $roleCoordenador = Role::firstOrCreate(['name' => 'Coordenador Pedagógico', 'guard_name' => 'web']);
        $roleCoordenador->givePermissionTo($permissoes);

        $roleAdministradorRede = Role::firstOrCreate(['name' => 'Administrador da Rede', 'guard_name' => 'web']);
        $roleAdministradorRede->givePermissionTo($permissoes);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissoes = [
            'acompanhar diarios pedagogicamente',
            'validar planejamento anual',
            'validar planejamento semanal',
            'validar aulas registradas',
            'acompanhar frequencia pedagogica',
            'acompanhar rendimento pedagogico',
            'acompanhar alunos em risco',
            'gerenciar pendencias docentes',
        ];

        Permission::whereIn('name', $permissoes)->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
