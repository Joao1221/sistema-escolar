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
            'consultar diarios',
            'criar diarios',
            'registrar aulas',
            'lancar frequencia',
            'gerenciar planejamentos',
            'registrar observacoes pedagogicas',
            'registrar ocorrencias pedagogicas',
            'gerenciar pendencias do professor',
        ];

        foreach ($permissoes as $permissao) {
            Permission::findOrCreate($permissao, 'web');
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $roleProfessor = Role::firstOrCreate(['name' => 'Professor', 'guard_name' => 'web']);
        $roleProfessor->givePermissionTo([
            'criar diarios',
            'registrar aulas',
            'lancar frequencia',
            'gerenciar planejamentos',
            'registrar observacoes pedagogicas',
            'registrar ocorrencias pedagogicas',
            'gerenciar pendencias do professor',
        ]);

        $roleCoordenador = Role::firstOrCreate(['name' => 'Coordenador Pedagógico', 'guard_name' => 'web']);
        $roleCoordenador->givePermissionTo(['consultar diarios']);

        $roleDiretor = Role::firstOrCreate(['name' => 'Diretor Escolar', 'guard_name' => 'web']);
        $roleDiretor->givePermissionTo(['consultar diarios']);

        $roleSecretario = Role::firstOrCreate(['name' => 'Secretário Escolar', 'guard_name' => 'web']);
        $roleSecretario->givePermissionTo(['consultar diarios']);

        $roleAdmEscola = Role::firstOrCreate(['name' => 'Administrador da Escola', 'guard_name' => 'web']);
        $roleAdmEscola->givePermissionTo(['consultar diarios']);

        $roleAdmRede = Role::firstOrCreate(['name' => 'Administrador da Rede', 'guard_name' => 'web']);
        $roleAdmRede->givePermissionTo($permissoes);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissoes = [
            'consultar diarios',
            'criar diarios',
            'registrar aulas',
            'lancar frequencia',
            'gerenciar planejamentos',
            'registrar observacoes pedagogicas',
            'registrar ocorrencias pedagogicas',
            'gerenciar pendencias do professor',
        ];

        Permission::whereIn('name', $permissoes)->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
