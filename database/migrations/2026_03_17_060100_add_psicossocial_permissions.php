<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        $permissoes = [
            'acessar modulo psicossocial',
            'consultar agenda psicossocial',
            'registrar atendimentos psicossociais',
            'consultar historico psicossocial',
            'registrar planos de intervencao psicossociais',
            'registrar encaminhamentos psicossociais',
            'registrar casos disciplinares sigilosos',
            'emitir relatorios tecnicos psicossociais',
            'acessar dados sigilosos psicossociais',
        ];

        foreach ($permissoes as $permissao) {
            Permission::findOrCreate($permissao, 'web');
        }

        $role = Role::findOrCreate('Psicologia/Psicopedagogia', 'web');
        $role->givePermissionTo($permissoes);
    }

    public function down(): void
    {
        $permissoes = [
            'acessar modulo psicossocial',
            'consultar agenda psicossocial',
            'registrar atendimentos psicossociais',
            'consultar historico psicossocial',
            'registrar planos de intervencao psicossociais',
            'registrar encaminhamentos psicossociais',
            'registrar casos disciplinares sigilosos',
            'emitir relatorios tecnicos psicossociais',
            'acessar dados sigilosos psicossociais',
        ];

        foreach ($permissoes as $permissao) {
            $permission = Permission::where('name', $permissao)->where('guard_name', 'web')->first();
            if ($permission) {
                $permission->delete();
            }
        }
    }
};
