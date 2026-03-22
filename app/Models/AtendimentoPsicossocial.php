<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtendimentoPsicossocial extends Model
{
    use HasFactory;

    protected $table = 'atendimentos_psicossociais';

    protected $fillable = [
        'escola_id',
        'usuario_registro_id',
        'profissional_responsavel_id',
        'atendivel_type',
        'atendivel_id',
        'tipo_publico',
        'tipo_atendimento',
        'natureza',
        'status',
        'data_agendada',
        'data_realizacao',
        'local_atendimento',
        'motivo_demanda',
        'resumo_sigiloso',
        'observacoes_restritas',
        'nivel_sigilo',
        'requer_acompanhamento',
        'data_encerramento',
        'motivo_encerramento',
        'resumo_encerramento',
        'orientacoes_finais',
    ];

    protected $casts = [
        'data_agendada' => 'datetime',
        'data_realizacao' => 'datetime',
        'data_encerramento' => 'datetime',
        'motivo_demanda' => 'encrypted',
        'resumo_sigiloso' => 'encrypted',
        'observacoes_restritas' => 'encrypted',
        'motivo_encerramento' => 'encrypted',
        'resumo_encerramento' => 'encrypted',
        'orientacoes_finais' => 'encrypted',
        'requer_acompanhamento' => 'boolean',
    ];

    public function escola()
    {
        return $this->belongsTo(Escola::class);
    }

    public function usuarioRegistro()
    {
        return $this->belongsTo(Usuario::class, 'usuario_registro_id');
    }

    public function profissionalResponsavel()
    {
        return $this->belongsTo(Funcionario::class, 'profissional_responsavel_id');
    }

    public function atendivel()
    {
        return $this->morphTo();
    }

    public function planosIntervencao()
    {
        return $this->hasMany(PlanoIntervencaoPsicossocial::class, 'atendimento_psicossocial_id')->latest();
    }

    public function encaminhamentos()
    {
        return $this->hasMany(EncaminhamentoPsicossocial::class, 'atendimento_psicossocial_id')->latest();
    }

    public function casosDisciplinares()
    {
        return $this->hasMany(CasoDisciplinarSigiloso::class, 'atendimento_psicossocial_id')->latest();
    }

    public function relatoriosTecnicos()
    {
        return $this->hasMany(RelatorioTecnicoPsicossocial::class, 'atendimento_psicossocial_id')->latest('data_emissao');
    }

    public function sessoes()
    {
        // chave estrangeira correta na tabela: atendimento_id
        return $this->hasMany(SessaoAtendimento::class, 'atendimento_id')->latest('data_sessao');
    }

    public function devolutivas()
    {
        return $this->hasMany(DevolutivaPsicossocial::class, 'atendimento_id')->latest('data_devolutiva');
    }

    public function reavaliacoes()
    {
        return $this->hasMany(ReavaliacaoPsicossocial::class, 'atendimento_id')->latest('data_reavaliacao');
    }

    public function demanda()
    {
        // demanda referencia atendimento via coluna atendimento_id
        return $this->hasOne(DemandaPsicossocial::class, 'atendimento_id');
    }

    public function getNomeAtendidoAttribute(): string
    {
        if ($this->atendivel instanceof Aluno) {
            return $this->atendivel->nome_completo;
        }

        if ($this->atendivel instanceof Funcionario) {
            return $this->atendivel->nome;
        }

        return $this->atendivel?->nome ?? 'Nao identificado';
    }
}
