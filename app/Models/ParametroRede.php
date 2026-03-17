<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParametroRede extends Model
{
    protected $table = 'parametros_rede';

    protected $fillable = [
        'ano_letivo_vigente',
        'dias_letivos_minimos',
        'media_minima',
        'frequencia_minima',
        'parametros_documentos',
        'parametros_upload'
    ];

    protected $casts = [
        'ano_letivo_vigente' => 'integer',
        'dias_letivos_minimos' => 'integer',
        'media_minima' => 'decimal:2',
        'frequencia_minima' => 'integer',
    ];
}
