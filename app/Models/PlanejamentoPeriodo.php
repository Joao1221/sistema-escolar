<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanejamentoPeriodo extends Model
{
    use HasFactory;

    const STATUS_RASCUNHO = 'rascunho';
    const STATUS_ENVIADO = 'enviado';
    const STATUS_APROVADO = 'aprovado';
    const STATUS_DEVOLVIDO = 'devolvido';

    protected $table = 'planejamentos_periodo';

    protected $fillable = [
        'diario_professor_id',
        'tipo_planejamento',
        'status',
        'tema_gerador',
        'periodo_referencia',
        'data_inicio',
        'data_fim',
        'objetivos_aprendizagem',
        'habilidades_competencias',
        'conteudos',
        'metodologia',
        'recursos_didaticos',
        'estrategias_pedagogicas',
        'instrumentos_avaliacao',
        'observacoes',
        'referencias',
        'adequacoes_inclusao',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
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
