<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FornecedorAlimento extends Model
{
    use HasFactory;

    protected $table = 'fornecedores_alimentos';

    protected $fillable = [
        'nome',
        'cnpj',
        'contato_nome',
        'telefone',
        'email',
        'cidade',
        'uf',
        'observacoes',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function movimentacoes()
    {
        return $this->hasMany(MovimentacaoAlimento::class, 'fornecedor_alimento_id');
    }
}
