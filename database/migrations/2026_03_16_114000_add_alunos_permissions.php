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
            'visualizar alunos',
            'criar aluno',
            'editar aluno',
            'detalhar aluno',
            'ativar inativar aluno',
        ];

        foreach ($permissoes as $permissao) {
            Permission::firstOrCreate(['name' => $permissao, 'guard_name' => 'web']);
        }

        // Atribuir ao Administrador da Rede por padrão
        $role = Role::where('name', 'Administrador da Rede')->first();
        if ($role) {
            $role->givePermissionTo($permissoes);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permissoes = [
            'visualizar alunos',
            'criar aluno',
            'editar aluno',
            'detalhar aluno',
            'ativar inativar aluno',
        ];

        foreach ($permissoes as $permissao) {
            Permission::where('name', $permissao)->delete();
        }
    }
};
