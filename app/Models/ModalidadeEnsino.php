<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModalidadeEnsino extends Model
{
    protected $table = 'modalidades_ensino';

    protected $fillable = [
        'nome',
        'estrutura_avaliativa',
        'tipo_avaliacao',
        'carga_horaria_minima',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'carga_horaria_minima' => 'integer'
    ];
}
