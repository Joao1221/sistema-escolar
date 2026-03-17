<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaltaFuncionario extends Model
{
    use HasFactory;

    protected $table = 'faltas_funcionarios';

    protected $fillable = [
        'escola_id',
        'funcionario_id',
        'usuario_registro_id',
        'data_falta',
        'turno',
        'tipo_falta',
        'justificada',
        'motivo',
        'observacoes',
    ];

    protected $casts = [
        'data_falta' => 'date',
        'justificada' => 'boolean',
    ];

    public function escola()
    {
        return $this->belongsTo(Escola::class);
    }

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }

    public function usuarioRegistro()
    {
        return $this->belongsTo(Usuario::class, 'usuario_registro_id');
    }
}
