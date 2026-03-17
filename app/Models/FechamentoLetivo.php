<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FechamentoLetivo extends Model
{
    use HasFactory;

    protected $table = 'fechamentos_letivos';

    protected $fillable = [
        'escola_id',
        'ano_letivo',
        'usuario_direcao_id',
        'status',
        'resumo',
        'observacoes',
        'iniciado_em',
        'concluido_em',
    ];

    protected $casts = [
        'ano_letivo' => 'integer',
        'iniciado_em' => 'datetime',
        'concluido_em' => 'datetime',
    ];

    public function escola()
    {
        return $this->belongsTo(Escola::class);
    }

    public function usuarioDirecao()
    {
        return $this->belongsTo(Usuario::class, 'usuario_direcao_id');
    }
}
