<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiarioProfessor extends Model
{
    use HasFactory;

    protected $table = 'diarios_professor';

    protected $fillable = [
        'escola_id',
        'turma_id',
        'disciplina_id',
        'professor_id',
        'ano_letivo',
        'periodo_tipo',
        'periodo_referencia',
        'situacao',
        'observacoes_gerais',
    ];

    protected $casts = [
        'ano_letivo' => 'integer',
    ];

    public function escola()
    {
        return $this->belongsTo(Escola::class);
    }

    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function professor()
    {
        return $this->belongsTo(Funcionario::class, 'professor_id');
    }

    public function planejamentoAnual()
    {
        return $this->hasOne(PlanejamentoAnual::class);
    }

    public function planejamentosSemanais()
    {
        return $this->hasMany(PlanejamentoSemanal::class)->orderByDesc('data_inicio_semana');
    }

    public function planejamentosPeriodo()
    {
        return $this->hasMany(PlanejamentoPeriodo::class)
            ->orderByDesc('data_inicio')
            ->orderByDesc('created_at');
    }

    public function registrosAula()
    {
        return $this->hasMany(RegistroAula::class)->orderByDesc('data_aula');
    }

    public function lancamentosAvaliativos()
    {
        return $this->hasMany(LancamentoAvaliativo::class)
            ->orderBy('avaliacao_referencia')
            ->orderBy('matricula_id');
    }

    public function observacoesAluno()
    {
        return $this->hasMany(ObservacaoAluno::class)->orderByDesc('data_observacao');
    }

    public function ocorrencias()
    {
        return $this->hasMany(OcorrenciaDiario::class)->orderByDesc('data_ocorrencia');
    }

    public function pendencias()
    {
        return $this->hasMany(PendenciaProfessor::class)->orderByDesc('created_at');
    }

    public function validacoesPedagogicas()
    {
        return $this->hasMany(ValidacaoPedagogica::class)->orderByDesc('validado_em');
    }

    public function validacoesDirecao()
    {
        return $this->hasMany(ValidacaoDirecao::class)->orderByDesc('validado_em');
    }

    public function acompanhamentosPedagogicos()
    {
        return $this->hasMany(AcompanhamentoPedagogicoAluno::class)
            ->orderByDesc('precisa_intervencao')
            ->orderByDesc('updated_at');
    }

    public function justificativasFaltaAluno()
    {
        return $this->hasMany(JustificativaFaltaAluno::class)->orderByDesc('deferida_em');
    }

    public function liberacoesPrazo()
    {
        return $this->hasMany(LiberacaoPrazoProfessor::class)->orderByDesc('data_limite');
    }
}
