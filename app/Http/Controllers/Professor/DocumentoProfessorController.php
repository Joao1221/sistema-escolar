<?php

namespace App\Http\Controllers\Professor;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmitirDocumentoRequest;
use App\Services\DocumentoEscolarService;
use App\Services\PortalProfessorService;
use Illuminate\Http\Request;

class DocumentoProfessorController extends Controller
{
    public function __construct(
        private readonly DocumentoEscolarService $documentoEscolarService,
        private readonly PortalProfessorService $portalProfessorService
    ) {
    }

    public function index(Request $request)
    {
        abort_unless($request->user()->can('consultar documentos do professor'), 403);

        return view('professor.documentos.index', [
            'documentos' => $this->documentoEscolarService->documentosDisponiveis('professor', $request->user()),
            'opcoesFormulario' => $this->documentoEscolarService->opcoesFormulario('professor', $request->user()),
            'tituloPagina' => 'Documentos do Professor',
            'subtituloPagina' => 'Impressos operacionais restritos ao escopo docente.',
            'breadcrumbs' => $this->portalProfessorService->construirBreadcrumbs([
                ['label' => 'Documentos'],
            ]),
        ]);
    }

    public function preview(EmitirDocumentoRequest $request, string $tipo)
    {
        abort_unless($request->user()->can('consultar documentos do professor'), 403);

        return view('professor.documentos.preview', [
            'documento' => $this->documentoEscolarService->emitir('professor', $tipo, $request->user(), $request->validated()),
            'tipoDocumento' => $tipo,
            'payload' => $request->validated(),
            'tituloPagina' => 'Visualizacao documental',
            'subtituloPagina' => 'Conferencia antes da impressao.',
            'breadcrumbs' => $this->portalProfessorService->construirBreadcrumbs([
                ['label' => 'Documentos', 'url' => route('professor.documentos.index')],
                ['label' => 'Visualizacao'],
            ]),
        ]);
    }

    public function print(EmitirDocumentoRequest $request, string $tipo)
    {
        abort_unless($request->user()->can('consultar documentos do professor'), 403);

        return view('documentos.impressao', [
            'documento' => $this->documentoEscolarService->emitir('professor', $tipo, $request->user(), $request->validated()),
        ]);
    }
}
