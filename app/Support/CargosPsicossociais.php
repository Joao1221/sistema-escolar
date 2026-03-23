<?php

namespace App\Support;

use Illuminate\Support\Str;

class CargosPsicossociais
{
    public static function labels(): array
    {
        return [
            'Psicólogo',
            'Psicopedagogo',
        ];
    }

    public static function contains(?string $cargo): bool
    {
        if (! $cargo) {
            return false;
        }

        $cargoNormalizado = Str::lower(Str::ascii($cargo));

        return collect(self::labels())
            ->map(fn (string $label) => Str::lower(Str::ascii($label)))
            ->contains($cargoNormalizado);
    }
}
