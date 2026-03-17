<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FrequenciaAula extends Model
{
    use HasFactory;

    protected $table = 'frequencias_aula';

    protected $fillable = [
        'registro_aula_id',
        'matricula_id',
        'situacao',
        'justificativa',
        'observacao',
    ];

    public function justificativaDirecao()
    {
        return $this->hasOne(JustificativaFaltaAluno::class);
    }

    public function registroAula()
    {
        return $this->belongsTo(RegistroAula::class);
    }

    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }
}
