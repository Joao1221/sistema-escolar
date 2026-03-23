<?php

namespace App\Models;

use App\Support\ArquivoPublicoUrl;
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

    protected $appends = [
        'brasao_url',
        'logo_prefeitura_url',
        'logo_secretaria_url',
    ];

    public function getBrasaoUrlAttribute(): ?string
    {
        return ArquivoPublicoUrl::resolver($this->brasao_path);
    }

    public function getLogoPrefeituraUrlAttribute(): ?string
    {
        return ArquivoPublicoUrl::resolver($this->logo_prefeitura_path);
    }

    public function getLogoSecretariaUrlAttribute(): ?string
    {
        return ArquivoPublicoUrl::resolver($this->logo_secretaria_path);
    }
}
