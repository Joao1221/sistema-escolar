<?php

namespace App\Enums;

enum StatusPendenciaDiario: string
{
    case Aberta = 'aberta';
    case EmAndamento = 'em_andamento';
    case Resolvida = 'resolvida';

    public function label(): string
    {
        return match ($this) {
            self::Aberta => 'Aberta',
            self::EmAndamento => 'Em andamento',
            self::Resolvida => 'Resolvida',
        };
    }
}