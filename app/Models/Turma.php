<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    use HasFactory;

    protected $table = 'turmas';

    protected $fillable = [
        'escola_id',
        'modalidade_id',
        'matriz_id',
        'serie_etapa',
        'nome',
        'turno',
        'ano_letivo',
        'vagas',
        'is_multisseriada',
        'ativa',
    ];

    protected $casts = [
        'is_multisseriada' => 'boolean',
        'ativa' => 'boolean',
        'ano_letivo' => 'integer',
        'vagas' => 'integer',
    ];

    /**
     * Scope para pegar turmas ativas
     */
    public function scopeActive($query)
    {
        return $query->where('ativa', true);
    }

    /**
     * Relacionamento com a Escola.
     */
    public function escola()
    {
        return $this->belongsTo(Escola::class, 'escola_id');
    }

    /**
     * Relacionamento com a Matriz Curricular.
     */
    public function matriz()
    {
        return $this->belongsTo(MatrizCurricular::class, 'matriz_id');
    }

    /**
     * Relacionamento com a Modalidade de Ensino.
     */
    public function modalidade()
    {
        return $this->belongsTo(ModalidadeEnsino::class, 'modalidade_id');
    }

    /**
     * Relacionamento com as Matrículas da turma.
     */
    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }

    public function diariosProfessor()
    {
        return $this->hasMany(DiarioProfessor::class);
    }
}
