<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DevolutivaPsicossocial extends Model
{
    use HasFactory;

    protected $table = 'devolutivas_psicossociais';

    protected $fillable = [
        'atendimento_id',
        'usuario_responsavel_id',
        'destinatario',
        'nome_destinatario',
        'data_devolutiva',
        'resumo_devolutiva',
        'orientacoes',
        'encaminhamentos_combinados',
        'necessita_acompanhamento',
        'observacoes',
    ];

    protected $casts = [
        'data_devolutiva' => 'date',
        'necessita_acompanhamento' => 'boolean',
    ];

    protected $hidden = [
        'resumo_devolutiva',
        'orientacoes',
        'encaminhamentos_combinados',
        'observacoes',
    ];

    protected $appends = ['resumo_devolutiva', 'orientacoes', 'encaminhamentos_combinados', 'observacoes'];

    public function atendimento(): BelongsTo
    {
        return $this->belongsTo(AtendimentoPsicossocial::class, 'atendimento_id');
    }

    public function usuarioResponsavel(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_responsavel_id');
    }
}
