<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        $permissoes = [
            'consultar auditoria da rede',
            'consultar auditoria escolar',
            'consultar auditoria pedagogica',
            'consultar auditoria da direcao escolar',
            'consultar auditoria do proprio trabalho docente',
            'consultar auditoria da alimentacao escolar',
            'consultar auditoria psicossocial sigilosa',
            'visualizar dados sensiveis de auditoria',
        ];

        foreach ($permissoes as $permissao) {
            Permission::findOrCreate($permissao, 'web');
        }

        Role::findOrCreate('Administrador da Rede', 'web')->givePermissionTo([
            'consultar auditoria da rede',
            'visualizar dados sensiveis de auditoria',
        ]);

        foreach (['Secretário Escolar', 'Administrador da Escola'] as $perfil) {
            Role::findOrCreate($perfil, 'web')->givePermissionTo([
                'consultar auditoria escolar',
            ]);
        }

        Role::findOrCreate('Coordenador Pedagógico', 'web')->givePermissionTo([
            'consultar auditoria pedagogica',
        ]);

        Role::findOrCreate('Diretor Escolar', 'web')->givePermissionTo([
            'consultar auditoria da direcao escolar',
            'visualizar dados sensiveis de auditoria',
        ]);

        Role::findOrCreate('Professor', 'web')->givePermissionTo([
            'consultar auditoria do proprio trabalho docente',
        ]);

        Role::findOrCreate('Nutricionista', 'web')->givePermissionTo([
            'consultar auditoria da alimentacao escolar',
        ]);

        Role::findOrCreate('Psicologia/Psicopedagogia', 'web')->givePermissionTo([
            'consultar auditoria psicossocial sigilosa',
            'visualizar dados sensiveis de auditoria',
        ]);
    }

    public function down(): void
    {
        $permissoes = [
            'consultar auditoria da rede',
            'consultar auditoria escolar',
            'consultar auditoria pedagogica',
            'consultar auditoria da direcao escolar',
            'consultar auditoria do proprio trabalho docente',
            'consultar auditoria da alimentacao escolar',
            'consultar auditoria psicossocial sigilosa',
            'visualizar dados sensiveis de auditoria',
        ];

        foreach ($permissoes as $permissao) {
            $registro = Permission::where('name', $permissao)->where('guard_name', 'web')->first();

            if ($registro) {
                $registro->delete();
            }
        }
    }
};
