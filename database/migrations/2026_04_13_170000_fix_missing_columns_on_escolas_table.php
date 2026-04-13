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
            if (! Schema::hasColumn('escolas', 'inep')) {
                $table->string('inep', 8)->nullable()->after('cnpj');
            }

            if (! Schema::hasColumn('escolas', 'qtd_salas')) {
                $table->integer('qtd_salas')->nullable()->default(0)->after('ativo');
            }

            if (! Schema::hasColumn('escolas', 'ato_posse_diretor')) {
                $table->string('ato_posse_diretor', 30)->nullable()->after('cpf_gestor');
            }

            if (! Schema::hasColumn('escolas', 'localidade')) {
                $table->string('localidade', 70)->nullable()->after('endereco');
            }

            if (! Schema::hasColumn('escolas', 'ato_criacao')) {
                $table->string('ato_criacao', 30)->nullable();
            }

            if (! Schema::hasColumn('escolas', 'ato_autoriza')) {
                $table->string('ato_autoriza', 30)->nullable();
            }

            if (! Schema::hasColumn('escolas', 'ato_recon')) {
                $table->string('ato_recon', 30)->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('escolas', function (Blueprint $table) {
            $columns = [];

            foreach (['inep', 'qtd_salas', 'ato_posse_diretor', 'localidade', 'ato_criacao', 'ato_autoriza', 'ato_recon'] as $column) {
                if (Schema::hasColumn('escolas', $column)) {
                    $columns[] = $column;
                }
            }

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
