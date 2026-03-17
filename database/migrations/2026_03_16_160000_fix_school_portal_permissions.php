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
        // 1. Criar novas permissões de Matrículas (que ainda não existem no DB)
        $novasPermissoesMatricula = [
            'consultar matrículas',
            'cadastrar matrícula',
            'visualizar detalhes da matrícula',
            'enturmar',
            'transferir',
            'rematricular',
        ];

        foreach ($novasPermissoesMatricula as $permissao) {
            Permission::firstOrCreate(['name' => $permissao, 'guard_name' => 'web']);
        }

        // 2. Definir o conjunto completo de permissões operacionais da Escola
        $permissoesEscola = array_merge(
            [
                'visualizar alunos',
                'criar aluno',
                'editar aluno',
                'detalhar aluno',
                'ativar inativar aluno',
            ],
            [
                'consultar turmas',
                'detalhar turma',
                'cadastrar turmas',
                'editar turmas',
                'excluir turmas',
            ],
            $novasPermissoesMatricula
        );

        // 3. Atribuir aos perfis da Secretaria Escolar
        $perfisCandidatos = ['Secretário Escolar', 'Administrador da Escola', 'Administrador da Rede'];

        foreach ($perfisCandidatos as $nomePerfil) {
            $role = Role::where('name', $nomePerfil)->first();
            if ($role) {
                $role->givePermissionTo($permissoesEscola);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Não removemos as permissões no down para evitar quebrar outros perfis, 
        // mas as desvinculamos se necessário. Geralmente em migrations de permissão 
        // o down deleta apenas as criadas por ela.
        $novasPermissoesMatricula = [
            'consultar matrículas',
            'cadastrar matrícula',
            'visualizar detalhes da matrícula',
            'enturmar',
            'transferir',
            'rematricular',
        ];

        Permission::whereIn('name', $novasPermissoesMatricula)->delete();
    }
};
