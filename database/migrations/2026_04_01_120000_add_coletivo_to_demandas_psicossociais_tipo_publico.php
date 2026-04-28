<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("
                ALTER TABLE demandas_psicossociais
                MODIFY tipo_publico ENUM('aluno', 'professor', 'funcionario', 'responsavel', 'coletivo') NOT NULL
            ");
        }
    }

    public function down(): void
    {
        $possuiRegistrosColetivos = DB::table('demandas_psicossociais')
            ->where('tipo_publico', 'coletivo')
            ->exists();

        if ($possuiRegistrosColetivos) {
            throw new RuntimeException('Nao e possivel reverter a migration enquanto existirem demandas coletivas.');
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement("
                ALTER TABLE demandas_psicossociais
                MODIFY tipo_publico ENUM('aluno', 'professor', 'funcionario', 'responsavel') NOT NULL
            ");
        }
    }
};
