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
    ];

    protected $casts = [
        'data_agendada' => 'datetime',
        'data_realizacao' => 'datetime',
        'motivo_demanda' => 'encrypted',
        'resumo_sigiloso' => 'encrypted',
        'observacoes_restritas' => 'encrypted',
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
        return $this->hasMany(PlanoIntervencaoPsicossocial::class)->latest();
    }

    public function encaminhamentos()
    {
        return $this->hasMany(EncaminhamentoPsicossocial::class)->latest();
    }

    public function casosDisciplinares()
    {
        return $this->hasMany(CasoDisciplinarSigiloso::class)->latest();
    }

    public function relatoriosTecnicos()
    {
        return $this->hasMany(RelatorioTecnicoPsicossocial::class)->latest('data_emissao');
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
