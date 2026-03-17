<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaAlimento extends Model
{
    use HasFactory;

    protected $table = 'categorias_alimentos';

    protected $fillable = [
        'nome',
        'descricao',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function alimentos()
    {
        return $this->hasMany(Alimento::class, 'categoria_alimento_id');
    }
}
