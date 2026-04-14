<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }

    /**
     * Relacionamento com a Escola.
     */
    public function escola(): BelongsTo
    {
        return $this->belongsTo(Escola::class);
    }

    /**
     * Relacionamento com a Turma.
     */
    public function turma(): BelongsTo
    {
        return $this->belongsTo(Turma::class);
    }

    /**
     * Relacionamento com o Histórico.
     */
    public function historico(): HasMany
    {
        return $this->hasMany(MatriculaHistorico::class);
    }

    /**
     * Se for uma matrícula AEE, pode estar vinculada a uma Regular.
     */
    public function matriculaRegular(): BelongsTo
    {
        return $this->belongsTo(Matricula::class, 'matricula_regular_id');
    }

    /**
     * Se for uma matrícula Regular, pode ter uma vinculada de AEE.
     */
    public function matriculaAEE(): HasOne
    {
        return $this->hasOne(Matricula::class, 'matricula_regular_id');
    }

    public function frequenciasAula(): HasMany
    {
        return $this->hasMany(FrequenciaAula::class);
    }

    public function observacoesAluno(): HasMany
    {
        return $this->hasMany(ObservacaoAluno::class);
    }

    public function ocorrenciasDiario(): HasMany
    {
        return $this->hasMany(OcorrenciaDiario::class);
    }

    public function acompanhamentosPedagogicos(): HasMany
    {
        return $this->hasMany(AcompanhamentoPedagogicoAluno::class);
    }

    public function justificativasFaltaAluno(): HasMany
    {
        return $this->hasMany(JustificativaFaltaAluno::class);
    }

    public function lancamentosAvaliativos(): HasMany
    {
        return $this->hasMany(LancamentoAvaliativo::class);
    }
}
