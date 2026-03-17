<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JustificativaFaltaAluno extends Model
{
    use HasFactory;

    protected $table = 'justificativas_falta_aluno';

    protected $fillable = [
        'frequencia_aula_id',
        'diario_professor_id',
        'matricula_id',
        'usuario_direcao_id',
        'situacao_anterior',
        'situacao_atual',
        'motivo',
        'documento_comprobatorio',
        'deferida_em',
    ];

    protected $casts = [
        'deferida_em' => 'datetime',
    ];

    public function frequenciaAula()
    {
        return $this->belongsTo(FrequenciaAula::class);
    }

    public function diarioProfessor()
    {
        return $this->belongsTo(DiarioProfessor::class);
    }

    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }

    public function usuarioDirecao()
    {
        return $this->belongsTo(Usuario::class, 'usuario_direcao_id');
    }
}
