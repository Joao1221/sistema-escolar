<?php

namespace App\Providers;

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
use App\Models\PlanoIntervencaoPsicossocial;
use App\Models\RegistroAula;
use App\Models\RelatorioTecnicoPsicossocial;
use App\Models\Turma;
use App\Models\Usuario;
use App\Observers\RegistroAuditoriaObserver;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Conceder Bypass global no Gate para o Administrador
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Administrador da Rede') ? true : null;
        });

        foreach ([
            Usuario::class,
            Instituicao::class,
            Escola::class,
            Funcionario::class,
            Aluno::class,
            Turma::class,
            Matricula::class,
            HorarioAula::class,
            CategoriaAlimento::class,
            Alimento::class,
            \App\Models\FornecedorAlimento::class,
            MovimentacaoAlimento::class,
            CardapioDiario::class,
            AtendimentoPsicossocial::class,
            PlanoIntervencaoPsicossocial::class,
            EncaminhamentoPsicossocial::class,
            CasoDisciplinarSigiloso::class,
            RelatorioTecnicoPsicossocial::class,
            DiarioProfessor::class,
            PlanejamentoAnual::class,
            PlanejamentoPeriodo::class,
            RegistroAula::class,
            FrequenciaAula::class,
            LancamentoAvaliativo::class,
            AcompanhamentoPedagogicoAluno::class,
            PendenciaProfessor::class,
            JustificativaFaltaAluno::class,
            LiberacaoPrazoProfessor::class,
            FaltaFuncionario::class,
            FechamentoLetivo::class,
        ] as $modeloAuditavel) {
            $modeloAuditavel::observe(RegistroAuditoriaObserver::class);
        }
    }
}
