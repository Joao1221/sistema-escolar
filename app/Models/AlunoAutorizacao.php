<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlunoAutorizacao extends Model
{
    use HasFactory;

    protected $table = 'aluno_autorizacoes';

    protected $fillable = [
        'aluno_id',
        'aut_uso_imagem',
        'aut_passeios',
        'aut_tratamento_dados',
        'aut_saida',
        'aut_saida_nome',
        'aut_saida_parentesco',
        'aut_saida_fone',
    ];

    protected $casts = [
        'aut_uso_imagem' => 'boolean',
        'aut_tratamento_dados' => 'boolean',
        'aut_saida' => 'boolean',
    ];

    public function aluno(): BelongsTo
    {
        return $this->belongsTo(Aluno::class);
    }
}