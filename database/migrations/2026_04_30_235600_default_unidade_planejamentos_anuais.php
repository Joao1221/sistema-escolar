<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('planejamentos_anuais', 'unidade')) {
            DB::statement('ALTER TABLE planejamentos_anuais MODIFY unidade TINYINT NOT NULL DEFAULT 1');
        }
    }

    public function down(): void
    {
        // Keep unidade with a safe default for legacy annual planning records.
    }
};
