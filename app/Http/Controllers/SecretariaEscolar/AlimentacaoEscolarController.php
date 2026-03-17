<?php

namespace App\Http\Controllers\SecretariaEscolar;

use App\Http\Controllers\Controller;
use App\Services\AlimentacaoEscolarService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AlimentacaoEscolarController extends Controller implements HasMiddleware
{
    public function __construct(
        private readonly AlimentacaoEscolarService $service
    ) {
    }

    public static function middleware(): array
    {
        return [
            new Middleware('can:consultar alimentacao escolar'),
        ];
    }

    public function index(Request $request)
    {
        $painel = $this->service->listarPainel(
            $request->user(),
            $request->integer('escola_id') ?: null
        );

        return view('secretaria-escolar.alimentacao.index', $painel);
    }
}
