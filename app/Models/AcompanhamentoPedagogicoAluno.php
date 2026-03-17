<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcompanhamentoPedagogicoAluno extends Model
{
    use HasFactory;

    protected $table = 'acompanhamentos_pedagogicos_aluno';

    protected $fillable = [
        'diario_professor_id',
        'matricula_id',
        'usuario_coordenador_id',
        'nivel_rendimento',
        'situacao_risco',
        'percentual_frequencia',
        'indicativos_aprendizagem',
        'fatores_risco',
        'encaminhamentos',
        'precisa_intervencao',
    ];

    protected $casts = [
        'percentual_frequencia' => 'decimal:2',
        'precisa_intervencao' => 'boolean',
    ];

    public function diarioProfessor()
    {
        return $this->belongsTo(DiarioProfessor::class);
    }

    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }

    public function usuarioCoordenador()
    {
        return $this->belongsTo(Usuario::class, 'usuario_coordenador_id');
    }
}
