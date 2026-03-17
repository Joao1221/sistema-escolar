<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanejamentoSemanal extends Model
{
    use HasFactory;

    protected $table = 'planejamentos_semanais';

    protected $fillable = [
        'diario_professor_id',
        'data_inicio_semana',
        'data_fim_semana',
        'objetivos_semana',
        'conteudos_previstos',
        'estrategias',
        'avaliacao_prevista',
        'observacoes',
    ];

    protected $casts = [
        'data_inicio_semana' => 'date',
        'data_fim_semana' => 'date',
    ];

    public function diarioProfessor()
    {
        return $this->belongsTo(DiarioProfessor::class);
    }

    public function validacaoPedagogica()
    {
        return $this->morphOne(ValidacaoPedagogica::class, 'validavel');
    }

    public function validacaoDirecao()
    {
        return $this->morphOne(ValidacaoDirecao::class, 'validavel');
    }
}
