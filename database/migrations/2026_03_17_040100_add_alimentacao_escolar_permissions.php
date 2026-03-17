<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        $permissoes = [
            'consultar alimentacao escolar',
            'cadastrar alimentos',
            'editar alimentos',
            'registrar entrada de alimentos',
            'registrar saida de alimentos',
            'lancar cardapio diario',
            'consultar estoque de alimentos',
            'consultar movimentacoes de alimentos',
            'cadastrar categorias de alimentos',
            'cadastrar fornecedores de alimentos',
            'editar categorias de alimentos',
            'editar fornecedores de alimentos',
        ];

        foreach ($permissoes as $permissao) {
            Permission::findOrCreate($permissao, 'web');
        }

        $permissoesOperacionais = [
            'consultar alimentacao escolar',
            'cadastrar alimentos',
            'editar alimentos',
            'registrar entrada de alimentos',
            'registrar saida de alimentos',
            'lancar cardapio diario',
            'consultar estoque de alimentos',
            'consultar movimentacoes de alimentos',
            'cadastrar categorias de alimentos',
            'cadastrar fornecedores de alimentos',
            'editar categorias de alimentos',
            'editar fornecedores de alimentos',
        ];

        foreach (['Secretário Escolar', 'Administrador da Escola', 'Diretor Escolar'] as $perfil) {
            $role = Role::findByName($perfil, 'web');
            $role->givePermissionTo($permissoesOperacionais);
        }
    }

    public function down(): void
    {
        $permissoes = [
            'consultar alimentacao escolar',
            'cadastrar alimentos',
            'editar alimentos',
            'registrar entrada de alimentos',
            'registrar saida de alimentos',
            'lancar cardapio diario',
            'consultar estoque de alimentos',
            'consultar movimentacoes de alimentos',
            'cadastrar categorias de alimentos',
            'cadastrar fornecedores de alimentos',
            'editar categorias de alimentos',
            'editar fornecedores de alimentos',
        ];

        foreach ($permissoes as $permissao) {
            $permission = Permission::where('name', $permissao)->where('guard_name', 'web')->first();
            if ($permission) {
                $permission->delete();
            }
        }
    }
};
