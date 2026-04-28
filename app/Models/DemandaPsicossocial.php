<?php

namespace App\Models;

use App\Enums\StatusDemandaPsicossocial;
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
        'aberta_pela_escola',
    ];

    protected $casts = [
        'data_solicitacao' => 'date',
        'encaminhado_para_atendimento' => 'boolean',
        'aberta_pela_escola' => 'boolean',
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
        // campo na tabela demandas: atendimento_id
        return $this->belongsTo(AtendimentoPsicossocial::class, 'atendimento_id');
    }

    public function triagem()
    {
        // tabela de triagens usa a coluna demanda_id (nao demanda_psicossocial_id)
        return $this->hasOne(TriagemPsicossocial::class, 'demanda_id');
    }

    public function getNomeAtendidoAttribute(): ?string
    {
        return $this->aluno?->nome_completo
            ?? $this->funcionario?->nome
            ?? $this->responsavel_nome
            ?? ($this->tipo_publico === 'coletivo' ? 'Atendimento coletivo' : null);
    }

    public function getTipoPublicoLabelAttribute(): string
    {
        return ucfirst($this->tipo_publico ?? '');
    }

    public function getStatusLabelAttribute(): string
    {
        return ucfirst(str_replace('_', ' ', $this->status ?? ''));
    }

    public function scopeAbertas($query)
    {
        return $query->where('status', StatusDemandaPsicossocial::Aberta);
    }

    public function scopeEmTriagem($query)
    {
        return $query->where('status', StatusDemandaPsicossocial::EmTriagem);
    }

    public function scopeEncaminhadas($query)
    {
        return $query->whereNotNull('atendimento_id');
    }
}
