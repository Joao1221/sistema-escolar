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
            'validar planejamento por periodo',
            'consultar notas e conceitos pedagogicos',
            'alterar notas e conceitos pedagogicos',
            'consultar horarios pedagogicamente',
            'cadastrar horarios pedagogicamente',
            'editar horarios pedagogicamente',
            'reorganizar horarios pedagogicamente',
            'consultar aulas pedagogicamente',
            'ajustar aulas pedagogicamente',
        ];

        foreach ($permissoes as $permissao) {
            Permission::findOrCreate($permissao, 'web');
        }

        $roles = [
            Role::firstOrCreate(['name' => 'Coordenador Pedagógico', 'guard_name' => 'web']),
            Role::firstOrCreate(['name' => 'Administrador da Rede', 'guard_name' => 'web']),
        ];

        foreach ($roles as $role) {
            $role->givePermissionTo($permissoes);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Permission::whereIn('name', [
            'validar planejamento por periodo',
            'consultar notas e conceitos pedagogicos',
            'alterar notas e conceitos pedagogicos',
            'consultar horarios pedagogicamente',
            'cadastrar horarios pedagogicamente',
            'editar horarios pedagogicamente',
            'reorganizar horarios pedagogicamente',
            'consultar aulas pedagogicamente',
            'ajustar aulas pedagogicamente',
        ])->delete();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
