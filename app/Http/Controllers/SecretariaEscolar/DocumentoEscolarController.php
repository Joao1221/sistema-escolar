<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmitirDocumentoRequest;
use App\Services\DocumentoEscolarService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class DocumentoEscolarController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly DocumentoEscolarService $documentoEscolarService
    ) {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:consultar documentos escolares'),
        ];
    }

    public function index(Request $request)
    {
        return view('secretaria-escolar.documentos.index', [
            'documentos' => $this->documentoEscolarService->documentosDisponiveis('secretaria-escolar', $request->user()),
            'opcoesFormulario' => $this->documentoEscolarService->opcoesFormulario('secretaria-escolar', $request->user()),
        ]);
    }

    public function preview(EmitirDocumentoRequest $request, string $tipo)
    {
        return view('secretaria-escolar.documentos.preview', [
            'documento' => $this->documentoEscolarService->emitir('secretaria-escolar', $tipo, $request->user(), $request->validated()),
            'tipoDocumento' => $tipo,
            'payload' => $request->validated(),
        ]);
    }

    public function print(EmitirDocumentoRequest $request, string $tipo)
    {
        return view('documentos.impressao', [
            'documento' => $this->documentoEscolarService->emitir('secretaria-escolar', $tipo, $request->user(), $request->validated()),
        ]);
    }
}
