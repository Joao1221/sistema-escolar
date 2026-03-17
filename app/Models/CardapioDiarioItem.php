<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardapioDiarioItem extends Model
{
    use HasFactory;

    protected $table = 'cardapio_diario_itens';

    protected $fillable = [
        'cardapio_diario_id',
        'alimento_id',
        'refeicao',
        'quantidade_prevista',
        'observacoes',
    ];

    protected $casts = [
        'quantidade_prevista' => 'decimal:3',
    ];

    public function cardapio()
    {
        return $this->belongsTo(CardapioDiario::class, 'cardapio_diario_id');
    }

    public function alimento()
    {
        return $this->belongsTo(Alimento::class);
    }
}
