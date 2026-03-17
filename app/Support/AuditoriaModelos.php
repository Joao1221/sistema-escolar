<?php

namespace App\Support;

use App\Models\Aluno;
use App\Models\Alimento;
use App\Models\AcompanhamentoPedagogicoAluno;
use App\Models\AtendimentoPsicossocial;
use App\Models\CardapioDiario;
use App\Models\CasoDisciplinarSigiloso;
use App\Models\CategoriaAlimento;
use App\Models\DiarioProfessor;
use App\Models\EncaminhamentoPsicossocial;
use App\Models\Escola;
use App\Models\FaltaFuncionario;
use App\Models\FechamentoLetivo;
use App\Models\FrequenciaAula;
use App\Models\Funcionario;
use App\Models\HorarioAula;
use App\Models\Instituicao;
use App\Models\JustificativaFaltaAluno;
use App\Models\LancamentoAvaliativo;
use App\Models\LiberacaoPrazoProfessor;
use App\Models\Matricula;
use App\Models\MovimentacaoAlimento;
use App\Models\PendenciaProfessor;
use App\Models\PlanejamentoAnual;
use App\Models\PlanejamentoPeriodo;
use App\Models\PlanejamentoSemanal;
use App\Models\PlanoIntervencaoPsicossocial;
use App\Models\RegistroAula;
use App\Models\RelatorioTecnicoPsicossocial;
use App\Models\Turma;
use App\Models\Usuario;

class AuditoriaModelos
{
    public static function configuracao(\Illuminate\Database\Eloquent\Model|string $model): ?array
    {
        $classe = is_string($model) ? $model : $model::class;

        return self::mapa()[$classe] ?? null;
    }

    public static function mapa(): array
    {
        return [
            Usuario::class => self::config('usuarios', 'Usuario', 'alto', ['name', 'email', 'ativo', 'funcionario_id']),
            Instituicao::class => self::config('instituicao', 'Dados Institucionais', 'alto', ['nome_prefeitura', 'cnpj_prefeitura', 'nome_secretaria', 'sigla_secretaria', 'nome_secretario', 'telefone', 'email', 'municipio', 'uf']),
            Escola::class => self::config('escolas', 'Escola', 'medio', ['nome', 'cnpj', 'email', 'telefone', 'cidade', 'uf', 'ativo']),
            Funcionario::class => self::config('funcionarios', 'Funcionario', 'medio', ['nome', 'cpf', 'email', 'telefone', 'cargo', 'ativo']),
            Aluno::class => self::config('alunos', 'Aluno', 'alto', ['rgm', 'nome_completo', 'data_nascimento', 'responsavel_nome', 'responsavel_telefone', 'cidade', 'uf', 'ativo']),
            Turma::class => self::config('turmas', 'Turma', 'medio', ['escola_id', 'modalidade_id', 'nome', 'turno', 'ano_letivo', 'vagas', 'ativa'], escola: fn (Turma $model) => $model->escola_id),
            Matricula::class => self::config(
                fn (Matricula $model) => $model->tipo === 'aee' ? 'aee' : 'matriculas',
                'Matricula',
                'alto',
                ['aluno_id', 'escola_id', 'turma_id', 'ano_letivo', 'tipo', 'status', 'data_matricula', 'data_encerramento'],
                escola: fn (Matricula $model) => $model->escola_id,
                contexto: fn (Matricula $model) => [
                    'aluno_id' => $model->aluno_id,
                    'turma_id' => $model->turma_id,
                    'ano_letivo' => $model->ano_letivo,
                    'tipo_matricula' => $model->tipo,
                ]
            ),
            HorarioAula::class => self::config('horarios', 'Horario de Aula', 'medio', ['escola_id', 'turma_id', 'disciplina_id', 'professor_id', 'dia_semana', 'horario_inicial', 'horario_final', 'ordem_aula', 'ativo'], escola: fn (HorarioAula $model) => $model->escola_id),
            CategoriaAlimento::class => self::config('alimentacao', 'Categoria de Alimento', 'medio', ['nome', 'descricao', 'ativo']),
            Alimento::class => self::config('alimentacao', 'Alimento', 'medio', ['categoria_alimento_id', 'nome', 'unidade_medida', 'estoque_minimo', 'controla_validade', 'ativo']),
            \App\Models\FornecedorAlimento::class => self::config('alimentacao', 'Fornecedor de Alimento', 'medio', ['nome', 'cnpj', 'telefone', 'email', 'cidade', 'uf', 'ativo']),
            MovimentacaoAlimento::class => self::config('alimentacao', 'Movimentacao de Alimento', 'medio', ['escola_id', 'alimento_id', 'fornecedor_alimento_id', 'tipo', 'quantidade', 'saldo_resultante', 'data_movimentacao', 'data_validade'], escola: fn (MovimentacaoAlimento $model) => $model->escola_id),
            CardapioDiario::class => self::config('alimentacao', 'Cardapio Diario', 'medio', ['escola_id', 'data_cardapio', 'observacoes'], escola: fn (CardapioDiario $model) => $model->escola_id),
            AtendimentoPsicossocial::class => self::config('psicossocial', 'Atendimento Psicossocial', 'sigiloso', ['escola_id', 'tipo_publico', 'tipo_atendimento', 'natureza', 'status', 'data_agendada', 'data_realizacao', 'nivel_sigilo', 'requer_acompanhamento'], escola: fn (AtendimentoPsicossocial $model) => $model->escola_id),
            PlanoIntervencaoPsicossocial::class => self::config('psicossocial', 'Plano de Intervencao', 'sigiloso', ['objetivo_geral', 'objetivos_especificos', 'estrategias', 'responsaveis_execucao', 'status', 'data_inicio', 'data_fim'], escola: fn (PlanoIntervencaoPsicossocial $model) => $model->atendimento?->escola_id),
            EncaminhamentoPsicossocial::class => self::config('psicossocial', 'Encaminhamento Psicossocial', 'sigiloso', ['tipo', 'destino', 'motivo', 'status', 'data_encaminhamento'], escola: fn (EncaminhamentoPsicossocial $model) => $model->atendimento?->escola_id),
            CasoDisciplinarSigiloso::class => self::config('psicossocial', 'Caso Disciplinar Sigiloso', 'sigiloso', ['escola_id', 'titulo', 'status', 'data_registro'], escola: fn (CasoDisciplinarSigiloso $model) => $model->escola_id),
            RelatorioTecnicoPsicossocial::class => self::config('psicossocial', 'Relatorio Tecnico Psicossocial', 'sigiloso', ['escola_id', 'tipo_relatorio', 'titulo', 'data_emissao'], escola: fn (RelatorioTecnicoPsicossocial $model) => $model->escola_id),
            DiarioProfessor::class => self::config('diarios', 'Diario do Professor', 'medio', ['escola_id', 'turma_id', 'disciplina_id', 'professor_id', 'ano_letivo', 'periodo_tipo', 'periodo_referencia', 'situacao'], escola: fn (DiarioProfessor $model) => $model->escola_id, contexto: fn (DiarioProfessor $model) => ['professor_id' => $model->professor_id, 'turma_id' => $model->turma_id]),
            PlanejamentoAnual::class => self::config('planejamentos', 'Planejamento Anual', 'medio', ['objetivos_aprendizagem', 'habilidades_competencias', 'conteudos', 'metodologia', 'recursos_didaticos', 'estrategias_pedagogicas', 'instrumentos_avaliacao'], escola: fn (PlanejamentoAnual $model) => $model->diarioProfessor?->escola_id, contexto: fn (PlanejamentoAnual $model) => self::contextoDiario($model->diarioProfessor)),
            PlanejamentoPeriodo::class => self::config('planejamentos', 'Planejamento por Periodo', 'medio', ['tipo_planejamento', 'data_inicio', 'data_fim', 'objetivos_aprendizagem', 'conteudos', 'metodologia'], escola: fn (PlanejamentoPeriodo $model) => $model->diarioProfessor?->escola_id, contexto: fn (PlanejamentoPeriodo $model) => self::contextoDiario($model->diarioProfessor)),
            PlanejamentoSemanal::class => self::config('planejamentos', 'Planejamento Semanal', 'medio', ['data_inicio_semana', 'data_fim_semana', 'conteudos', 'metodologia', 'objetivos'], escola: fn (PlanejamentoSemanal $model) => $model->diarioProfessor?->escola_id, contexto: fn (PlanejamentoSemanal $model) => self::contextoDiario($model->diarioProfessor)),
            RegistroAula::class => self::config('aulas', 'Registro de Aula', 'medio', ['data_aula', 'titulo', 'conteudo_previsto', 'conteudo_ministrado', 'quantidade_aulas', 'aula_dada'], escola: fn (RegistroAula $model) => $model->diarioProfessor?->escola_id, contexto: fn (RegistroAula $model) => self::contextoDiario($model->diarioProfessor)),
            FrequenciaAula::class => self::config('frequencia', 'Frequencia de Aula', 'alto', ['situacao', 'justificativa', 'observacao'], escola: fn (FrequenciaAula $model) => $model->registroAula?->diarioProfessor?->escola_id, contexto: fn (FrequenciaAula $model) => self::contextoDiario($model->registroAula?->diarioProfessor) + ['matricula_id' => $model->matricula_id]),
            LancamentoAvaliativo::class => self::config('avaliacoes', 'Lancamento Avaliativo', 'alto', ['tipo_avaliacao', 'avaliacao_referencia', 'valor_numerico', 'conceito', 'observacoes'], escola: fn (LancamentoAvaliativo $model) => $model->diarioProfessor?->escola_id, contexto: fn (LancamentoAvaliativo $model) => self::contextoDiario($model->diarioProfessor) + ['matricula_id' => $model->matricula_id]),
            AcompanhamentoPedagogicoAluno::class => self::config('pedagogico', 'Acompanhamento Pedagogico', 'alto', ['nivel_rendimento', 'situacao_risco', 'percentual_frequencia', 'precisa_intervencao'], escola: fn (AcompanhamentoPedagogicoAluno $model) => $model->diarioProfessor?->escola_id, contexto: fn (AcompanhamentoPedagogicoAluno $model) => self::contextoDiario($model->diarioProfessor) + ['matricula_id' => $model->matricula_id]),
            PendenciaProfessor::class => self::config('pedagogico', 'Pendencia Docente', 'medio', ['titulo', 'descricao', 'origem', 'prazo', 'status'], escola: fn (PendenciaProfessor $model) => $model->diarioProfessor?->escola_id, contexto: fn (PendenciaProfessor $model) => self::contextoDiario($model->diarioProfessor)),
            JustificativaFaltaAluno::class => self::config('gestao_escolar', 'Justificativa de Falta', 'alto', ['motivo', 'deferida', 'deferida_em'], escola: fn (JustificativaFaltaAluno $model) => $model->diarioProfessor?->escola_id, contexto: fn (JustificativaFaltaAluno $model) => self::contextoDiario($model->diarioProfessor)),
            LiberacaoPrazoProfessor::class => self::config('gestao_escolar', 'Liberacao de Prazo', 'alto', ['tipo_lancamento', 'data_limite', 'motivo', 'status'], escola: fn (LiberacaoPrazoProfessor $model) => $model->diarioProfessor?->escola_id, contexto: fn (LiberacaoPrazoProfessor $model) => self::contextoDiario($model->diarioProfessor)),
            FaltaFuncionario::class => self::config('gestao_escolar', 'Falta de Funcionario', 'alto', ['escola_id', 'funcionario_id', 'data_falta', 'turno', 'tipo_falta', 'motivo', 'justificada'], escola: fn (FaltaFuncionario $model) => $model->escola_id),
            FechamentoLetivo::class => self::config('gestao_escolar', 'Fechamento Letivo', 'alto', ['escola_id', 'ano_letivo', 'status', 'resumo', 'observacoes', 'iniciado_em', 'concluido_em'], escola: fn (FechamentoLetivo $model) => $model->escola_id),
        ];
    }

    private static function config(
        callable|string $modulo,
        string $tipoRegistro,
        string $sensibilidade,
        array $camposCriticos,
        ?callable $escola = null,
        ?callable $contexto = null
    ): array {
        return [
            'modulo' => $modulo,
            'tipo_registro' => $tipoRegistro,
            'sensibilidade' => $sensibilidade,
            'campos_criticos' => $camposCriticos,
            'resolver_escola' => $escola,
            'resolver_contexto' => $contexto,
        ];
    }

    private static function contextoDiario(?DiarioProfessor $diario): array
    {
        if (! $diario) {
            return [];
        }

        return [
            'diario_professor_id' => $diario->id,
            'professor_id' => $diario->professor_id,
            'turma_id' => $diario->turma_id,
            'disciplina_id' => $diario->disciplina_id,
            'ano_letivo' => $diario->ano_letivo,
        ];
    }
}
