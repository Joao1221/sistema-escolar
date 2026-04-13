<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    use HasFactory;

    protected $table = 'alunos';

    protected $fillable = [
        'escola_id',
        'rgm',
        'nome_completo',
        'data_nascimento',
        'sexo',
        'cpf',
        'nis',
        'nome_mae',
        'nome_pai',
        'responsavel_nome',
        'responsavel_cpf',
        'responsavel_telefone',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'uf',
        'certidao_nascimento',
        'rg_numero',
        'rg_orgao',
        'alergias',
        'medicamentos',
        'restricoes_alimentares',
        'obs_saude',
        'ativo',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'ativo' => 'boolean',
    ];

    /**
     * Relacionamento com as Matrículas do aluno.
     */
    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }

    /**
     * Retorna a idade do aluno.
     */
    public function getIdadeAttribute()
    {
        return $this->data_nascimento->age;
    }
}
