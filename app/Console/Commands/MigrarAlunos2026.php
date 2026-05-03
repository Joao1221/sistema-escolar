<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Aluno;
use App\Models\Matricula;

class MigrarAlunos2026 extends Command
{
    protected $signature = 'migrar:alunos-2026';
    protected $description = 'Migra alunos e matrículas de 2026 do sistema antigo';

    public function handle()
    {
        // ================== CONFIGURAÇÕES ==================
        $escolaAntigaId = 1005;
        $escolaNovaId = 6;
        $turmaAntigaId = 1503;
        $turmaNovaId = 3;
        $anoLetivo = '2026';
        // =====================================================

        $this->info("==========================================");
        $this->info("MIGRAÇÃO DE ALUNOS - 2026");
        $this->info("==========================================");
        $this->info("Escola Antiga: {$escolaAntigaId} -> Escola Nova: {$escolaNovaId}");
        $this->info("Turma Antiga: {$turmaAntigaId} -> Turma Nova: {$turmaNovaId}");
        $this->info("Ano Letivo: {$anoLetivo}");
        $this->info("-------------------------------------------");

        // 1. Buscar alunos no banco antigo
        $alunosAntigos = DB::connection('antigo')
            ->table('tb_dadosaluno')
            ->where('id_escola', $escolaAntigaId)
            ->where('ano_letivo', $anoLetivo)
            ->where('status', 1)
            ->where('id_turma', $turmaAntigaId)
            ->get();

        $this->info("Encontrados {$alunosAntigos->count()} alunos para migrar.");

        if ($alunosAntigos->count() === 0) {
            $this->error("Nenhum aluno encontrado para migrar!");
            return;
        }

        $this->info("-------------------------------------------");

        $contadorSucesso = 0;
        $contadorDuplicado = 0;

        foreach ($alunosAntigos as $alunoAntigo) {
            // Verificar se CPF já existe para evitar duplicados
            if ($alunoAntigo->cpf && Aluno::where('cpf', $alunoAntigo->cpf)->exists()) {
                $this->warn("PULADO (CPF duplicado): {$alunoAntigo->nome}");
                $contadorDuplicado++;
                continue;
            }

            // Criar aluno no sistema novo
            // Campos reais da tabela: id, escola_id, rgm, nome_completo, data_nascimento, sexo, cpf, nis,
            // nome_mae, nome_pai, responsavel_nome, responsavel_cpf, responsavel_telefone, cep, logradouro,
            // numero, complemento, bairro, cidade, uf, certidao_nascimento, rg_numero, rg_orgao,
            // alergias, medicamentos, restricoes_alimentares, obs_saude, ativo, created_at, updated_at
            
            $novoAluno = Aluno::create([
                'escola_id' => $escolaNovaId,
                'rgm' => $alunoAntigo->matricula_aluno ?? 'M' . date('Y') . str_pad($alunoAntigo->id, 5, '0', STR_PAD_LEFT),
                'nome_completo' => $alunoAntigo->nome ?? 'Nome não informado',
                'data_nascimento' => $alunoAntigo->nascimento ?? null,
                'sexo' => $alunoAntigo->sexo ?? 'O',
                'cpf' => $alunoAntigo->cpf ?? null,
                'nis' => $alunoAntigo->nis ?? null,
                'nome_mae' => $alunoAntigo->mae ?? 'Não informado',
                'nome_pai' => $alunoAntigo->pai ?? null,
                'responsavel_nome' => $alunoAntigo->responsavel ?? $alunoAntigo->mae ?? 'Não informado',
                'responsavel_cpf' => '',
                'responsavel_telefone' => $alunoAntigo->celular ?? $alunoAntigo->telefone ?? '',
                'cep' => '',
                'logradouro' => '',
                'numero' => '',
                'bairro' => '',
                'cidade' => '',
                'uf' => '',
                'rg_numero' => $alunoAntigo->rg ?? null,
                'rg_orgao' => $alunoAntigo->orgao_exp ?? null,
                'ativo' => true,
            ]);

            // Criar matrícula
            Matricula::create([
                'aluno_id' => $novoAluno->id,
                'escola_id' => $escolaNovaId,
                'turma_id' => $turmaNovaId,
                'ano_letivo' => (int) $anoLetivo,
                'tipo' => 'regular',
                'status' => 'ativa',
                'data_matricula' => $alunoAntigo->dataMatricula ?? now()->toDateString(),
            ]);

            $contadorSucesso++;
            $this->info("✓ Migrado: {$novoAluno->nome_completo}");
        }

        $this->info("-------------------------------------------");
        $this->info("RESULTADO:");
        $this->info("  ✓ Sucesso: {$contadorSucesso} alunos");
        $this->info("  ○ Duplicados: {$contadorDuplicado} alunos");
        $this->info("==========================================");

        $this->info("Concluído!");
    }
}