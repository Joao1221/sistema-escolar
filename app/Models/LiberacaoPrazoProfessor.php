<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiberacaoPrazoProfessor extends Model
{
    use HasFactory;

    protected $table = 'liberacoes_prazo_professor';

    protected $fillable = [
        'diario_professor_id',
        'professor_id',
        'usuario_direcao_id',
        'tipo_lancamento',
        'data_limite',
        'status',
        'motivo',
        'observacoes',
    ];

    protected $casts = [
        'data_limite' => 'date',
    ];

    public function diarioProfessor()
    {
        return $this->belongsTo(DiarioProfessor::class);
    }

    public function professor()
    {
        return $this->belongsTo(Funcionario::class, 'professor_id');
    }

    public function usuarioDirecao()
    {
        return $this->belongsTo(Usuario::class, 'usuario_direcao_id');
    }
}
