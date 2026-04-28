<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('matriculas', function (Blueprint $table) {
            if (!Schema::hasColumn('matriculas', 'turno')) {
                $table->enum('turno', ['manha', 'tarde', 'noite', 'integral'])->nullable()->after('status');
            }
            if (!Schema::hasColumn('matriculas', 'serie_pretendida')) {
                $table->string('serie_pretendida', 50)->nullable()->after('turno');
            }
            if (!Schema::hasColumn('matriculas', 'escola_origem')) {
                $table->string('escola_origem', 255)->nullable()->after('serie_pretendida');
            }
            if (!Schema::hasColumn('matriculas', 'escola_inep')) {
                $table->string('escola_inep', 8)->nullable()->after('escola_origem');
            }
            if (!Schema::hasColumn('matriculas', 'rede')) {
                $table->enum('rede', ['municipal', 'publica', 'privada', 'outra'])->nullable()->after('escola_inep');
            }
            if (!Schema::hasColumn('matriculas', 'cidade_uf')) {
                $table->string('cidade_uf', 100)->nullable()->after('rede');
            }
            if (!Schema::hasColumn('matriculas', 'serie_cursada')) {
                $table->string('serie_cursada', 50)->nullable()->after('cidade_uf');
            }
            if (!Schema::hasColumn('matriculas', 'ano_cursado')) {
                $table->year('ano_cursado')->nullable()->after('serie_cursada');
            }
            if (!Schema::hasColumn('matriculas', 'situacao')) {
                $table->enum('situacao', ['transferido', 'concluiu', 'cursando', 'desistente'])->nullable()->after('ano_cursado');
            }
            if (!Schema::hasColumn('matriculas', 'data_transferencia')) {
                $table->date('data_transferencia')->nullable()->after('situacao');
            }
            if (!Schema::hasColumn('matriculas', 'transporte')) {
                $table->boolean('transporte')->default(false)->after('data_transferencia');
            }
            if (!Schema::hasColumn('matriculas', 'transporte_veiculo')) {
                $table->enum('transporte_veiculo', ['nao', 'vans', 'onibus', 'bicicleta', 'outros'])->nullable()->after('transporte');
            }
            if (!Schema::hasColumn('matriculas', 'bolsa_familia')) {
                $table->boolean('bolsa_familia')->default(false)->after('transporte_veiculo');
            }
            if (!Schema::hasColumn('matriculas', 'bolsa_cartao')) {
                $table->string('bolsa_cartao', 11)->nullable()->after('bolsa_familia');
            }
            if (!Schema::hasColumn('matriculas', 'escolarizacao_outro')) {
                $table->enum('escolarizacao_outro', ['nao', 'hospital', 'domicilio'])->nullable()->after('bolsa_cartao');
            }
            if (!Schema::hasColumn('matriculas', 'pendencias')) {
                $table->boolean('pendencias')->default(false)->after('escolarizacao_outro');
            }
            if (!Schema::hasColumn('matriculas', 'obs_pendencias')) {
                $table->text('obs_pendencias')->nullable()->after('pendencias');
            }
        });
    }

    public function down(): void
    {
        Schema::table('matriculas', function (Blueprint $table) {
            $columnsToDrop = [
                'turno', 'serie_pretendida', 'escola_origem', 'escola_inep', 
                'rede', 'cidade_uf', 'serie_cursada', 'ano_cursado', 
                'situacao', 'data_transferencia', 'transporte', 
                'transporte_veiculo', 'bolsa_familia', 'bolsa_cartao',
                'escolarizacao_outro', 'pendencias', 'obs_pendencias'
            ];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('matriculas', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};