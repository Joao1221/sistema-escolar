<?php

namespace App\Enums;

enum StatusDemandaPsicossocial: string
{
    case Aberta = 'aberta';
    case EmTriagem = 'em_triagem';
    case EmAtendimento = 'em_atendimento';
    case Encerrada = 'encerrada';
    case Observacao = 'observacao';

    public function label(): string
    {
        return match ($this) {
            self::Aberta => 'Aberta',
            self::EmTriagem => 'Em triagem',
            self::EmAtendimento => 'Em atendimento',
            self::Encerrada => 'Encerrada',
            self::Observacao => 'Observação',
        };
    }
}