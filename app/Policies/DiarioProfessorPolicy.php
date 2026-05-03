<?php

namespace App\Policies;

use App\Models\DiarioProfessor;
use App\Models\Funcionario;
use App\Models\Usuario;

class DiarioProfessorPolicy
{
    public function viewAny(Usuario $usuario): bool
    {
        return $usuario->can('consultar diarios') || $usuario->can('criar diarios');
    }

    public function view(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $this->podeConsultar($usuario, $diario) || $this->podeGerenciarComoProfessor($usuario, $diario);
    }

    public function create(Usuario $usuario): bool
    {
        return $usuario->can('criar diarios') && $this->resolverFuncionarioId($usuario) !== null;
    }

    public function registrarAula(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('registrar aulas') && $this->podeGerenciarComoProfessor($usuario, $diario);
    }

    public function lancarFrequencia(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('lancar frequencia') && $this->podeGerenciarComoProfessor($usuario, $diario);
    }

    public function gerenciarPlanejamento(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('gerenciar planejamentos') && $this->podeGerenciarComoProfessor($usuario, $diario);
    }

    public function registrarObservacao(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('registrar observacoes pedagogicas') && $this->podeGerenciarComoProfessor($usuario, $diario);
    }

    public function registrarOcorrencia(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('registrar ocorrencias pedagogicas') && $this->podeGerenciarComoProfessor($usuario, $diario);
    }

    public function gerenciarPendencia(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('gerenciar pendencias do professor') && $this->podeGerenciarComoProfessor($usuario, $diario);
    }

    public function consultarPedagogico(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $this->podeConsultar($usuario, $diario);
    }

    public function acompanharCoordenacao(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('acompanhar diarios pedagogicamente') && $this->podeConsultar($usuario, $diario);
    }

    public function validarPlanejamentoAnual(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('validar planejamento anual') && $this->podeConsultar($usuario, $diario);
    }

    public function validarRegistroAula(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('validar aulas registradas') && $this->podeConsultar($usuario, $diario);
    }

    public function validarPlanejamentoPeriodo(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('validar planejamento por periodo') && $this->podeConsultar($usuario, $diario);
    }

    public function acompanharFrequenciaPedagogica(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('acompanhar frequencia pedagogica') && $this->podeConsultar($usuario, $diario);
    }

    public function acompanharRendimentoPedagogico(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('acompanhar rendimento pedagogico') && $this->podeConsultar($usuario, $diario);
    }

    public function acompanharAlunosEmRisco(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('acompanhar alunos em risco') && $this->podeConsultar($usuario, $diario);
    }

    public function gerenciarPendenciaDocente(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('gerenciar pendencias docentes') && $this->podeConsultar($usuario, $diario);
    }

    public function consultarAvaliacaoCoordenacao(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('consultar notas e conceitos pedagogicos') && $this->podeConsultar($usuario, $diario);
    }

    public function alterarAvaliacaoCoordenacao(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('alterar notas e conceitos pedagogicos') && $this->podeConsultar($usuario, $diario);
    }

    public function consultarAulaCoordenacao(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('consultar aulas pedagogicamente') && $this->podeConsultar($usuario, $diario);
    }

    public function ajustarAulaCoordenacao(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('ajustar aulas pedagogicamente') && $this->podeConsultar($usuario, $diario);
    }

    public function acompanharDirecao(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('acompanhar diarios da direcao') && $this->podeConsultar($usuario, $diario);
    }

    public function validarPlanejamentoDirecao(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('validar planejamento pela direcao') && $this->podeConsultar($usuario, $diario);
    }

    public function validarRegistroAulaDirecao(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('validar aulas pela direcao') && $this->podeConsultar($usuario, $diario);
    }

    public function validarPlanejamentoPeriodoDirecao(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('validar planejamento por periodo pela direcao') && $this->podeConsultar($usuario, $diario);
    }

    public function consultarAvaliacaoDirecao(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('consultar notas e conceitos da direcao') && $this->podeConsultar($usuario, $diario);
    }

    public function alterarAvaliacaoDirecao(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('alterar notas e conceitos da direcao') && $this->podeConsultar($usuario, $diario);
    }

    public function consultarAulaDirecao(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('consultar aulas da direcao') && $this->podeConsultar($usuario, $diario);
    }

    public function ajustarAulaDirecao(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('ajustar aulas da direcao') && $this->podeConsultar($usuario, $diario);
    }

    public function justificarFaltaAluno(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('justificar faltas de alunos') && $this->podeConsultar($usuario, $diario);
    }

    public function liberarPrazoLancamento(Usuario $usuario, DiarioProfessor $diario): bool
    {
        return $usuario->can('liberar prazo de lancamento') && $this->podeConsultar($usuario, $diario);
    }

    private function podeConsultar(Usuario $usuario, DiarioProfessor $diario): bool
    {
        if (! $usuario->can('consultar diarios')) {
            return false;
        }

        $escolaIds = $usuario->escolas()->pluck('escolas.id');

        return $escolaIds->isNotEmpty() && $escolaIds->contains($diario->escola_id);
    }

    private function podeGerenciarComoProfessor(Usuario $usuario, DiarioProfessor $diario): bool
    {
        $funcionarioId = $this->resolverFuncionarioId($usuario);

        return $funcionarioId !== null && (int) $diario->professor_id === $funcionarioId;
    }

    private function resolverFuncionarioId(Usuario $usuario): ?int
    {
        if ($usuario->funcionario_id) {
            return (int) $usuario->funcionario_id;
        }

        return Funcionario::query()
            ->where('email', $usuario->email)
            ->value('id');
    }
}
