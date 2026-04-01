<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        $permissoes = [
            'consultar demandas psicossociais escolares',
            'registrar demandas psicossociais escolares',
        ];

        foreach ($permissoes as $permissao) {
            Permission::findOrCreate($permissao, 'web');
        }

        $coord = Role::findOrCreate("Coordenador Pedag\u{00F3}gico", 'web');
        $direcao = Role::findOrCreate('Diretor Escolar', 'web');

        $coord->givePermissionTo($permissoes);
        $direcao->givePermissionTo($permissoes);
    }

    public function down(): void
    {
        $permissoes = [
            'consultar demandas psicossociais escolares',
            'registrar demandas psicossociais escolares',
        ];

        $roles = [
            Role::where('name', "Coordenador Pedag\u{00F3}gico")->where('guard_name', 'web')->first(),
            Role::where('name', 'Diretor Escolar')->where('guard_name', 'web')->first(),
        ];

        foreach ($roles as $role) {
            if ($role) {
                $role->revokePermissionTo($permissoes);
            }
        }

        foreach ($permissoes as $permissao) {
            $permission = Permission::where('name', $permissao)->where('guard_name', 'web')->first();

            if ($permission) {
                $permission->delete();
            }
        }
    }
};
