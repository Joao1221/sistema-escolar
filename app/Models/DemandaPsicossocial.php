<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DemandaPsicossocial extends Model
{
    use HasFactory;

    protected $table = 'demandas_psicossociais';

    protected $fillable = [
        'escola_id',
        'usuario_registro_id',
        'profissional_responsavel_id',
        'tipo_atendimento',
        'origem_demanda',
        'tipo_publico',
        'aluno_id',
        'funcionario_id',
        'responsavel_nome',
        'responsavel_telefone',
        'responsavel_vinculo',
        'motivo_inicial',
        'prioridade',
        'status',
        'data_solicitacao',
        'observacoes',
        'encaminhado_para_atendimento',
        'atendimento_id',
    ];

    protected $casts = [
        'data_solicitacao' => 'date',
        'encaminhado_para_atendimento' => 'boolean',
    ];

    protected $hidden = [
        'motivo_inicial',
        'observacoes',
    ];

    protected $appends = ['motivo_inicial', 'observacoes'];

    public function escola(): BelongsTo
    {
        return $this->belongsTo(Escola::class);
    }

    public function usuarioRegistro(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_registro_id');
    }

    public function profissionalResponsavel(): BelongsTo
    {
        return $this->belongsTo(Funcionario::class, 'profissional_responsavel_id');
    }

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }

    public function funcionario(): BelongsTo
    {
        return $this->belongsTo(Funcionario::class);
    }

    public function atendimento(): BelongsTo
    {
        return $this->belongsTo(AtendimentoPsicossocial::class);
    }

    public function triagem()
    {
        return $this->hasOne(TriagemPsicossocial::class);
    }

    public function getNomeAtendidoAttribute(): ?string
    {
        return $this->aluno?->nome
            ?? $this->funcionario?->nome
            ?? $this->responsavel_nome;
    }

    public function scopeAbertas($query)
    {
        return $query->where('status', 'aberta');
    }

    public function scopeEmTriagem($query)
    {
        return $query->where('status', 'em_triagem');
    }

    public function scopeEncaminhadas($query)
    {
        return $query->whereNotNull('atendimento_id');
    }
}
