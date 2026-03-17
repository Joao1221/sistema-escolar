<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ObservacaoAluno extends Model
{
    use HasFactory;

    protected $table = 'observacoes_aluno';

    protected $fillable = [
        'diario_professor_id',
        'matricula_id',
        'usuario_registro_id',
        'data_observacao',
        'categoria',
        'descricao',
        'encaminhamento',
        'destaque',
    ];

    protected $casts = [
        'data_observacao' => 'date',
        'destaque' => 'boolean',
    ];

    public function diarioProfessor()
    {
        return $this->belongsTo(DiarioProfessor::class);
    }

    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }

    public function usuarioRegistro()
    {
        return $this->belongsTo(Usuario::class, 'usuario_registro_id');
    }
}
