<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissoes = [
            'validar planejamento por periodo pela direcao',
            'consultar notas e conceitos da direcao',
            'alterar notas e conceitos da direcao',
            'consultar horarios da direcao',
            'cadastrar horarios da direcao',
            'editar horarios da direcao',
            'reorganizar horarios da direcao',
            'consultar aulas da direcao',
            'ajustar aulas da direcao',
        ];

        foreach ($permissoes as $permissao) {
            Permission::findOrCreate($permissao, 'web');
        }

        foreach (['Diretor Escolar', 'Administrador da Rede'] as $perfil) {
            $papel = Role::firstOrCreate(['name' => $perfil, 'guard_name' => 'web']);
            $papel->givePermissionTo($permissoes);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Permission::whereIn('name', [
            'validar planejamento por periodo pela direcao',
            'consultar notas e conceitos da direcao',
            'alterar notas e conceitos da direcao',
            'consultar horarios da direcao',
            'cadastrar horarios da direcao',
            'editar horarios da direcao',
            'reorganizar horarios da direcao',
            'consultar aulas da direcao',
            'ajustar aulas da direcao',
        ])->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
