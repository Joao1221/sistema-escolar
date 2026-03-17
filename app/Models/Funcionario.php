<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Funcionario extends Model
{
    use HasFactory;

    protected $table = 'funcionarios';

    protected $fillable = [
        'nome',
        'cpf',
        'email',
        'telefone',
        'cargo',
        'ativo',
    ];

    public function escolas()
    {
        return $this->belongsToMany(Escola::class, 'funcionario_escola', 'funcionario_id', 'escola_id');
    }

    public function usuario()
    {
        return $this->hasOne(Usuario::class, 'funcionario_id');
    }

    public function diariosProfessor()
    {
        return $this->hasMany(DiarioProfessor::class, 'professor_id');
    }

    public function horariosAula()
    {
        return $this->hasMany(HorarioAula::class, 'professor_id');
    }

    public function liberacoesPrazo()
    {
        return $this->hasMany(LiberacaoPrazoProfessor::class, 'professor_id');
    }

    public function faltasRegistradas()
    {
        return $this->hasMany(FaltaFuncionario::class)->orderByDesc('data_falta');
    }
}
