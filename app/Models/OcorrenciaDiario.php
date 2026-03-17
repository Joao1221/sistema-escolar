<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OcorrenciaDiario extends Model
{
    use HasFactory;

    protected $table = 'ocorrencias_diario';

    protected $fillable = [
        'diario_professor_id',
        'matricula_id',
        'usuario_registro_id',
        'data_ocorrencia',
        'tipo',
        'descricao',
        'providencias',
        'status',
    ];

    protected $casts = [
        'data_ocorrencia' => 'date',
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
