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
        Schema::table('escolas', function (Blueprint $table) {
            $table->string('inep', 8)->nullable()->after('cnpj');
            $table->string('ato_posse_diretor', 30)->nullable()->after('cpf_gestor');
            $table->integer('qtd_salas')->nullable()->default(0)->after('ativo');
            $table->string('ato_criacao', 30)->nullable();
            $table->string('ato_autoriza', 30)->nullable();
            $table->string('ato_recon', 30)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('escolas', function (Blueprint $table) {
            $table->dropColumn([
                'inep',
                'ato_posse_diretor',
                'qtd_salas',
                'ato_criacao',
                'ato_autoriza',
                'ato_recon'
            ]);
        });
    }
};
