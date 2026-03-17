<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ModalidadeEnsino;

class ModalidadesIniciaisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ModalidadeEnsino::firstOrCreate(['nome' => 'Educação Infantil'], [
            'estrutura_avaliativa' => 'Semestral',
            'tipo_avaliacao' => 'Parecer',
            'carga_horaria_minima' => 800,
            'ativo' => true
        ]);

        ModalidadeEnsino::firstOrCreate(['nome' => 'Ensino Fundamental'], [
            'estrutura_avaliativa' => 'Bimestral',
            'tipo_avaliacao' => 'Nota',
            'carga_horaria_minima' => 800,
            'ativo' => true
        ]);

        ModalidadeEnsino::firstOrCreate(['nome' => 'EJA - Educação de Jovens e Adultos'], [
            'estrutura_avaliativa' => 'Semestral',
            'tipo_avaliacao' => 'Nota',
            'carga_horaria_minima' => 400,
            'ativo' => true
        ]);

        ModalidadeEnsino::firstOrCreate(['nome' => 'AEE - Atendimento Educacional Especializado'], [
            'estrutura_avaliativa' => 'Anual',
            'tipo_avaliacao' => 'Parecer',
            'carga_horaria_minima' => 0,
            'ativo' => true
        ]);
    }
}
