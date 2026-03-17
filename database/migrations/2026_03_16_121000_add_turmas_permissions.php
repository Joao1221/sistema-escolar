<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissoes = [
            'consultar turmas', // Acesso geral de leitura
            'detalhar turma',   // Ver detalhes específicos
            'cadastrar turmas', // Criar (Secretaria Escolar)
            'editar turmas',    // Editar (Secretaria Escolar)
            'excluir turmas',   // Inativar/Excluir
        ];

        foreach ($permissoes as $permissao) {
            Permission::firstOrCreate(['name' => $permissao, 'guard_name' => 'web']);
        }

        // Administrador da Rede pode tudo
        $roleAdmRede = Role::where('name', 'Administrador da Rede')->first();
        if ($roleAdmRede) {
            $roleAdmRede->givePermissionTo($permissoes);
        }

        // Criar ou atualizar Role "Secretário Escolar"
        $roleSecEscolar = Role::firstOrCreate(['name' => 'Secretário Escolar', 'guard_name' => 'web']);
        $roleSecEscolar->givePermissionTo([
            'consultar turmas',
            'detalhar turma',
            'cadastrar turmas',
            'editar turmas',
            'excluir turmas',
        ]);
        
        // Administrador da Escola também gerencia turmas
        $roleAdmEscola = Role::firstOrCreate(['name' => 'Administrador da Escola', 'guard_name' => 'web']);
        $roleAdmEscola->givePermissionTo([
            'consultar turmas',
            'detalhar turma',
            'cadastrar turmas',
            'editar turmas',
            'excluir turmas',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permissoes = [
            'consultar turmas',
            'detalhar turma',
            'cadastrar turmas',
            'editar turmas',
            'excluir turmas',
        ];

        Permission::whereIn('name', $permissoes)->delete();
    }
};
