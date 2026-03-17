<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegistroAuditoria extends Model
{
    use HasFactory;

    protected $table = 'registros_auditoria';

    protected $fillable = [
        'usuario_id',
        'escola_id',
        'modulo',
        'acao',
        'tipo_registro',
        'registro_type',
        'registro_id',
        'nivel_sensibilidade',
        'ip',
        'user_agent',
        'valores_antes',
        'valores_depois',
        'contexto',
    ];

    protected $casts = [
        'valores_antes' => 'array',
        'valores_depois' => 'array',
        'contexto' => 'array',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function escola()
    {
        return $this->belongsTo(Escola::class);
    }
}
