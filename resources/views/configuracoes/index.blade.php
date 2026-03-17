<x-secretaria-layout>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Configurações Globais da Rede</h1>
        <p class="text-sm text-gray-500 mt-1">Parâmetros pedagógicos e modalidades de ensino</p>
    </div>

    <div x-data="{ tab: 'parametros' }">

        {{-- Tab Navigation --}}
        <div class="mb-6 border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button @click="tab = 'parametros'"
                    :class="tab === 'parametros' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                    Parâmetros da Rede
                </button>
                <button @click="tab = 'modalidades'"
                    :class="tab === 'modalidades' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                    Modalidades de Ensino
                </button>
            </nav>
        </div>

        {{-- Parâmetros da Rede --}}
        <div x-show="tab === 'parametros'" x-cloak class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
            <div class="p-6">
                <h3 class="text-base font-bold mb-6 border-b pb-2">Regras e Parâmetros Gerais</h3>
                <form action="{{ route('secretaria.configuracoes.parametros.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="ano_letivo_vigente" :value="__('Ano Letivo Vigente')" />
                            <x-text-input id="ano_letivo_vigente" name="ano_letivo_vigente" type="number" class="mt-1 block w-full" :value="old('ano_letivo_vigente', $parametros->ano_letivo_vigente)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('ano_letivo_vigente')" />
                        </div>
                        <div>
                            <x-input-label for="dias_letivos_minimos" :value="__('Mínimo de Dias Letivos')" />
                            <x-text-input id="dias_letivos_minimos" name="dias_letivos_minimos" type="number" class="mt-1 block w-full" :value="old('dias_letivos_minimos', $parametros->dias_letivos_minimos)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('dias_letivos_minimos')" />
                        </div>
                        <div>
                            <x-input-label for="media_minima" :value="__('Média Mínima de Aprovação')" />
                            <x-text-input id="media_minima" name="media_minima" type="number" step="0.1" class="mt-1 block w-full" :value="old('media_minima', $parametros->media_minima)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('media_minima')" />
                        </div>
                        <div>
                            <x-input-label for="frequencia_minima" :value="__('Frequência Mínima (%)')" />
                            <x-text-input id="frequencia_minima" name="frequencia_minima" type="number" class="mt-1 block w-full" :value="old('frequencia_minima', $parametros->frequencia_minima)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('frequencia_minima')" />
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <x-input-label for="parametros_documentos" :value="__('Parâmetros de Documentos')" />
                            <textarea id="parametros_documentos" name="parametros_documentos" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('parametros_documentos', $parametros->parametros_documentos) }}</textarea>
                        </div>
                        <div>
                            <x-input-label for="parametros_upload" :value="__('Parâmetros de Upload')" />
                            <textarea id="parametros_upload" name="parametros_upload" rows="3" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('parametros_upload', $parametros->parametros_upload) }}</textarea>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <x-primary-button>{{ __('Salvar Parâmetros') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modalidades de Ensino --}}
        <div x-show="tab === 'modalidades'" x-cloak class="space-y-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
                <div class="p-6">
                    <h3 class="text-base font-bold mb-6">Modalidades Ativas</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 tracking-wider">
                                <tr>
                                    <th class="py-3 px-6">Nome</th>
                                    <th class="py-3 px-6">Estrutura</th>
                                    <th class="py-3 px-6 text-center">Tipo Avaliação</th>
                                    <th class="py-3 px-6 text-center">CH Mínima</th>
                                    <th class="py-3 px-6 text-center">Status</th>
                                    <th class="py-3 px-6 text-right">Ação</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($modalidades as $mod)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-4 px-6 font-medium text-gray-900">{{ $mod->nome }}</td>
                                    <td class="py-4 px-6">{{ $mod->estrutura_avaliativa }}</td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $mod->tipo_avaliacao === 'Nota' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                            {{ $mod->tipo_avaliacao }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-center">{{ $mod->carga_horaria_minima }}h</td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="{{ $mod->ativo ? 'text-green-600' : 'text-red-600' }} font-bold">{{ $mod->ativo ? 'Ativa' : 'Inativa' }}</span>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <form action="{{ route('secretaria.configuracoes.modalidades.toggle', $mod->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-sm {{ $mod->ativo ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }} font-semibold">
                                                {{ $mod->ativo ? 'Desativar' : 'Ativar' }}
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Nova Modalidade --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100">
                <div class="p-6">
                    <h3 class="text-base font-bold mb-6 border-b pb-2">Cadastrar Nova Modalidade</h3>
                    <form action="{{ route('secretaria.configuracoes.modalidades.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @csrf
                        <div>
                            <x-input-label for="nome" :value="__('Nome da Modalidade')" />
                            <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" placeholder="Ex: Ensino Médio" required />
                        </div>
                        <div>
                            <x-input-label for="estrutura_avaliativa" :value="__('Estrutura Avaliativa')" />
                            <select id="estrutura_avaliativa" name="estrutura_avaliativa" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                @foreach (['Bimestral', 'Trimestral', 'Semestral', 'Anual'] as $e)
                                    <option value="{{ $e }}">{{ $e }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="tipo_avaliacao" :value="__('Tipo de Avaliação')" />
                            <select id="tipo_avaliacao" name="tipo_avaliacao" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="Nota">Nota (0 a 10)</option>
                                <option value="Conceito">Conceito (A, B, C...)</option>
                                <option value="Parecer">Parecer Descritivo</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="carga_horaria_minima" :value="__('Carga Horária Mínima Anual')" />
                            <x-text-input id="carga_horaria_minima" name="carga_horaria_minima" type="number" class="mt-1 block w-full" value="800" required />
                        </div>
                        <div class="md:col-span-2 flex justify-end">
                            <x-primary-button>{{ __('Cadastrar Modalidade') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <style>[x-cloak] { display: none !important; }</style>

</x-secretaria-layout>
