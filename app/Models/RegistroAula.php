<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroAula extends Model
{
    use HasFactory;

    protected $table = 'registros_aula';

    protected $fillable = [
        'diario_professor_id',
        'horario_aula_id',
        'usuario_registro_id',
        'data_aula',
        'titulo',
        'conteudo_previsto',
        'conteudo_ministrado',
        'metodologia',
        'recursos_utilizados',
        'quantidade_aulas',
        'aula_dada',
    ];

    protected $casts = [
        'data_aula' => 'date',
        'quantidade_aulas' => 'integer',
        'aula_dada' => 'boolean',
    ];

    public function diarioProfessor()
    {
        return $this->belongsTo(DiarioProfessor::class);
    }

    public function horarioAula()
    {
        return $this->belongsTo(HorarioAula::class);
    }

    public function usuarioRegistro()
    {
        return $this->belongsTo(Usuario::class, 'usuario_registro_id');
    }

    public function frequencias()
    {
        return $this->hasMany(FrequenciaAula::class)->with(['matricula.aluno']);
    }

    public function validacaoPedagogica()
    {
        return $this->morphOne(ValidacaoPedagogica::class, 'validavel');
    }

    public function validacaoDirecao()
    {
        return $this->morphOne(ValidacaoDirecao::class, 'validavel');
    }
}
