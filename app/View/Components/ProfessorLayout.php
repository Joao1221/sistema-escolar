<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class ProfessorLayout extends Component
{
    public function __construct(
        public string $titulo = 'Portal do Professor',
        public ?string $subtitulo = null,
        public array $breadcrumbs = []
    ) {
    }

    public function render(): View
    {
        return view('layouts.professor');
    }
}
