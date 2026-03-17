<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendenciaProfessor extends Model
{
    use HasFactory;

    protected $table = 'pendencias_professor';

    protected $fillable = [
        'diario_professor_id',
        'professor_id',
        'usuario_registro_id',
        'titulo',
        'descricao',
        'origem',
        'prazo',
        'status',
        'resolvida_em',
    ];

    protected $casts = [
        'prazo' => 'date',
        'resolvida_em' => 'datetime',
    ];

    public function diarioProfessor()
    {
        return $this->belongsTo(DiarioProfessor::class);
    }

    public function professor()
    {
        return $this->belongsTo(Funcionario::class, 'professor_id');
    }

    public function usuarioRegistro()
    {
        return $this->belongsTo(Usuario::class, 'usuario_registro_id');
    }
}
