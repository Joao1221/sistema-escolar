<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PerfisPermissoesSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Criar permissões básicas de sistema ou módulos iniciais
        Permission::firstOrCreate(['name' => 'acessar painel']);

        // Permissões do módulo Gestão de Usuários
        Permission::firstOrCreate(['name' => 'visualizar usuarios']);
        Permission::firstOrCreate(['name' => 'criar usuario']);
        Permission::firstOrCreate(['name' => 'editar usuario']);
        Permission::firstOrCreate(['name' => 'ativar inativar usuario']);

        // Permissões do módulo Dados Institucionais
        Permission::firstOrCreate(['name' => 'visualizar instituicao']);
        Permission::firstOrCreate(['name' => 'editar instituicao']);

        // Permissões do módulo Configurações Globais
        Permission::firstOrCreate(['name' => 'visualizar configuracoes']);
        Permission::firstOrCreate(['name' => 'editar configuracoes']);

        // Permissões do módulo Escolas
        Permission::firstOrCreate(['name' => 'visualizar escolas']);
        Permission::firstOrCreate(['name' => 'criar escola']);
        Permission::firstOrCreate(['name' => 'editar escola']);
        Permission::firstOrCreate(['name' => 'ativar inativar escola']);

        // Permissões do módulo Funcionários
        Permission::firstOrCreate(['name' => 'visualizar funcionarios']);
        Permission::firstOrCreate(['name' => 'criar funcionario']);
        Permission::firstOrCreate(['name' => 'editar funcionario']);
        Permission::firstOrCreate(['name' => 'ativar inativar funcionario']);

        // Criar perfis e atribuir permissões
        $roleAdmin = Role::firstOrCreate(['name' => 'Administrador da Rede']);
        $roleAdmin->givePermissionTo([
            'acessar painel',
            'visualizar usuarios',
            'criar usuario',
            'editar usuario',
            'ativar inativar usuario',
            'visualizar instituicao',
            'editar instituicao',
            'visualizar configuracoes',
            'editar configuracoes',
            'visualizar escolas',
            'criar escola',
            'editar escola',
            'ativar inativar escola',
            'visualizar funcionarios',
            'criar funcionario',
            'editar funcionario',
            'ativar inativar funcionario'
        ]);
    }
}
