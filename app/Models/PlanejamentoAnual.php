<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanejamentoAnual extends Model
{
    use HasFactory;

    const STATUS_RASCUNHO = 'rascunho';
    const STATUS_ENVIADO = 'enviado';
    const STATUS_APROVADO = 'aprovado';
    const STATUS_DEVOLVIDO = 'devolvido';

    protected $table = 'planejamentos_anuais';

    protected $fillable = [
        'diario_professor_id',
        'unidade',
        'status',
        'tema_gerador',
        'periodo_vigencia_inicio',
        'periodo_vigencia_fim',
        'objetivos_gerais',
        'competencias_habilidades',
        'conteudos',
        'metodologia',
        'recursos_didaticos',
        'estrategias_pedagogicas',
        'estrategias_metodologicas',
        'instrumentos_avaliacao',
        'criterios_avaliacao',
        'cronograma_previsto',
        'referencias',
        'observacoes',
        'adequacoes_inclusao',
    ];

    protected $casts = [
        'periodo_vigencia_inicio' => 'date',
        'periodo_vigencia_fim' => 'date',
        'unidade' => 'integer',
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
