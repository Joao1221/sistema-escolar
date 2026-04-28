<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoricoEscolar extends Model
{
    use HasFactory;

    protected $table = 'historico_escolar';

    protected $fillable = [
        'aluno_id',
        'escola_origem',
        'escola_inep',
        'rede',
        'cidade_uf',
        'serie_cursada',
        'ano_cursado',
        'situacao',
        'data_transferencia',
        'serie_pretendida',
        'turno',
        'pendencias',
        'obs_pendencias',
    ];

    protected $casts = [
        'data_transferencia' => 'date',
        'ano_cursado' => 'integer',
        'pendencias' => 'boolean',
    ];

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }
}