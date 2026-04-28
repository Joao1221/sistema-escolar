<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlunoEndereco extends Model
{
    use HasFactory;

    protected $table = 'aluno_enderecos';

    protected $fillable = [
        'aluno_id',
        'tipo',
        'zona',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'uf',
        'principal',
    ];

    protected $casts = [
        'principal' => 'boolean',
    ];

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }
}