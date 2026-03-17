<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        $permissoes = [
            'cadastrar alimentos',
            'editar alimentos',
            'cadastrar categorias de alimentos',
            'editar categorias de alimentos',
            'cadastrar fornecedores de alimentos',
            'editar fornecedores de alimentos',
            'registrar entrada de alimentos',
            'registrar saida de alimentos',
            'lancar cardapio diario',
        ];

        foreach ($permissoes as $permissao) {
            Permission::findOrCreate($permissao, 'web');
        }

        $role = Role::findOrCreate('Nutricionista', 'web');
        $role->givePermissionTo($permissoes);
    }

    public function down(): void
    {
        $role = Role::where('name', 'Nutricionista')->where('guard_name', 'web')->first();

        if ($role) {
            $role->revokePermissionTo([
                'cadastrar alimentos',
                'editar alimentos',
                'cadastrar categorias de alimentos',
                'editar categorias de alimentos',
                'cadastrar fornecedores de alimentos',
                'editar fornecedores de alimentos',
                'registrar entrada de alimentos',
                'registrar saida de alimentos',
                'lancar cardapio diario',
            ]);
        }
    }
};
