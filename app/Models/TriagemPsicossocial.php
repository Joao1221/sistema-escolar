<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TriagemPsicossocial extends Model
{
    use HasFactory;

    protected $table = 'triagens_psicossociais';

    protected $fillable = [
        'demanda_id',
        'usuario_triador_id',
        'resumo_caso',
        'sinais_observados',
        'historico_breve',
        'urgencia',
        'risco_identificado',
        'descricao_risco',
        'nivel_sigilo',
        'decisao',
        'justificativa_decisao',
        'observacoes',
        'data_triagem',
    ];

    protected $casts = [
        'risco_identificado' => 'boolean',
        'data_triagem' => 'date',
    ];

    protected $hidden = [
        'resumo_caso',
        'sinais_observados',
        'historico_breve',
        'descricao_risco',
        'justificativa_decisao',
        'observacoes',
    ];

    protected $appends = ['resumo_caso', 'sinais_observados', 'historico_breve', 'descricao_risco', 'justificativa_decisao', 'observacoes'];

    public function demanda(): BelongsTo
    {
        return $this->belongsTo(DemandaPsicossocial::class, 'demanda_id');
    }

    public function usuarioTriador(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_triador_id');
    }
}
