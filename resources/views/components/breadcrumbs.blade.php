@php
    $allSegments = request()->segments();

    // Find the index of 'secretaria' or 'secretaria-escolar'
    $portalPrefixIndex = false;
    $portalPrefix = '';

    $secretariaIndex = array_search('secretaria', $allSegments);
    $secretariaEscolarIndex = array_search('secretaria-escolar', $allSegments);

    if ($secretariaIndex !== false && ($secretariaEscolarIndex === false || $secretariaIndex < $secretariaEscolarIndex)) {
        $portalPrefixIndex = $secretariaIndex;
        $portalPrefix = 'secretaria';
    } elseif ($secretariaEscolarIndex !== false) {
        $portalPrefixIndex = $secretariaEscolarIndex;
        $portalPrefix = 'secretaria-escolar';
    }

    if ($portalPrefixIndex !== false) {
        $segments = array_slice($allSegments, $portalPrefixIndex + 1);
        $url = implode('/', array_slice($allSegments, 0, $portalPrefixIndex + 1));
    } else {
        $segments = $allSegments;
        $url = '';
    }

    $translations = [
        'dashboard'    => 'Painel',
        'escolas'      => 'Escolas',
        'funcionarios' => 'Funcionários',
        'usuarios'     => 'Usuários',
        'alunos'       => 'Alunos',
        'turmas'       => 'Turmas',
        'instituicao'  => 'Dados Institucionais',
        'configuracoes'=> 'Parâmetros Globais',
        'create'       => 'Novo',
        'edit'         => 'Editar',
    ];
@endphp

<nav class="flex text-gray-400 text-sm font-medium" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-2">
        <li class="inline-flex items-center">
            <a href="{{ $portalPrefix ? route($portalPrefix . '.dashboard') : url('/') }}" class="hover:text-gray-600 transition inline-flex items-center">
                <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                Início
            </a>
        </li>

        @foreach ($segments as $index => $segment)
            @php
                $url .= '/' . $segment;
                
                // Skip display if it's a numeric ID, but still update the URL
                if (is_numeric($segment)) {
                    continue;
                }

                $label = $translations[$segment] ?? ucwords(str_replace('-', ' ', $segment));
                
                // Determine if this is the last VISIBLE segment
                $remainingSegments = array_slice($segments, $index + 1);
                $hasMoreVisibleSegments = false;
                foreach($remainingSegments as $nextSegment) {
                    if (!is_numeric($nextSegment)) {
                        $hasMoreVisibleSegments = true;
                        break;
                    }
                }
                $isLastVisible = !$hasMoreVisibleSegments;
            @endphp
            <li>
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    @if ($isLastVisible)
                        <span class="ml-1 md:ml-2 text-gray-700 font-semibold">{{ $label }}</span>
                    @else
                        <a href="{{ url($url) }}" class="ml-1 md:ml-2 hover:text-gray-600 transition">{{ $label }}</a>
                    @endif
                </div>
            </li>
        @endforeach
    </ol>
</nav>
