<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatriculaHistorico extends Model
{
    use HasFactory;

    protected $table = 'matricula_historicos';

    public $timestamps = false; // Usamos apenas created_at manual ou via cast

    protected $fillable = [
        'matricula_id',
        'acao',
        'descricao',
        'usuario_id',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Relacionamento com a Matrícula.
     */
    public function matricula()
    {
        return $this->belongsTo(Matricula::class);
    }

    /**
     * Relacionamento com o Usuário que realizou a ação.
     */
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }
}
