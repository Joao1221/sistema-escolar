<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissions = [
            'gerenciar disciplinas',
            'gerenciar matrizes',
            'consultar matrizes'
        ];

        foreach ($permissions as $p) {
            \Spatie\Permission\Models\Permission::findOrCreate($p, 'web');
        }

        $roles = ['admin-secretaria-educacao', 'gestor-secretaria-educacao'];
        foreach ($roles as $roleName) {
            $role = \Spatie\Permission\Models\Role::where('name', $roleName)->where('guard_name', 'web')->first();
            if ($role) {
                $role->givePermissionTo($permissions);
            }
        }
    }

    public function down(): void
    {
        $permissions = [
            'gerenciar disciplinas',
            'gerenciar matrizes',
            'consultar matrizes'
        ];

        foreach ($permissions as $p) {
            \Illuminate\Support\Facades\DB::table('permissions')->where('name', $p)->delete();
        }
    }
};
