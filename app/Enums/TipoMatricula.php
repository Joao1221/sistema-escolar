<?php

namespace App\Enums;

enum TipoMatricula: string
{
    case Regular = 'regular';
    case Aee = 'aee';

    public function label(): string
    {
        return match ($this) {
            self::Regular => 'Regular',
            self::Aee => 'AEE',
        };
    }
}