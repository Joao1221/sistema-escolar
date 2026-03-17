<x-secretaria-layout>
    <div class="space-y-6">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-indigo-600">Documentos Institucionais</p>
            <h1 class="mt-2 text-3xl font-bold text-gray-900">Emissao institucional da rede</h1>
            <p class="mt-2 text-sm text-gray-500">Oficios institucionais e modelos oficiais da Secretaria de Educacao com base nos dados da prefeitura e da secretaria.</p>
        </div>

        @include('documentos.partials.formularios', [
            'documentos' => $documentos,
            'opcoesFormulario' => $opcoesFormulario,
            'rotaPreview' => 'secretaria.documentos.preview',
        ])
    </div>
</x-secretaria-layout>
