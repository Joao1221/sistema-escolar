<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessaoAtendimento extends Model
{
    use HasFactory;

    protected $table = 'sessoes_atendimentos';

    protected $fillable = [
        'atendimento_id',
        'usuario_profissional_id',
        'funcionario_profissional_id',
        'data_sessao',
        'hora_inicio',
        'hora_fim',
        'tipo_sessao',
        'objetivo_sessao',
        'relato_sessao',
        'estrategias_utilizadas',
        'comportamento_observado',
        'evolucao_percebida',
        'encaminhamentos_definidos',
        'necessita_retorno',
        'proximo_passo',
        'observacoes',
        'status',
    ];

    protected $casts = [
        'data_sessao' => 'date',
        'necessita_retorno' => 'boolean',
    ];

    protected $hidden = [
        'relato_sessao',
        'estrategias_utilizadas',
        'comportamento_observado',
        'evolucao_percebida',
        'encaminhamentos_definidos',
        'proximo_passo',
        'observacoes',
    ];

    protected $appends = ['relato_sessao', 'estrategias_utilizadas', 'comportamento_observado', 'evolucao_percebida', 'encaminhamentos_definidos', 'proximo_passo', 'observacoes'];

    public function atendimento(): BelongsTo
    {
        return $this->belongsTo(AtendimentoPsicossocial::class, 'atendimento_id');
    }

    public function usuarioProfissional(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_profissional_id');
    }

    public function funcionarioProfissional(): BelongsTo
    {
        return $this->belongsTo(Funcionario::class, 'funcionario_profissional_id');
    }

    public function scopeRealizadas($query)
    {
        return $query->where('status', 'realizado');
    }
}
