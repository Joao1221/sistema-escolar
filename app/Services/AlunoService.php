<?php

namespace App\Services;

use App\Models\Aluno;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AlunoService
{
    /**
     * Lista alunos com filtros e paginação.
     */
    public function listarAlunos(array $filtros = [], int $paginacao = 10)
    {
        $query = Aluno::query();

        if (!empty($filtros['nome'])) {
            $query->where('nome_completo', 'like', '%' . $filtros['nome'] . '%');
        }

        if (!empty($filtros['rgm'])) {
            $query->where('rgm', $filtros['rgm']);
        }

        if (isset($filtros['status'])) {
            $query->where('ativo', $filtros['status']);
        }

        return $query->orderBy('nome_completo')->paginate($paginacao);
    }

    /**
     * Cria um novo aluno com RGM gerado automaticamente.
     */
    public function criarAluno(array $dados)
    {
        return DB::transaction(function () use ($dados) {
            $dados['rgm'] = $this->gerarRGM();
            return Aluno::create($dados);
        });
    }

    /**
     * Atualiza dados de um aluno.
     */
    public function atualizarAluno(Aluno $aluno, array $dados)
    {
        return $aluno->update($dados);
    }

    /**
     * Alterna o status do aluno.
     */
    public function alternarStatus(Aluno $aluno)
    {
        $aluno->ativo = !$aluno->ativo;
        return $aluno->save();
    }

    /**
     * Gera um RGM único baseado no ano e sequência.
     * Formato: YYYYXXXX (Ano + 4 dígitos sequenciais)
     */
    private function gerarRGM()
    {
        $ano = date('Y');
        $ultimoAluno = Aluno::where('rgm', 'like', $ano . '%')
            ->orderBy('rgm', 'desc')
            ->first();

        if ($ultimoAluno) {
            $sequencia = (int) substr($ultimoAluno->rgm, 4) + 1;
        } else {
            $sequencia = 1;
        }

        return $ano . str_pad($sequencia, 4, '0', STR_PAD_LEFT);
    }
}
