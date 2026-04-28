<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE alunos MODIFY cpf VARCHAR(500)");
            DB::statement("ALTER TABLE alunos MODIFY responsavel_cpf VARCHAR(500)");
            DB::statement("ALTER TABLE alunos MODIFY responsavel_telefone VARCHAR(500)");
            DB::statement("ALTER TABLE alunos MODIFY alergias TEXT");
            DB::statement("ALTER TABLE alunos MODIFY medicamentos TEXT");
            DB::statement("ALTER TABLE alunos MODIFY restricoes_alimentares TEXT");
            DB::statement("ALTER TABLE alunos MODIFY obs_saude TEXT");
            DB::statement("ALTER TABLE funcionarios MODIFY cpf VARCHAR(500)");
            DB::statement("ALTER TABLE funcionarios MODIFY telefone VARCHAR(500)");
            DB::statement("ALTER TABLE atendidos_externos MODIFY cpf VARCHAR(500)");
            DB::statement("ALTER TABLE atendidos_externos MODIFY telefone VARCHAR(500)");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE alunos MODIFY responsavel_cpf VARCHAR(14)");
            DB::statement("ALTER TABLE alunos MODIFY responsavel_telefone VARCHAR(15)");
            DB::statement("ALTER TABLE funcionarios MODIFY cpf VARCHAR(255)");
            DB::statement("ALTER TABLE funcionarios MODIFY telefone VARCHAR(255)");
            DB::statement("ALTER TABLE atendidos_externos MODIFY cpf VARCHAR(20)");
            DB::statement("ALTER TABLE atendidos_externos MODIFY telefone VARCHAR(30)");
        }
    }
};