<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidacaoPedagogica extends Model
{
    use HasFactory;

    protected $table = 'validacoes_pedagogicas';

    protected $fillable = [
        'diario_professor_id',
        'usuario_coordenador_id',
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

    public function usuarioCoordenador()
    {
        return $this->belongsTo(Usuario::class, 'usuario_coordenador_id');
    }
}
