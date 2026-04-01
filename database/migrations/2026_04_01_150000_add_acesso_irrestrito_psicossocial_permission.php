<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;

return new class extends Migration
{
    public function up(): void
    {
        Permission::findOrCreate('acesso irrestrito psicossocial', 'web');
    }

    public function down(): void
    {
        $permission = Permission::where('name', 'acesso irrestrito psicossocial')
            ->where('guard_name', 'web')
            ->first();

        if ($permission) {
            $permission->delete();
        }
    }
};
