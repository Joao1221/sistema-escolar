<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('planejamentos_anuais', function (Blueprint $table) {
            if (! Schema::hasColumn('planejamentos_anuais', 'status')) {
                $table->string('status', 20)->default('rascunho')->after('unidade');
            }
        });
    }

    public function down(): void
    {
        // Keep status for validation workflow compatibility.
    }
};
