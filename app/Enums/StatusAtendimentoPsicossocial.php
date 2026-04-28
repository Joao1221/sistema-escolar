<?php

namespace App\Enums;

enum StatusAtendimentoPsicossocial: string
{
    case Aberto = 'aberto';
    case EmAtendimento = 'em_atendimento';
    case Encerrado = 'encerrado';

    public function label(): string
    {
        return match ($this) {
            self::Aberto => 'Aberto',
            self::EmAtendimento => 'Em atendimento',
            self::Encerrado => 'Encerrado',
        };
    }
}