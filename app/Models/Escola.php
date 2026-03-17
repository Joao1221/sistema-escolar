<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Escola extends Model
{
    use HasFactory;

    protected $table = 'escolas';

    protected $fillable = [
        'nome',
        'cnpj',
        'email',
        'telefone',
        'cep',
        'endereco',
        'bairro',
        'cidade',
        'uf',
        'nome_gestor',
        'cpf_gestor',
        'ativo',
    ];

    public function scopeActive($query)
    {
        return $query->where('ativo', true);
    }

    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'usuarios_escolas', 'escola_id', 'usuario_id');
    }

    public function funcionarios()
    {
        return $this->belongsToMany(Funcionario::class, 'funcionario_escola', 'escola_id', 'funcionario_id');
    }

    /**
     * Relacionamento com as Matrículas da escola.
     */
    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }

    public function diariosProfessor()
    {
        return $this->hasMany(DiarioProfessor::class);
    }

    public function movimentacoesAlimentos()
    {
        return $this->hasMany(MovimentacaoAlimento::class);
    }

    public function cardapiosDiarios()
    {
        return $this->hasMany(CardapioDiario::class);
    }
}
