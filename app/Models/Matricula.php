<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    protected $table = 'matriculas';

    protected $fillable = [
        'aluno_id',
        'escola_id',
        'turma_id',
        'ano_letivo',
        'tipo',
        'status',
        'matricula_regular_id',
        'data_matricula',
        'data_encerramento',
        'observacoes',
    ];

    protected $casts = [
        'data_matricula' => 'date',
        'data_encerramento' => 'date',
        'ano_letivo' => 'integer',
    ];

    /**
     * Relacionamento com o Aluno.
     */
    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    /**
     * Relacionamento com a Escola.
     */
    public function escola()
    {
        return $this->belongsTo(Escola::class);
    }

    /**
     * Relacionamento com a Turma.
     */
    public function turma()
    {
        return $this->belongsTo(Turma::class);
    }

    /**
     * Relacionamento com o Histórico.
     */
    public function historico()
    {
        return $this->hasMany(MatriculaHistorico::class);
    }

    /**
     * Se for uma matrícula AEE, pode estar vinculada a uma Regular.
     */
    public function matriculaRegular()
    {
        return $this->belongsTo(Matricula::class, 'matricula_regular_id');
    }

    /**
     * Se for uma matrícula Regular, pode ter uma vinculada de AEE.
     */
    public function matriculaAEE()
    {
        return $this->hasOne(Matricula::class, 'matricula_regular_id');
    }

    public function frequenciasAula()
    {
        return $this->hasMany(FrequenciaAula::class);
    }

    public function observacoesAluno()
    {
        return $this->hasMany(ObservacaoAluno::class);
    }

    public function ocorrenciasDiario()
    {
        return $this->hasMany(OcorrenciaDiario::class);
    }

    public function acompanhamentosPedagogicos()
    {
        return $this->hasMany(AcompanhamentoPedagogicoAluno::class);
    }

    public function justificativasFaltaAluno()
    {
        return $this->hasMany(JustificativaFaltaAluno::class);
    }

    public function lancamentosAvaliativos()
    {
        return $this->hasMany(LancamentoAvaliativo::class);
    }
}
