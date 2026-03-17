<x-secretaria-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Matriz Curricular') }}: {{ $matriz->nome }}
        </h2>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-8">
        <form action="{{ route('secretaria.matrizes.update', $matriz) }}" method="POST" x-data="matrizForm()">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="md:col-span-2">
                    <x-input-label for="nome" :value="__('Nome da Matriz')" />
                    <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" required autofocus :value="old('nome', $matriz->nome)" />
                    <x-input-error class="mt-2" :messages="$errors->get('nome')" />
                </div>

                <div>
                    <x-input-label for="ano_vigencia" :value="__('Ano de Vigência')" />
                    <x-text-input id="ano_vigencia" name="ano_vigencia" type="number" class="mt-1 block w-full" required :value="old('ano_vigencia', $matriz->ano_vigencia)" />
                    <x-input-error class="mt-2" :messages="$errors->get('ano_vigencia')" />
                </div>

                <div>
                    <x-input-label for="modalidade_id" :value="__('Modalidade')" />
                    <select id="modalidade_id" name="modalidade_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        <option value="">Selecione...</option>
                        @foreach ($modalidades as $mod)
                            <option value="{{ $mod->id }}" {{ old('modalidade_id', $matriz->modalidade_id) == $mod->id ? 'selected' : '' }}>{{ $mod->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-input-label for="serie_etapa" :value="__('Série / Etapa')" />
                    <x-text-input id="serie_etapa" name="serie_etapa" type="text" class="mt-1 block w-full" required :value="old('serie_etapa', $matriz->serie_etapa)" />
                </div>

                <div>
                    <x-input-label for="escola_id" :value="__('Escola (Opcional - Em branco para Rede)')" />
                    <select id="escola_id" name="escola_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">--- TODAS AS ESCOLAS ---</option>
                        @foreach ($escolas as $esc)
                            <option value="{{ $esc->id }}" {{ old('escola_id', $matriz->escola_id) == $esc->id ? 'selected' : '' }}>{{ $esc->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="border-t border-gray-100 pt-6 mt-6">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="text-lg font-bold text-gray-800">Composição Curricular (Disciplinas)</h3>
                    <button type="button" @click="addDisciplina" class="px-4 py-2 bg-black text-white rounded-lg text-xs font-bold uppercase transition hover:bg-gray-800" style="background-color: black;">
                        + Vincular Disciplina
                    </button>
                </div>

                <table class="w-full" x-show="disciplinasSelecionadas.length > 0">
                    <thead>
                        <tr class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">
                            <th class="text-left pb-2 pl-2">Disciplina</th>
                            <th class="text-center pb-2 w-20">C.H.</th>
                            <th class="text-center pb-2 w-16">Obrig.</th>
                            <th class="pb-2 w-10"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template x-for="(item, index) in disciplinasSelecionadas" :key="index">
                            <tr class="border-t border-gray-100">
                                <td class="py-1.5 pr-2">
                                    <select :name="`disciplinas[${index}][id]`" x-model="item.id" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded text-sm py-1.5" required>
                                        <option value="">Selecione...</option>
                                        @foreach ($disciplinas as $d)
                                            <option value="{{ $d->id }}">{{ $d->nome }} ({{ $d->codigo }})</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="py-1.5 px-1">
                                    <input type="number" :name="`disciplinas[${index}][carga_horaria]`" x-model="item.carga_horaria" class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded text-sm py-1.5 text-center" required placeholder="h">
                                </td>
                                <td class="py-1.5 text-center">
                                    <input type="checkbox" :name="`disciplinas[${index}][obrigatoria]`" value="1" x-model="item.obrigatoria" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                </td>
                                <td class="py-1.5 text-center">
                                    <button type="button" @click="removeDisciplina(index)" class="text-red-400 hover:text-red-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <div class="mt-12 flex items-center justify-end space-x-6 border-t border-gray-100 pt-8">
                <a href="{{ route('secretaria.matrizes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 transition ease-in-out duration-150">Cancelar</a>
                <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 py-3 px-8">
                    {{ __('Atualizar Matriz Curricular') }}
                </x-primary-button>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        function matrizForm() {
            return {
                disciplinasSelecionadas: @json($matriz->disciplinas->map(fn($d) => [
                    'id' => $d->id,
                    'carga_horaria' => $d->pivot->carga_horaria,
                    'obrigatoria' => (bool)$d->pivot->obrigatoria
                ])),
                addDisciplina() {
                    this.disciplinasSelecionadas.push({
                        id: '',
                        carga_horaria: '',
                        obrigatoria: true
                    });
                },
                removeDisciplina(index) {
                    this.disciplinasSelecionadas.splice(index, 1);
                }
            }
        }
    </script>
    @endpush
</x-secretaria-layout>
