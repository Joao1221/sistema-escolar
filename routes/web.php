<?php

use Illuminate\Http\Request;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ArquivoPublicoController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\InstituicaoController;
use App\Http\Controllers\ConfiguracoesController;
use App\Http\Controllers\EscolaController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\Secretaria\AlunoConsultaController;
use App\Http\Controllers\Secretaria\DocumentoInstitucionalController;
use App\Http\Controllers\Secretaria\AuditoriaRedeController;
use App\Http\Controllers\Secretaria\RelatorioRedeController;
use App\Http\Controllers\Secretaria\TurmaConsultaController;
use App\Http\Controllers\Secretaria\MatriculaConsultaController;
use App\Http\Controllers\Secretaria\SecretariaController;
use App\Http\Controllers\Secretaria\DisciplinaController;
use App\Http\Controllers\Secretaria\MatrizCurricularController;
use App\Http\Controllers\SecretariaEscolar\DashboardController as EscolarDashboardController;
use App\Http\Controllers\SecretariaEscolar\AlimentacaoEscolarController;
use App\Http\Controllers\SecretariaEscolar\AlimentoController as EscolarAlimentoController;
use App\Http\Controllers\SecretariaEscolar\CategoriaAlimentoController;
use App\Http\Controllers\SecretariaEscolar\CoordenacaoDocumentoController;
use App\Http\Controllers\SecretariaEscolar\RelatorioCoordenacaoController;
use App\Http\Controllers\SecretariaEscolar\RelatorioDirecaoController;
use App\Http\Controllers\SecretariaEscolar\RelatorioEscolarController;
use App\Http\Controllers\SecretariaEscolar\RelatorioPsicossocialController;
use App\Http\Controllers\SecretariaEscolar\TurmaController as EscolarTurmaController;
use App\Http\Controllers\SecretariaEscolar\AlunoController as EscolarAlunoController;
use App\Http\Controllers\SecretariaEscolar\DocumentoEscolarController;
use App\Http\Controllers\SecretariaEscolar\MatriculaController as EscolarMatriculaController;
use App\Http\Controllers\SecretariaEscolar\CardapioDiarioController;
use App\Http\Controllers\SecretariaEscolar\AuditoriaCoordenacaoController;
use App\Http\Controllers\SecretariaEscolar\AuditoriaDirecaoController;
use App\Http\Controllers\SecretariaEscolar\AuditoriaEscolarController;
use App\Http\Controllers\SecretariaEscolar\AuditoriaPsicossocialController;
use App\Http\Controllers\SecretariaEscolar\CoordenacaoHorarioController;
use App\Http\Controllers\SecretariaEscolar\CurriculoController as EscolarCurriculoController;
use App\Http\Controllers\SecretariaEscolar\CoordenacaoPedagogicaController;
use App\Http\Controllers\SecretariaEscolar\ConsultaDiarioController;
use App\Http\Controllers\SecretariaEscolar\DirecaoDocumentoController;
use App\Http\Controllers\SecretariaEscolar\DirecaoHorarioController;
use App\Http\Controllers\SecretariaEscolar\DirecaoEscolarController;
use App\Http\Controllers\SecretariaEscolar\FornecedorAlimentoController;
use App\Http\Controllers\SecretariaEscolar\MovimentacaoAlimentoController;
use App\Http\Controllers\SecretariaEscolar\PsicossocialDocumentoController;
use App\Http\Controllers\SecretariaEscolar\DemandaPsicossocialEscolarController;
use App\Http\Controllers\SecretariaEscolar\PsicossocialController;
use App\Http\Controllers\Professor\DocumentoProfessorController;
use App\Http\Controllers\Professor\AuditoriaProfessorController;
use App\Http\Controllers\Professor\DiarioProfessorController;
use App\Http\Controllers\Professor\PortalProfessorController;
use App\Http\Controllers\Professor\TurmaProfessorController;
use App\Http\Controllers\Professor\HorarioProfessorController;
use App\Http\Controllers\Professor\ThemeController;
use App\Http\Controllers\Nutricionista\GestaoAlimentacaoController as NutricionistaGestaoAlimentacaoController;
use App\Http\Controllers\Nutricionista\PortalNutricionistaController;
use App\Http\Controllers\Nutricionista\AuditoriaNutricionistaController;
use App\Http\Controllers\Nutricionista\RelatorioNutricionistaController;
use App\Http\Controllers\PsicologiaPsicopedagogia\RelatorioAtendimentoPsicologiaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Página inicial — redireciona para o portal
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('portal-select');
})->middleware(['auth'])->name('hub');

Route::get('/arquivos-publicos/{path}', [ArquivoPublicoController::class, 'show'])
    ->where('path', '.*')
    ->name('arquivos.publicos.show');

Route::get('/dashboard', function () {
    return redirect()->route('hub');
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Rotas autenticadas — fora do portal (profile)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| PORTAL DA SECRETARIA DE EDUCAÇÃO
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Administrador da Rede'])->prefix('secretaria')->name('secretaria.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [SecretariaController::class, 'dashboard'])->name('dashboard');

    // Gestão de Usuários
    Route::resource('usuarios', UsuarioController::class)->except(['show', 'destroy']);
    Route::patch('/usuarios/{usuario}/status', [UsuarioController::class, 'alternarStatus'])->name('usuarios.status');

    // Dados Institucionais
    Route::get('/instituicao', [InstituicaoController::class, 'show'])->name('instituicao.show');
    Route::get('/instituicao/editar', [InstituicaoController::class, 'edit'])->name('instituicao.edit');
    Route::put('/instituicao', [InstituicaoController::class, 'update'])->name('instituicao.update');

    // Configurações Globais
    Route::get('/configuracoes', [ConfiguracoesController::class, 'index'])->name('configuracoes.index');
    Route::put('/configuracoes/parametros', [ConfiguracoesController::class, 'updateParametros'])->name('configuracoes.parametros.update');
    Route::post('/configuracoes/modalidades', [ConfiguracoesController::class, 'storeModalidade'])->name('configuracoes.modalidades.store');
    Route::put('/configuracoes/modalidades/{id}', [ConfiguracoesController::class, 'updateModalidade'])->name('configuracoes.modalidades.update');
    Route::patch('/configuracoes/modalidades/{id}/toggle', [ConfiguracoesController::class, 'toggleModalidade'])->name('configuracoes.modalidades.toggle');

    // Escolas
    Route::resource('escolas', EscolaController::class);
    Route::patch('/escolas/{escola}/toggle', [EscolaController::class, 'toggleStatus'])->name('escolas.toggle');

    // Funcionários
    Route::resource('funcionarios', FuncionarioController::class);
    Route::patch('/funcionarios/{funcionario}/toggle', [FuncionarioController::class, 'toggleStatus'])->name('funcionarios.toggle');

    // Consulta de Alunos
    Route::get('/alunos', [AlunoConsultaController::class, 'index'])->name('alunos.index');
    Route::get('/alunos/{aluno}', [AlunoConsultaController::class, 'show'])->name('alunos.show');

    // Consulta de Turmas
    Route::get('/turmas', [TurmaConsultaController::class, 'index'])->name('turmas.index');
    Route::get('/turmas/{turma}', [TurmaConsultaController::class, 'show'])->name('turmas.show');

    // Consulta de Matrículas
    Route::get('/matriculas', [MatriculaConsultaController::class, 'index'])->name('matriculas.index');
    Route::get('/matriculas/{matricula}', [MatriculaConsultaController::class, 'show'])->name('matriculas.show');

    // Base Curricular
    Route::get('/matrizes/panorama', [MatrizCurricularController::class, 'panorama'])->name('matrizes.panorama');
    Route::resource('matrizes', MatrizCurricularController::class)->parameters([
        'matrizes' => 'matriz',
    ]);
    Route::resource('disciplinas', DisciplinaController::class)->except(['show', 'destroy']);
    Route::patch('/disciplinas/{disciplina}/toggle', [DisciplinaController::class, 'toggle'])->name('disciplinas.toggle');

    Route::prefix('documentos')->name('documentos.')->group(function () {
        Route::get('/', [DocumentoInstitucionalController::class, 'index'])->name('index');
        Route::post('/{tipo}/visualizar', [DocumentoInstitucionalController::class, 'preview'])->name('preview');
        Route::post('/{tipo}/imprimir', [DocumentoInstitucionalController::class, 'print'])->name('print');
    });

    Route::prefix('relatorios')->name('relatorios.')->group(function () {
        Route::get('/', [RelatorioRedeController::class, 'index'])->name('index');
        Route::post('/{tipo}/visualizar', [RelatorioRedeController::class, 'preview'])->name('preview');
        Route::post('/{tipo}/imprimir', [RelatorioRedeController::class, 'print'])->name('print');
    });

    Route::prefix('auditoria')->name('auditoria.')->group(function () {
        Route::get('/', [AuditoriaRedeController::class, 'index'])->name('index');
    });
});

/*
|--------------------------------------------------------------------------
| PORTAL DA SECRETARIA ESCOLAR (Operacional)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Administrador da Rede|Secretário Escolar|Administrador da Escola|Diretor Escolar|Coordenador Pedagógico|Psicologia/Psicopedagogia'])->prefix('secretaria-escolar')->name('secretaria-escolar.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [EscolarDashboardController::class, 'index'])->name('dashboard');

    // Dados da Escola
    Route::get('/dados-escola', [App\Http\Controllers\SecretariaEscolar\DadosEscolaController::class, 'edit'])->name('dados-escola.edit');
    Route::put('/dados-escola', [App\Http\Controllers\SecretariaEscolar\DadosEscolaController::class, 'update'])->name('dados-escola.update');

    // Gestão de Turmas
    Route::resource('turmas', EscolarTurmaController::class);
    Route::patch('/turmas/{turma}/toggle', [EscolarTurmaController::class, 'toggleStatus'])->name('turmas.toggle');

    // Gestão de Alunos (CRUD Completo)
    Route::resource('alunos', EscolarAlunoController::class);
    Route::patch('/alunos/{aluno}/toggle', [EscolarAlunoController::class, 'toggleStatus'])->name('alunos.toggle');

    // Gestão de Matrículas
    Route::resource('matriculas', EscolarMatriculaController::class);
    Route::get('/matriculas/{matricula}/enturmar', [EscolarMatriculaController::class, 'enturmarForm'])->name('matriculas.enturmar.form');
    Route::post('/matriculas/{matricula}/enturmar', [EscolarMatriculaController::class, 'enturmarStore'])->name('matriculas.enturmar.store');
    Route::get('/matriculas/{matricula}/transferir', [EscolarMatriculaController::class, 'transferirForm'])->name('matriculas.transferir.form');
    Route::post('/matriculas/{matricula}/transferir', [EscolarMatriculaController::class, 'transferirStore'])->name('matriculas.transferir.store');
    Route::get('/matriculas/{matricula}/rematricular', [EscolarMatriculaController::class, 'rematricularForm'])->name('matriculas.rematricular.form');
    Route::post('/matriculas/{matricula}/rematricular', [EscolarMatriculaController::class, 'rematricularStore'])->name('matriculas.rematricular.store');

    // Base Curricular (Consulta)
    Route::get('/curriculo', [EscolarCurriculoController::class, 'index'])->name('curriculo.index');
    Route::get('/curriculo/{matriz}', [EscolarCurriculoController::class, 'show'])->name('curriculo.show');

    // Horários (Agora gerido pelas escolas)
    Route::get('/diarios', [ConsultaDiarioController::class, 'index'])->name('diarios.index');
    Route::get('/diarios/{diario}', [ConsultaDiarioController::class, 'show'])->name('diarios.show');

    Route::resource('horarios', App\Http\Controllers\SecretariaEscolar\EscolarHorarioController::class);

    Route::prefix('documentos')->name('documentos.')->group(function () {
        Route::get('/', [DocumentoEscolarController::class, 'index'])->name('index');
        Route::post('/{tipo}/visualizar', [DocumentoEscolarController::class, 'preview'])->name('preview');
        Route::post('/{tipo}/imprimir', [DocumentoEscolarController::class, 'print'])->name('print');
    });

    Route::prefix('relatorios')->name('relatorios.')->group(function () {
        Route::get('/', [RelatorioEscolarController::class, 'index'])->name('index');
        Route::post('/{tipo}/visualizar', [RelatorioEscolarController::class, 'preview'])->name('preview');
        Route::post('/{tipo}/imprimir', [RelatorioEscolarController::class, 'print'])->name('print');
    });

    Route::prefix('auditoria')->name('auditoria.')->group(function () {
        Route::get('/', [AuditoriaEscolarController::class, 'index'])->name('index');
    });

    Route::prefix('alimentacao')->name('alimentacao.')->group(function () {
        Route::get('/', [AlimentacaoEscolarController::class, 'index'])->name('index');
        Route::get('/categorias', [CategoriaAlimentoController::class, 'index'])->name('categorias.index');
        Route::post('/categorias', [CategoriaAlimentoController::class, 'store'])->name('categorias.store');
        Route::put('/categorias/{categoria}', [CategoriaAlimentoController::class, 'update'])->name('categorias.update');
        Route::get('/fornecedores', [FornecedorAlimentoController::class, 'index'])->name('fornecedores.index');
        Route::post('/fornecedores', [FornecedorAlimentoController::class, 'store'])->name('fornecedores.store');
        Route::put('/fornecedores/{fornecedor}', [FornecedorAlimentoController::class, 'update'])->name('fornecedores.update');
        Route::get('/alimentos', [EscolarAlimentoController::class, 'index'])->name('alimentos.index');
        Route::get('/alimentos/criar', [EscolarAlimentoController::class, 'create'])->name('alimentos.create');
        Route::post('/alimentos', [EscolarAlimentoController::class, 'store'])->name('alimentos.store');
        Route::get('/alimentos/{alimento}/editar', [EscolarAlimentoController::class, 'edit'])->name('alimentos.edit');
        Route::put('/alimentos/{alimento}', [EscolarAlimentoController::class, 'update'])->name('alimentos.update');
        Route::get('/movimentacoes', [MovimentacaoAlimentoController::class, 'index'])->name('movimentacoes.index');
        Route::get('/movimentacoes/criar', [MovimentacaoAlimentoController::class, 'create'])->name('movimentacoes.create');
        Route::post('/movimentacoes', [MovimentacaoAlimentoController::class, 'store'])->name('movimentacoes.store');
        Route::get('/cardapios', [CardapioDiarioController::class, 'index'])->name('cardapios.index');
        Route::get('/cardapios/criar', [CardapioDiarioController::class, 'create'])->name('cardapios.create');
        Route::post('/cardapios', [CardapioDiarioController::class, 'store'])->name('cardapios.store');
        Route::get('/cardapios/{cardapio}', [CardapioDiarioController::class, 'show'])->name('cardapios.show');
        Route::get('/cardapios/{cardapio}/editar', [CardapioDiarioController::class, 'edit'])->name('cardapios.edit');
        Route::put('/cardapios/{cardapio}', [CardapioDiarioController::class, 'update'])->name('cardapios.update');
    });

    Route::prefix('psicologia-psicopedagogia/demandas')->name('demandas-psicossociais.')->group(function () {
        Route::get('/', [DemandaPsicossocialEscolarController::class, 'index'])->name('index');
        Route::get('/criar', [DemandaPsicossocialEscolarController::class, 'create'])->name('create');
        Route::get('/dados-escola/{escolaId}', [DemandaPsicossocialEscolarController::class, 'dadosEscola'])->name('dados-escola');
        Route::post('/', [DemandaPsicossocialEscolarController::class, 'store'])->name('store');
        Route::get('/{demanda}', [DemandaPsicossocialEscolarController::class, 'show'])->name('show');
    });

    Route::prefix('psicologia-psicopedagogia')->name('psicossocial.')->group(function () {
        Route::get('/', [PsicossocialController::class, 'index'])->name('index');
        Route::get('/agenda', [PsicossocialController::class, 'agenda'])->name('agenda');
        Route::get('/historico', [PsicossocialController::class, 'historico'])->name('historico');
        Route::get('/atendimentos/criar', [PsicossocialController::class, 'create'])->name('create');
        Route::post('/atendimentos', [PsicossocialController::class, 'store'])->name('store');
        Route::get('/atendimentos/{atendimento}', [PsicossocialController::class, 'show'])->name('show');
        Route::post('/atendimentos/{atendimento}/planos-intervencao', [PsicossocialController::class, 'storePlano'])->name('planos.store');
        Route::post('/atendimentos/{atendimento}/encaminhamentos', [PsicossocialController::class, 'storeEncaminhamento'])->name('encaminhamentos.store');
        Route::post('/atendimentos/{atendimento}/casos-disciplinares', [PsicossocialController::class, 'storeCaso'])->name('casos.store');
        Route::post('/atendimentos/{atendimento}/relatorios-tecnicos', [PsicossocialController::class, 'storeRelatorio'])->name('relatorios.store');
        Route::get('/documentos', [PsicossocialDocumentoController::class, 'index'])->name('documentos.index');
        Route::post('/documentos/{tipo}/visualizar', [PsicossocialDocumentoController::class, 'preview'])->name('documentos.preview');
        Route::post('/documentos/{tipo}/imprimir', [PsicossocialDocumentoController::class, 'print'])->name('documentos.print');
        Route::prefix('relatorios')->name('relatorios.')->group(function () {
            Route::get('/', [RelatorioPsicossocialController::class, 'index'])->name('index');
            Route::post('/{tipo}/visualizar', [RelatorioPsicossocialController::class, 'preview'])->name('preview');
            Route::post('/{tipo}/imprimir', [RelatorioPsicossocialController::class, 'print'])->name('print');
        });
        Route::prefix('auditoria')->name('auditoria.')->group(function () {
            Route::get('/', [AuditoriaPsicossocialController::class, 'index'])->name('index');
        });
    });

    Route::prefix('coordenacao-pedagogica')->name('coordenacao.')->group(function () {
        Route::get('/diarios', [CoordenacaoPedagogicaController::class, 'index'])->name('diarios.index');
        Route::get('/diarios/{diario}', [CoordenacaoPedagogicaController::class, 'show'])->name('diarios.show');
        Route::post('/diarios/{diario}/planejamento-anual/{planejamento}/validacao', [CoordenacaoPedagogicaController::class, 'validarPlanejamentoAnual'])->name('diarios.planejamento-anual.validar');
        Route::post('/diarios/{diario}/planejamentos-periodo/{planejamento}/validacao', [CoordenacaoPedagogicaController::class, 'validarPlanejamentoPeriodo'])->name('diarios.planejamento-periodo.validar');
        Route::post('/diarios/{diario}/registros-aula/{registro}/validacao', [CoordenacaoPedagogicaController::class, 'validarRegistroAula'])->name('diarios.registro-aula.validar');
        Route::put('/diarios/{diario}/registros-aula/{registro}', [CoordenacaoPedagogicaController::class, 'updateRegistroAula'])->name('diarios.registro-aula.update');
        Route::put('/diarios/{diario}/avaliacoes/{avaliacao}', [CoordenacaoPedagogicaController::class, 'updateAvaliacao'])->name('diarios.avaliacoes.update');
        Route::post('/diarios/{diario}/acompanhamentos/{matricula}', [CoordenacaoPedagogicaController::class, 'storeAcompanhamentoAluno'])->name('diarios.acompanhamentos.store');
        Route::post('/diarios/{diario}/pendencias', [CoordenacaoPedagogicaController::class, 'storePendencia'])->name('diarios.pendencias.store');
        Route::get('/horarios', [CoordenacaoHorarioController::class, 'index'])->name('horarios.index');
        Route::get('/horarios/criar', [CoordenacaoHorarioController::class, 'create'])->name('horarios.create');
        Route::post('/horarios', [CoordenacaoHorarioController::class, 'store'])->name('horarios.store');
        Route::get('/horarios/{horario}/editar', [CoordenacaoHorarioController::class, 'edit'])->name('horarios.edit');
        Route::put('/horarios/{horario}', [CoordenacaoHorarioController::class, 'update'])->name('horarios.update');
        Route::get('/documentos', [CoordenacaoDocumentoController::class, 'index'])->name('documentos.index');
        Route::post('/documentos/{tipo}/visualizar', [CoordenacaoDocumentoController::class, 'preview'])->name('documentos.preview');
        Route::post('/documentos/{tipo}/imprimir', [CoordenacaoDocumentoController::class, 'print'])->name('documentos.print');
        Route::prefix('relatorios')->name('relatorios.')->group(function () {
            Route::get('/', [RelatorioCoordenacaoController::class, 'index'])->name('index');
            Route::post('/{tipo}/visualizar', [RelatorioCoordenacaoController::class, 'preview'])->name('preview');
            Route::post('/{tipo}/imprimir', [RelatorioCoordenacaoController::class, 'print'])->name('print');
        });
        Route::prefix('auditoria')->name('auditoria.')->group(function () {
            Route::get('/', [AuditoriaCoordenacaoController::class, 'index'])->name('index');
        });
    });

    Route::prefix('direcao-escolar')->name('direcao.')->group(function () {
        Route::get('/diarios', [DirecaoEscolarController::class, 'index'])->name('diarios.index');
        Route::get('/diarios/{diario}', [DirecaoEscolarController::class, 'show'])->name('diarios.show');
        Route::post('/diarios/{diario}/planejamento-anual/{planejamento}/validacao', [DirecaoEscolarController::class, 'validarPlanejamentoAnual'])->name('diarios.planejamento-anual.validar');
        Route::post('/diarios/{diario}/planejamentos-periodo/{planejamento}/validacao', [DirecaoEscolarController::class, 'validarPlanejamentoPeriodo'])->name('diarios.planejamento-periodo.validar');
        Route::post('/diarios/{diario}/registros-aula/{registro}/validacao', [DirecaoEscolarController::class, 'validarRegistroAula'])->name('diarios.registro-aula.validar');
        Route::put('/diarios/{diario}/registros-aula/{registro}', [DirecaoEscolarController::class, 'updateRegistroAula'])->name('diarios.registro-aula.update');
        Route::put('/diarios/{diario}/avaliacoes/{avaliacao}', [DirecaoEscolarController::class, 'updateAvaliacao'])->name('diarios.avaliacoes.update');
        Route::post('/diarios/{diario}/frequencias/{frequencia}/justificativa', [DirecaoEscolarController::class, 'justificarFalta'])->name('diarios.frequencias.justificar');
        Route::post('/diarios/{diario}/liberacoes-prazo', [DirecaoEscolarController::class, 'storeLiberacaoPrazo'])->name('diarios.liberacoes.store');
        Route::post('/faltas-funcionarios', [DirecaoEscolarController::class, 'storeFaltaFuncionario'])->name('faltas-funcionarios.store');
        Route::post('/fechamento-letivo', [DirecaoEscolarController::class, 'storeFechamentoLetivo'])->name('fechamento-letivo.store');
        Route::get('/horarios', [DirecaoHorarioController::class, 'index'])->name('horarios.index');
        Route::get('/horarios/criar', [DirecaoHorarioController::class, 'create'])->name('horarios.create');
        Route::post('/horarios', [DirecaoHorarioController::class, 'store'])->name('horarios.store');
        Route::get('/horarios/{horario}/editar', [DirecaoHorarioController::class, 'edit'])->name('horarios.edit');
        Route::put('/horarios/{horario}', [DirecaoHorarioController::class, 'update'])->name('horarios.update');
        Route::get('/documentos', [DirecaoDocumentoController::class, 'index'])->name('documentos.index');
        Route::post('/documentos/{tipo}/visualizar', [DirecaoDocumentoController::class, 'preview'])->name('documentos.preview');
        Route::post('/documentos/{tipo}/imprimir', [DirecaoDocumentoController::class, 'print'])->name('documentos.print');
        Route::prefix('relatorios')->name('relatorios.')->group(function () {
            Route::get('/', [RelatorioDirecaoController::class, 'index'])->name('index');
            Route::post('/{tipo}/visualizar', [RelatorioDirecaoController::class, 'preview'])->name('preview');
            Route::post('/{tipo}/imprimir', [RelatorioDirecaoController::class, 'print'])->name('print');
        });
        Route::prefix('auditoria')->name('auditoria.')->group(function () {
            Route::get('/', [AuditoriaDirecaoController::class, 'index'])->name('index');
        });
    });

});

/*
|--------------------------------------------------------------------------
| PORTAL DA PSICOLOGIA / PSICOPEDAGOGIA
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\PsicologiaPsicopedagogia\PortalPsicologiaPsicopedagogiaController;
Route::middleware(['auth', 'can:acessar modulo psicossocial', 'can:acessar dados sigilosos psicossociais'])->prefix('psicologia-psicopedagogia')->name('psicologia.')->group(function () {
    Route::get('/', [PortalPsicologiaPsicopedagogiaController::class, 'index'])->name('index');
    Route::get('/dashboard', [PortalPsicologiaPsicopedagogiaController::class, 'dashboard'])->name('dashboard');
    Route::get('/agenda', [PortalPsicologiaPsicopedagogiaController::class, 'agenda'])->name('agenda');
    
    Route::get('/demandas', [PortalPsicologiaPsicopedagogiaController::class, 'demandas'])->name('demandas.index');
    Route::get('/demandas/criar', [PortalPsicologiaPsicopedagogiaController::class, 'criarDemanda'])->name('demandas.create');
    Route::get('/demandas/dados-escola/{escolaId}', [PortalPsicologiaPsicopedagogiaController::class, 'dadosEscola']);
    Route::post('/demandas', [PortalPsicologiaPsicopedagogiaController::class, 'salvarDemanda'])->name('demandas.store');
    Route::get('/demandas/{demanda}', [PortalPsicologiaPsicopedagogiaController::class, 'verDemanda'])->name('demandas.show');
    Route::post('/demandas/{demanda}/triagem', [PortalPsicologiaPsicopedagogiaController::class, 'salvarTriagem'])->name('demandas.triagem');
    
    Route::get('/atendimentos', [PortalPsicologiaPsicopedagogiaController::class, 'atendimentos'])->name('atendimentos.index');
    Route::get('/atendimentos/criar', [PortalPsicologiaPsicopedagogiaController::class, 'create'])->name('create');
    Route::post('/atendimentos', [PortalPsicologiaPsicopedagogiaController::class, 'store'])->name('store');
    Route::get('/atendimentos/{atendimento}', [PortalPsicologiaPsicopedagogiaController::class, 'show'])->name('show');
    Route::get('/atendimentos/{atendimento}/relatorio-sessoes', [PortalPsicologiaPsicopedagogiaController::class, 'relatorioSessoes'])->name('atendimentos.relatorio_sessoes');
    Route::patch('/atendimentos/{atendimento}/finalizar', [PortalPsicologiaPsicopedagogiaController::class, 'finalizar'])->name('atendimento.finalizar');
    Route::post('/atendimentos/{atendimento}/sessao', [PortalPsicologiaPsicopedagogiaController::class, 'registrarSessao'])->name('atendimento.sessao.store');
    Route::post('/atendimentos/{atendimento}/devolutiva', [PortalPsicologiaPsicopedagogiaController::class, 'salvarDevolutiva'])->name('atendimento.devolutiva.store');
    Route::get('/devolutivas/{devolutiva}/editar', [PortalPsicologiaPsicopedagogiaController::class, 'editDevolutiva'])->name('devolutiva.edit');
    Route::patch('/devolutivas/{devolutiva}', [PortalPsicologiaPsicopedagogiaController::class, 'updateDevolutiva'])->name('devolutiva.update');
    Route::delete('/devolutivas/{devolutiva}', [PortalPsicologiaPsicopedagogiaController::class, 'destroyDevolutiva'])->name('devolutiva.destroy');
    Route::post('/atendimentos/{atendimento}/reavaliacao', [PortalPsicologiaPsicopedagogiaController::class, 'salvarReavaliacao'])->name('atendimento.reavaliacao.store');
    Route::get('/reavaliacoes/{reavaliacao}/editar', [PortalPsicologiaPsicopedagogiaController::class, 'editReavaliacao'])->name('reavaliacao.edit');
    Route::patch('/reavaliacoes/{reavaliacao}', [PortalPsicologiaPsicopedagogiaController::class, 'updateReavaliacao'])->name('reavaliacao.update');
    Route::delete('/reavaliacoes/{reavaliacao}', [PortalPsicologiaPsicopedagogiaController::class, 'destroyReavaliacao'])->name('reavaliacao.destroy');
    Route::post('/atendimentos/{atendimento}/encerrar', [PortalPsicologiaPsicopedagogiaController::class, 'encerrarAtendimento'])->name('atendimento.encerrar');
    Route::post('/atendimentos/{atendimento}/reabrir', [PortalPsicologiaPsicopedagogiaController::class, 'reabrirAtendimento'])->name('atendimento.reabrir');
    Route::post('/atendimentos/{atendimento}/planos-intervencao', [PortalPsicologiaPsicopedagogiaController::class, 'storePlano'])->name('planos.store');
    Route::get('/planos-intervencao/{plano}/editar', [PortalPsicologiaPsicopedagogiaController::class, 'editPlano'])->name('plano.edit');
    Route::patch('/planos-intervencao/{plano}', [PortalPsicologiaPsicopedagogiaController::class, 'updatePlano'])->name('plano.update');
    Route::delete('/planos-intervencao/{plano}', [PortalPsicologiaPsicopedagogiaController::class, 'destroyPlano'])->name('plano.destroy');
    Route::post('/atendimentos/{atendimento}/encaminhamentos', [PortalPsicologiaPsicopedagogiaController::class, 'storeEncaminhamento'])->name('encaminhamentos.store');
    Route::get('/encaminhamentos/{encaminhamento}/editar', [PortalPsicologiaPsicopedagogiaController::class, 'editEncaminhamento'])->name('encaminhamento.edit');
    Route::patch('/encaminhamentos/{encaminhamento}', [PortalPsicologiaPsicopedagogiaController::class, 'updateEncaminhamento'])->name('encaminhamento.update');
    Route::delete('/encaminhamentos/{encaminhamento}', [PortalPsicologiaPsicopedagogiaController::class, 'destroyEncaminhamento'])->name('encaminhamento.destroy');
    Route::post('/atendimentos/{atendimento}/casos-disciplinares', [PortalPsicologiaPsicopedagogiaController::class, 'storeCaso'])->name('casos.store');
    Route::post('/atendimentos/{atendimento}/relatorios-tecnicos', [PortalPsicologiaPsicopedagogiaController::class, 'storeRelatorio'])->name('relatorios_tecnicos.store');
    Route::get('/historico', [PortalPsicologiaPsicopedagogiaController::class, 'historico'])->name('historico.index');
    Route::get('/planos', [PortalPsicologiaPsicopedagogiaController::class, 'planos'])->name('planos.index');
    Route::get('/encaminhamentos', [PortalPsicologiaPsicopedagogiaController::class, 'encaminhamentos'])->name('encaminhamentos.index');
    Route::get('/casos-disciplinares', [PortalPsicologiaPsicopedagogiaController::class, 'casos'])->name('casos.index');
    Route::get('/relatorios-atendimentos', [RelatorioAtendimentoPsicologiaController::class, 'index'])->name('relatorios_atendimentos.index');
    Route::get('/relatorios-tecnicos', [PortalPsicologiaPsicopedagogiaController::class, 'relatoriosTecnicos'])->name('relatorios_tecnicos.index');
    Route::get('/relatorios-tecnicos/emitidos/{relatorio}', [PortalPsicologiaPsicopedagogiaController::class, 'showRelatorioTecnicoEmitido'])->name('relatorios_tecnicos.show');
    Route::get('/relatorios-tecnicos/emitidos/{relatorio}/editar', [PortalPsicologiaPsicopedagogiaController::class, 'editRelatorioTecnico'])->name('relatorios_tecnicos.edit');
    Route::patch('/relatorios-tecnicos/emitidos/{relatorio}', [PortalPsicologiaPsicopedagogiaController::class, 'updateRelatorioTecnico'])->name('relatorios_tecnicos.update');
    Route::delete('/relatorios-tecnicos/emitidos/{relatorio}', [PortalPsicologiaPsicopedagogiaController::class, 'destroyRelatorioTecnico'])->name('relatorios_tecnicos.destroy');
    Route::get('/relatorios-tecnicos/emitidos/{relatorio}/imprimir', [PortalPsicologiaPsicopedagogiaController::class, 'imprimirRelatorioTecnicoEmitido'])->name('relatorios_tecnicos.emitidos.print');
    Route::post('/documentos/{tipo}/visualizar', [PortalPsicologiaPsicopedagogiaController::class, 'previewDocumento'])->name('documentos.preview');
    Route::post('/documentos/{tipo}/imprimir', [PortalPsicologiaPsicopedagogiaController::class, 'imprimirDocumento'])->name('documentos.print');
    Route::get('/documentos', [PortalPsicologiaPsicopedagogiaController::class, 'documentos'])->name('documentos.index');
    Route::post('/relatorios-tecnicos/{tipo}/visualizar', [PortalPsicologiaPsicopedagogiaController::class, 'previewRelatorioTecnico'])->name('relatorios_tecnicos.preview');
    Route::post('/relatorios-tecnicos/{tipo}/imprimir', [PortalPsicologiaPsicopedagogiaController::class, 'imprimirRelatorioTecnico'])->name('relatorios_tecnicos.print');
    Route::get('/auditoria', [PortalPsicologiaPsicopedagogiaController::class, 'auditoria'])->name('auditoria.index');
});

Route::middleware(['auth', 'can:criar diarios'])->prefix('professor')->name('professor.')->group(function () {
    Route::get('/dashboard', [PortalProfessorController::class, 'dashboard'])->name('dashboard');
    Route::get('/turmas', [TurmaProfessorController::class, 'index'])->name('turmas.index');
    Route::get('/horarios', [HorarioProfessorController::class, 'index'])->name('horarios.index');
    Route::post('/theme', [ThemeController::class, 'update'])->name('theme.update');

    Route::get('/diario', [DiarioProfessorController::class, 'index'])->name('diario.index');
    Route::get('/diario/criar', [DiarioProfessorController::class, 'create'])->name('diario.create');
    Route::post('/diario', [DiarioProfessorController::class, 'store'])->name('diario.store');
    Route::get('/diario/{diario}', [DiarioProfessorController::class, 'show'])->name('diario.show');
    Route::post('/diario/{diario}/planejamento-anual', [DiarioProfessorController::class, 'storePlanejamentoAnual'])->name('diario.planejamento-anual.store');
    Route::patch('/diario/{diario}/planejamento-anual/enviar', [DiarioProfessorController::class, 'enviarPlanejamentoAnual'])->name('diario.planejamento-anual.enviar');
    Route::post('/diario/{diario}/planejamento-periodo', [DiarioProfessorController::class, 'storePlanejamentoPeriodo'])->name('diario.planejamento-periodo.store');
    Route::patch('/diario/{diario}/planejamento-periodo/{planejamento}/enviar', [DiarioProfessorController::class, 'enviarPlanejamentoPeriodo'])->name('diario.planejamento-periodo.enviar');
    Route::post('/diario/{diario}/registro-aula', [DiarioProfessorController::class, 'storeRegistroAula'])->name('diario.registro-aula.store');
    Route::post('/diario/{diario}/frequencia', [DiarioProfessorController::class, 'storeFrequencia'])->name('diario.frequencia.store');
    Route::post('/diario/{diario}/avaliacoes', [DiarioProfessorController::class, 'storeAvaliacao'])->name('diario.avaliacoes.store');
    Route::post('/diario/{diario}/observacoes', [DiarioProfessorController::class, 'storeObservacao'])->name('diario.observacoes.store');
    Route::post('/diario/{diario}/ocorrencias', [DiarioProfessorController::class, 'storeOcorrencia'])->name('diario.ocorrencias.store');
    Route::post('/diario/{diario}/pendencias', [DiarioProfessorController::class, 'storePendencia'])->name('diario.pendencias.store');
    Route::get('/documentos', [DocumentoProfessorController::class, 'index'])->name('documentos.index');
    Route::get('/documentos/{tipo}/visualizar', function (Request $request, string $tipo) {
        return redirect()->route('professor.documentos.index');
    })->name('documentos.preview');
    Route::post('/documentos/{tipo}/visualizar', [DocumentoProfessorController::class, 'preview'])->name('documentos.preview');
    Route::post('/documentos/{tipo}/imprimir', [DocumentoProfessorController::class, 'print'])->name('documentos.print');
    Route::get('/auditoria', [AuditoriaProfessorController::class, 'index'])->name('auditoria.index');
});

Route::middleware(['auth', 'can:acessar portal da nutricionista'])->prefix('nutricionista')->name('nutricionista.')->group(function () {
    Route::get('/dashboard', [PortalNutricionistaController::class, 'dashboard'])->name('dashboard');
    Route::get('/estoque', [NutricionistaGestaoAlimentacaoController::class, 'estoque'])->name('estoque.index');
    Route::get('/cardapios', [NutricionistaGestaoAlimentacaoController::class, 'cardapios'])->name('cardapios.index');
    Route::get('/cardapios/criar', [NutricionistaGestaoAlimentacaoController::class, 'createCardapio'])->name('cardapios.create');
    Route::post('/cardapios', [NutricionistaGestaoAlimentacaoController::class, 'storeCardapio'])->name('cardapios.store');
    Route::get('/cardapios/{cardapio}', [NutricionistaGestaoAlimentacaoController::class, 'showCardapio'])->name('cardapios.show');
    Route::get('/cardapios/{cardapio}/editar', [NutricionistaGestaoAlimentacaoController::class, 'editCardapio'])->name('cardapios.edit');
    Route::put('/cardapios/{cardapio}', [NutricionistaGestaoAlimentacaoController::class, 'updateCardapio'])->name('cardapios.update');
    Route::get('/movimentacoes', [NutricionistaGestaoAlimentacaoController::class, 'movimentacoes'])->name('movimentacoes.index');
    Route::get('/movimentacoes/criar', [NutricionistaGestaoAlimentacaoController::class, 'createMovimentacao'])->name('movimentacoes.create');
    Route::post('/movimentacoes', [NutricionistaGestaoAlimentacaoController::class, 'storeMovimentacao'])->name('movimentacoes.store');
    Route::get('/alimentos', [NutricionistaGestaoAlimentacaoController::class, 'alimentos'])->name('alimentos.index');
    Route::get('/alimentos/criar', [NutricionistaGestaoAlimentacaoController::class, 'createAlimento'])->name('alimentos.create');
    Route::post('/alimentos', [NutricionistaGestaoAlimentacaoController::class, 'storeAlimento'])->name('alimentos.store');
    Route::get('/alimentos/{alimento}/editar', [NutricionistaGestaoAlimentacaoController::class, 'editAlimento'])->name('alimentos.edit');
    Route::put('/alimentos/{alimento}', [NutricionistaGestaoAlimentacaoController::class, 'updateAlimento'])->name('alimentos.update');
    Route::get('/categorias', [NutricionistaGestaoAlimentacaoController::class, 'categorias'])->name('categorias.index');
    Route::post('/categorias', [NutricionistaGestaoAlimentacaoController::class, 'storeCategoria'])->name('categorias.store');
    Route::put('/categorias/{categoria}', [NutricionistaGestaoAlimentacaoController::class, 'updateCategoria'])->name('categorias.update');
    Route::get('/fornecedores', [NutricionistaGestaoAlimentacaoController::class, 'fornecedores'])->name('fornecedores.index');
    Route::post('/fornecedores', [NutricionistaGestaoAlimentacaoController::class, 'storeFornecedor'])->name('fornecedores.store');
    Route::put('/fornecedores/{fornecedor}', [NutricionistaGestaoAlimentacaoController::class, 'updateFornecedor'])->name('fornecedores.update');
    Route::prefix('relatorios')->name('relatorios.')->group(function () {
        Route::get('/', [RelatorioNutricionistaController::class, 'index'])->name('index');
        Route::post('/{tipo}/visualizar', [RelatorioNutricionistaController::class, 'preview'])->name('preview');
        Route::post('/{tipo}/imprimir', [RelatorioNutricionistaController::class, 'print'])->name('print');
    });
    Route::get('/auditoria', [AuditoriaNutricionistaController::class, 'index'])->name('auditoria.index');
});

require __DIR__.'/auth.php';
