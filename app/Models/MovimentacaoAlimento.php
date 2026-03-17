<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovimentacaoAlimento extends Model
{
    use HasFactory;

    protected $table = 'movimentacoes_alimentos';

    protected $fillable = [
        'escola_id',
        'alimento_id',
        'fornecedor_alimento_id',
        'usuario_id',
        'tipo',
        'quantidade',
        'saldo_resultante',
        'data_movimentacao',
        'data_validade',
        'lote',
        'valor_unitario',
        'observacoes',
    ];

    protected $casts = [
        'quantidade' => 'decimal:3',
        'saldo_resultante' => 'decimal:3',
        'valor_unitario' => 'decimal:2',
        'data_movimentacao' => 'date',
        'data_validade' => 'date',
    ];

    public function escola()
    {
        return $this->belongsTo(Escola::class);
    }

    public function alimento()
    {
        return $this->belongsTo(Alimento::class);
    }

    public function fornecedor()
    {
        return $this->belongsTo(FornecedorAlimento::class, 'fornecedor_alimento_id');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
