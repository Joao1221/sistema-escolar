<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HorarioAula extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'horario_aulas';

    protected $fillable = [
        'escola_id',
        'turma_id',
        'disciplina_id',
        'professor_id',
        'dia_semana',
        'horario_inicial',
        'horario_final',
        'ordem_aula',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'dia_semana' => 'integer',
        'ordem_aula' => 'integer',
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

    public function registrosAula()
    {
        return $this->hasMany(RegistroAula::class);
    }
}
