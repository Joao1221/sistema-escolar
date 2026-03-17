<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        $permissoes = [
            'acessar portal da nutricionista',
            'consultar alimentos da nutricionista',
            'consultar categorias da nutricionista',
            'consultar fornecedores da nutricionista',
            'consultar cardapios da nutricionista',
            'consultar estoque da nutricionista',
            'consultar validade da nutricionista',
            'consultar movimentacoes da nutricionista',
            'consultar comparativo de alimentacao entre escolas',
            'consultar relatorios gerenciais da alimentacao',
        ];

        foreach ($permissoes as $permissao) {
            Permission::findOrCreate($permissao, 'web');
        }

        $role = Role::findOrCreate('Nutricionista', 'web');
        $role->givePermissionTo($permissoes);
    }

    public function down(): void
    {
        $permissoes = [
            'acessar portal da nutricionista',
            'consultar alimentos da nutricionista',
            'consultar categorias da nutricionista',
            'consultar fornecedores da nutricionista',
            'consultar cardapios da nutricionista',
            'consultar estoque da nutricionista',
            'consultar validade da nutricionista',
            'consultar movimentacoes da nutricionista',
            'consultar comparativo de alimentacao entre escolas',
            'consultar relatorios gerenciais da alimentacao',
        ];

        foreach ($permissoes as $permissao) {
            $permission = Permission::where('name', $permissao)->where('guard_name', 'web')->first();
            if ($permission) {
                $permission->delete();
            }
        }
    }
};
