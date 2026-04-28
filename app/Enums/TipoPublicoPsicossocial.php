<?php

namespace App\Enums;

enum TipoPublicoPsicossocial: string
{
    case Aluno = 'aluno';
    case Professor = 'professor';
    case Funcionario = 'funcionario';
    case Responsavel = 'responsavel';
    case Coletivo = 'coletivo';

    public function label(): string
    {
        return match ($this) {
            self::Aluno => 'Aluno',
            self::Professor => 'Professor',
            self::Funcionario => 'Funcionário',
            self::Responsavel => 'Responsável',
            self::Coletivo => 'Coletivo',
        };
    }
}