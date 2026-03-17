<?php

namespace App\Http\Controllers\Secretaria;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmitirDocumentoRequest;
use App\Services\DocumentoEscolarService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DocumentoInstitucionalController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly DocumentoEscolarService $documentoEscolarService
    ) {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:consultar documentos institucionais da rede'),
        ];
    }

    public function index(Request $request)
    {
        return view('secretaria.documentos.index', [
            'documentos' => $this->documentoEscolarService->documentosDisponiveis('secretaria', $request->user()),
            'opcoesFormulario' => $this->documentoEscolarService->opcoesFormulario('secretaria', $request->user()),
        ]);
    }

    public function preview(EmitirDocumentoRequest $request, string $tipo)
    {
        return view('secretaria.documentos.preview', [
            'documento' => $this->documentoEscolarService->emitir('secretaria', $tipo, $request->user(), $request->validated()),
            'tipoDocumento' => $tipo,
            'payload' => $request->validated(),
        ]);
    }

    public function print(EmitirDocumentoRequest $request, string $tipo)
    {
        return view('documentos.impressao', [
            'documento' => $this->documentoEscolarService->emitir('secretaria', $tipo, $request->user(), $request->validated()),
        ]);
    }
}
