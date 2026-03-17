<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instituicao extends Model
{
    protected $table = 'instituicoes';

    protected $fillable = [
        'nome_prefeitura',
        'cnpj_prefeitura',
        'nome_prefeito',
        'nome_secretaria',
        'sigla_secretaria',
        'nome_secretario',
        'endereco',
        'telefone',
        'email',
        'municipio',
        'uf',
        'cep',
        'brasao_path',
        'logo_prefeitura_path',
        'logo_secretaria_path',
        'textos_institucionais',
        'assinaturas_cargos',
    ];
}
