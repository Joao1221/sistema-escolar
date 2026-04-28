<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlunoSaude extends Model
{
    use HasFactory;

    protected $table = 'aluno_saude';

    protected $fillable = [
        'aluno_id',
        'deficiencias',
        'transtornos',
        'altas_habilidades',
        'tipo_sanguineo',
        'alergias',
        'restricoes_alimentares',
        'medicacao_continua',
        'medicacao_desc',
        'obs_saude',
        'emergencia_nome',
        'emergencia_parentesco',
        'emergencia_fone',
    ];

    protected $casts = [
        'deficiencias' => 'array',
        'transtornos' => 'array',
        'altas_habilidades' => 'boolean',
        'medicacao_continua' => 'boolean',
    ];

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }
}