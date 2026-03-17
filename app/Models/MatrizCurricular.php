<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatrizCurricular extends Model
{
    use HasFactory;

    protected $table = 'matrizes_curriculares';

    protected $fillable = [
        'nome',
        'modalidade_id',
        'serie_etapa',
        'escola_id',
        'ano_vigencia',
        'ativa',
    ];

    protected $casts = [
        'ano_vigencia' => 'integer',
        'ativa' => 'boolean',
    ];

    /**
     * Relacionamento com a Modalidade de Ensino.
     */
    public function modalidade()
    {
        return $this->belongsTo(ModalidadeEnsino::class, 'modalidade_id');
    }

    /**
     * Relacionamento com a Escola (se a matriz for específica).
     */
    public function escola()
    {
        return $this->belongsTo(Escola::class, 'escola_id');
    }

    /**
     * Disciplinas vinculadas a esta matriz.
     */
    public function disciplinas()
    {
        return $this->belongsToMany(Disciplina::class, 'matriz_disciplina', 'matriz_id', 'disciplina_id')
            ->withPivot('carga_horaria', 'obrigatoria')
            ->withTimestamps();
    }

    /**
     * Turmas que utilizam esta matriz.
     */
    public function turmas()
    {
        return $this->hasMany(Turma::class, 'matriz_id');
    }
}
