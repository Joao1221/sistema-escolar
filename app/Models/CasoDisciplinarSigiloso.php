<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CasoDisciplinarSigiloso extends Model
{
    use HasFactory;

    protected $table = 'casos_disciplinares_sigilosos';

    protected $fillable = [
        'atendimento_psicossocial_id',
        'escola_id',
        'aluno_id',
        'funcionario_id',
        'usuario_id',
        'data_ocorrencia',
        'titulo',
        'descricao_sigilosa',
        'medidas_adotadas',
        'status',
    ];

    protected $casts = [
        'data_ocorrencia' => 'date',
        'descricao_sigilosa' => 'encrypted',
        'medidas_adotadas' => 'encrypted',
    ];

    public function atendimento()
    {
        return $this->belongsTo(AtendimentoPsicossocial::class, 'atendimento_psicossocial_id');
    }

    public function escola()
    {
        return $this->belongsTo(Escola::class);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}
