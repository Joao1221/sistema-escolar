<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alimento extends Model
{
    use HasFactory;

    protected $table = 'alimentos';

    protected $fillable = [
        'categoria_alimento_id',
        'nome',
        'unidade_medida',
        'estoque_minimo',
        'controla_validade',
        'observacoes',
        'ativo',
    ];

    protected $casts = [
        'estoque_minimo' => 'decimal:3',
        'controla_validade' => 'boolean',
        'ativo' => 'boolean',
    ];

    public function categoria()
    {
        return $this->belongsTo(CategoriaAlimento::class, 'categoria_alimento_id');
    }

    public function movimentacoes()
    {
        return $this->hasMany(MovimentacaoAlimento::class);
    }

    public function itensCardapio()
    {
        return $this->hasMany(CardapioDiarioItem::class);
    }
}
