<?php

namespace App\Policies;

use App\Models\AtendimentoPsicossocial;
use App\Models\Usuario;

class AtendimentoPsicossocialPolicy
{
    public function viewAny(Usuario $usuario): bool
    {
        return $usuario->can('acessar modulo psicossocial') && $usuario->can('acessar dados sigilosos psicossociais');
    }

    public function view(Usuario $usuario, AtendimentoPsicossocial $atendimento): bool
    {
        return $atendimento->visivelParaUsuario($usuario)
            && $usuario->can('acessar dados sigilosos psicossociais');
    }

    public function create(Usuario $usuario): bool
    {
        return $usuario->can('registrar atendimentos psicossociais');
    }

    public function createPlano(Usuario $usuario, AtendimentoPsicossocial $atendimento): bool
    {
        return $this->view($usuario, $atendimento) && $usuario->can('registrar planos de intervencao psicossociais');
    }

    public function createEncaminhamento(Usuario $usuario, AtendimentoPsicossocial $atendimento): bool
    {
        return $this->view($usuario, $atendimento) && $usuario->can('registrar encaminhamentos psicossociais');
    }

    public function createCaso(Usuario $usuario, AtendimentoPsicossocial $atendimento): bool
    {
        return $this->view($usuario, $atendimento) && $usuario->can('registrar casos disciplinares sigilosos');
    }

    public function emitirRelatorio(Usuario $usuario, AtendimentoPsicossocial $atendimento): bool
    {
        return $this->view($usuario, $atendimento) && $usuario->can('emitir relatorios tecnicos psicossociais');
    }
}
