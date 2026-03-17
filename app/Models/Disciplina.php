<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    use HasFactory;

    protected $table = 'disciplinas';

    protected $fillable = [
        'nome',
        'codigo',
        'carga_horaria_sugerida',
        'ativo',
    ];

    protected $casts = [
        'carga_horaria_sugerida' => 'integer',
        'ativo' => 'boolean',
    ];

    /**
     * Matrizes curriculares que contêm esta disciplina.
     */
    public function matrizes()
    {
        return $this->belongsToMany(MatrizCurricular::class, 'matriz_disciplina', 'disciplina_id', 'matriz_id')
            ->withPivot('carga_horaria', 'obrigatoria')
            ->withTimestamps();
    }

    public function diariosProfessor()
    {
        return $this->hasMany(DiarioProfessor::class);
    }
}
