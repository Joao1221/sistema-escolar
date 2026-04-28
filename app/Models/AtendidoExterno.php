<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtendidoExterno extends Model
{
    use HasFactory;

    protected $table = 'atendidos_externos';

    protected $fillable = [
        'escola_id',
        'aluno_id',
        'nome',
        'tipo_vinculo',
        'cpf',
        'telefone',
        'email',
        'observacoes',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function escola()
    {
        return $this->belongsTo(Escola::class);
    }

    public function aluno()
    {
        return $this->belongsTo(Aluno::class);
    }

    public function atendimentos()
    {
        return $this->morphMany(AtendimentoPsicossocial::class, 'atendivel');
    }
}
