<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aluno extends Model
{
    use HasFactory;

    protected $table = 'alunos';

    protected $fillable = [
        'escola_id',
        'rgm',
        'nome_completo',
        'nome_social',
        'email',
        'data_nascimento',
        'sexo',
        'cpf',
        'nis',
        'inep',
        'naturalidade',
        'uf_nascimento',
        'zona',
        'raca',
        'nacionalidade',
        'nome_mae',
        'nome_pai',
        'responsavel_nome',
        'responsavel_cpf',
        'responsavel_telefone',
        'telefone',
        'celular',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'uf',
        'rg_numero',
        'rg_orgao',
        'rg_uf',
        'rg_data_expedicao',
        'passaporte',
        'pais_origem',
        'certidao_nascimento',
        'tipo_certidao',
        'modelo_certidao',
        'certidao_termo',
        'certidao_folha',
        'certidao_livro',
        'certidao_matricula',
        'certidao_cartorio',
        'certidao_localidade',
        'certidao_uf',
        'certidao_data_emissao',
        'profissao_mae',
        'profissao_pai',
        'profissao_responsavel',
        'alergias',
        'medicamentos',
        'restricoes_alimentares',
        'obs_saude',
        'ativo',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'rg_data_expedicao' => 'date',
        'certidao_data_emissao' => 'date',
        'ativo' => 'boolean',
    ];

    /**
     * Relacionamento com as Matrículas do aluno.
     */
    public function matriculas(): HasMany
    {
        return $this->hasMany(Matricula::class);
    }

    /**
     * Relacionamento com endereços.
     */
    public function enderecos()
    {
        return $this->hasMany(AlunoEndereco::class);
    }

    /**
     * Relacionamento com dados de saúde.
     */
    public function saude()
    {
        return $this->hasOne(AlunoSaude::class);
    }

    /**
     * Relacionamento com autorizações.
     */
    public function autorizacoes()
    {
        return $this->hasOne(AlunoAutorizacao::class);
    }

    /**
     * Retorna a idade do aluno.
     */
    public function getIdadeAttribute(): int
    {
        return $this->data_nascimento->age ?? 0;
    }
}
