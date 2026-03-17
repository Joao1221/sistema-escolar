<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CardapioDiario extends Model
{
    use HasFactory;

    protected $table = 'cardapios_diarios';

    protected $fillable = [
        'escola_id',
        'usuario_id',
        'data_cardapio',
        'observacoes',
    ];

    protected $casts = [
        'data_cardapio' => 'date',
    ];

    public function escola()
    {
        return $this->belongsTo(Escola::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function itens()
    {
        return $this->hasMany(CardapioDiarioItem::class, 'cardapio_diario_id');
    }
}
