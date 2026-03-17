<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RelatorioTecnicoPsicossocial extends Model
{
    use HasFactory;

    protected $table = 'relatorios_tecnicos_psicossociais';

    protected $fillable = [
        'atendimento_psicossocial_id',
        'escola_id',
        'usuario_emissor_id',
        'tipo_relatorio',
        'titulo',
        'conteudo_sigiloso',
        'data_emissao',
        'observacoes_restritas',
    ];

    protected $casts = [
        'data_emissao' => 'date',
        'conteudo_sigiloso' => 'encrypted',
        'observacoes_restritas' => 'encrypted',
    ];

    public function atendimento()
    {
        return $this->belongsTo(AtendimentoPsicossocial::class, 'atendimento_psicossocial_id');
    }
}
