<x-secretaria-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($disciplina) ? __('Editar Disciplina') : __('Nova Disciplina') }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-8 max-w-4xl">
        <form action="{{ isset($disciplina) ? route('secretaria.disciplinas.update', $disciplina) : route('secretaria.disciplinas.store') }}" method="POST">
            @csrf
            @if (isset($disciplina))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <x-input-label for="nome" :value="__('Nome da Disciplina')" />
                    <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" :value="old('nome', $disciplina->nome ?? '')" required autofocus />
                    <x-input-error class="mt-2" :messages="$errors->get('nome')" />
                </div>

                <div>
                    <x-input-label for="codigo" :value="__('Código')" />
                    <x-text-input id="codigo" name="codigo" type="text" class="mt-1 block w-full" :value="old('codigo', $disciplina->codigo ?? '')" placeholder="Opcional: Ex: MAT01" />
                    <x-input-error class="mt-2" :messages="$errors->get('codigo')" />
                </div>

                <div>
                    <x-input-label for="carga_horaria_sugerida" :value="__('Carga Horária Sugerida (Anual)')" />
                    <x-text-input id="carga_horaria_sugerida" name="carga_horaria_sugerida" type="number" class="mt-1 block w-full" :value="old('carga_horaria_sugerida', $disciplina->carga_horaria_sugerida ?? '0')" required />
                    <x-input-error class="mt-2" :messages="$errors->get('carga_horaria_sugerida')" />
                </div>

                <div class="flex items-center space-x-2">
                    <input type="hidden" name="ativo" value="0">
                    <input type="checkbox" id="ativo" name="ativo" value="1" {{ old('ativo', $disciplina->ativo ?? true) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <x-input-label for="ativo" :value="__('Ativa')" />
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end space-x-4">
                <a href="{{ route('secretaria.disciplinas.index') }}" class="text-sm text-gray-600 hover:text-gray-900 transition">Cancelar</a>
                <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                    {{ isset($disciplina) ? __('Atualizar Disciplina') : __('Cadastrar Disciplina') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-secretaria-layout>
