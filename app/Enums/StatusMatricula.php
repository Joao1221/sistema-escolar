<?php

namespace App\Enums;

enum StatusMatricula: string
{
    case Ativa = 'ativa';
    case Concluida = 'concluida';
    case Trancada = 'trancada';
    case Transferida = 'transferida';
    case Cancelada = 'cancelada';

    public function label(): string
    {
        return match ($this) {
            self::Ativa => 'Ativa',
            self::Concluida => 'Concluída',
            self::Trancada => 'Trancada',
            self::Transferida => 'Transferida',
            self::Cancelada => 'Cancelada',
        };
    }
}