<?php

namespace App\Http\Controllers\Nutricionista;

use App\Http\Controllers\Controller;
use App\Services\PortalNutricionistaService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class PortalNutricionistaController extends Controller
{
    public function __construct(
        private readonly PortalNutricionistaService $portalNutricionistaService
    ) {}

    public function dashboard(Request $request): View
    {
        return view('nutricionista.dashboard', [
            ...$this->portalNutricionistaService->obterDadosDashboard(),
            'tituloPagina' => 'Dashboard',
            'subtituloPagina' => 'Visao tecnica, comparativa e gerencial da alimentacao escolar em toda a rede.',
            'breadcrumbs' => $this->portalNutricionistaService->construirBreadcrumbs([
                ['label' => 'Dashboard'],
            ]),
        ]);
    }
}
