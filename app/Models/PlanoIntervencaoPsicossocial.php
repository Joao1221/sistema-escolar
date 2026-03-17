<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanoIntervencaoPsicossocial extends Model
{
    use HasFactory;

    protected $table = 'planos_intervencao_psicossociais';

    protected $fillable = [
        'atendimento_psicossocial_id',
        'usuario_id',
        'objetivo_geral',
        'objetivos_especificos',
        'estrategias',
        'responsaveis_execucao',
        'data_inicio',
        'data_fim',
        'status',
        'observacoes_sigilosas',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'objetivo_geral' => 'encrypted',
        'objetivos_especificos' => 'encrypted',
        'estrategias' => 'encrypted',
        'responsaveis_execucao' => 'encrypted',
        'observacoes_sigilosas' => 'encrypted',
    ];

    public function atendimento()
    {
        return $this->belongsTo(AtendimentoPsicossocial::class, 'atendimento_psicossocial_id');
    }
}
