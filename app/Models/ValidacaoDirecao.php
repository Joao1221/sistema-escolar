<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidacaoDirecao extends Model
{
    use HasFactory;

    protected $table = 'validacoes_direcao';

    protected $fillable = [
        'diario_professor_id',
        'usuario_direcao_id',
        'status',
        'parecer',
        'observacoes_internas',
        'validado_em',
    ];

    protected $casts = [
        'validado_em' => 'datetime',
    ];

    public function diarioProfessor()
    {
        return $this->belongsTo(DiarioProfessor::class);
    }

    public function validavel()
    {
        return $this->morphTo();
    }

    public function usuarioDirecao()
    {
        return $this->belongsTo(Usuario::class, 'usuario_direcao_id');
    }
}
