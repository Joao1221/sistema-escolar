<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReavaliacaoPsicossocial extends Model
{
    use HasFactory;

    protected $table = 'reavaliacoes_psicossociais';

    protected $fillable = [
        'atendimento_id',
        'usuario_responsavel_id',
        'data_reavaliacao',
        'progresso_observado',
        'dificuldades_persistentes',
        'ajuste_plano',
        'frequencia_nova',
        'decisao',
        'justificativa',
        'proxima_reavaliacao',
    ];

    protected $casts = [
        'data_reavaliacao' => 'date',
        'proxima_reavaliacao' => 'date',
    ];

    protected $hidden = [
        'progresso_observado',
        'dificuldades_persistentes',
        'ajuste_plano',
        'justificativa',
    ];

    protected $appends = ['progresso_observado', 'dificuldades_persistentes', 'ajuste_plano', 'justificativa'];

    public function atendimento(): BelongsTo
    {
        return $this->belongsTo(AtendimentoPsicossocial::class, 'atendimento_id');
    }

    public function usuarioResponsavel(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_responsavel_id');
    }
}
