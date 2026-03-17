<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LancamentoAvaliativo extends Model
{
    use HasFactory;

    protected $table = 'lancamentos_avaliativos';

    protected $fillable = [
        'diario_professor_id',
        'matricula_id',
        'usuario_registro_id',
        'tipo_avaliacao',
        'avaliacao_referencia',
        'valor_numerico',
        'conceito',
        'observacoes',
    ];

    protected $casts = [
        'valor_numerico' => 'decimal:2',
    ];

    public function diarioProfessor()
    {
        return $this->belongsTo(DiarioProfessor::class);
    }

    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }

    public function usuarioRegistro()
    {
        return $this->belongsTo(Usuario::class, 'usuario_registro_id');
    }
}
