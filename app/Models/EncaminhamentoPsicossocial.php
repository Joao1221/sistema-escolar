<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EncaminhamentoPsicossocial extends Model
{
    use HasFactory;

    protected $table = 'encaminhamentos_psicossociais';

    protected $fillable = [
        'atendimento_psicossocial_id',
        'usuario_id',
        'tipo',
        'destino',
        'profissional_destino',
        'instituicao_destino',
        'motivo',
        'orientacoes_sigilosas',
        'status',
        'data_encaminhamento',
        'retorno_previsto_em',
    ];

    protected $casts = [
        'data_encaminhamento' => 'date',
        'retorno_previsto_em' => 'date',
        'motivo' => 'encrypted',
        'orientacoes_sigilosas' => 'encrypted',
    ];

    public function atendimento()
    {
        return $this->belongsTo(AtendimentoPsicossocial::class, 'atendimento_psicossocial_id');
    }
}
