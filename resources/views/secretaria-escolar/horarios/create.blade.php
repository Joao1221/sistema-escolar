<x-secretaria-escolar-layout>
    <div class="px-8 py-6">
        <div class="mb-6 px-6">
            <h2 class="text-2xl font-bold text-slate-800">Nova Grade de Horários</h2>
            <p class="text-sm text-slate-500 mt-1">Cadastrar os horários das disciplinas para uma turma</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                <div class="flex items-center mb-2">
                    <svg class="h-5 w-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="text-sm font-medium text-red-800">Foram encontrados erros na validação:</h3>
                </div>
                <ul class="text-sm text-red-700 list-disc list-inside bg-red-50">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden" x-data="horarioForm()">
            <form action="{{ route('secretaria-escolar.horarios.store') }}" method="POST">
                @csrf
                
                {{-- Informações Básicas --}}
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Informações da Turma</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="escola_id" :value="__('Escola *')" class="text-slate-700" />
                            <select id="escola_id" name="escola_id" class="mt-1 block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required x-model="escolaId">
                                <option value="">Selecione uma escola...</option>
                                @foreach ($escolas as $escola)
                                    <option value="{{ $escola->id }}" {{ old('escola_id') == $escola->id ? 'selected' : '' }}>{{ $escola->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="turma_id" :value="__('Turma *')" class="text-slate-700" />
                            <select id="turma_id" name="turma_id" class="mt-1 block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Selecione a turma...</option>
                                @foreach ($turmas as $turma)
                                    <option value="{{ $turma->id }}" {{ old('turma_id') == $turma->id ? 'selected' : '' }}>{{ $turma->nome }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-slate-500 mt-1">Dica: Um filtro dinâmico de turma por escola pode ser adicionado futuramente.</p>
                        </div>
                    </div>
                </div>

                {{-- Quadro de Horários Dinâmicos --}}
                <div class="p-6 bg-slate-50">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-slate-800">Grade de Encontros</h3>
                        <button type="button" @click="addHorario()" class="px-4 py-2 text-white rounded-lg text-xs font-bold uppercase transition hover:bg-slate-700 shadow-sm inline-flex items-center" style="background-color: #000000;">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Adicionar Aula
                        </button>
                    </div>

                    <table class="w-full text-left border-collapse" x-show="horarios.length > 0">
                        <thead>
                            <tr class="text-[11px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">
                                <th class="pb-2 w-32">Dia da Semana *</th>
                                <th class="pb-2 w-24">Início *</th>
                                <th class="pb-2 w-24">Fim *</th>
                                <th class="pb-2">Disciplina *</th>
                                <th class="pb-2">Professor</th>
                                <th class="pb-2 w-16 text-center">Aula Nº</th>
                                <th class="pb-2 w-10 text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200/60">
                            <template x-for="(h, index) in horarios" :key="index">
                                <tr>
                                    <td class="py-3 pr-2">
                                        <select :name="`horarios[${index}][dia_semana]`" x-model="h.dia_semana" class="w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded text-sm py-2 shadow-sm" required>
                                            <option value="">Dia...</option>
                                            <option value="1">Domingo</option>
                                            <option value="2">Segunda-feira</option>
                                            <option value="3">Terça-feira</option>
                                            <option value="4">Quarta-feira</option>
                                            <option value="5">Quinta-feira</option>
                                            <option value="6">Sexta-feira</option>
                                            <option value="7">Sábado</option>
                                        </select>
                                    </td>
                                    <td class="py-3 px-1">
                                        <input type="time" :name="`horarios[${index}][horario_inicial]`" x-model="h.horario_inicial" class="w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded text-sm py-2 shadow-sm text-center" required>
                                    </td>
                                    <td class="py-3 px-1">
                                        <input type="time" :name="`horarios[${index}][horario_final]`" x-model="h.horario_final" class="w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded text-sm py-2 shadow-sm text-center" required>
                                    </td>
                                    <td class="py-3 px-2">
                                        <select :name="`horarios[${index}][disciplina_id]`" x-model="h.disciplina_id" class="w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded text-sm py-2 shadow-sm" required>
                                            <option value="">Selecione a Disciplina...</option>
                                            @foreach ($disciplinas as $d)
                                                <option value="{{ $d->id }}">{{ $d->nome }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-3 px-2">
                                        <select :name="`horarios[${index}][professor_id]`" x-model="h.professor_id" class="w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded text-sm py-2 shadow-sm">
                                            <option value="">Sem professor alocado...</option>
                                            @foreach ($professores as $p)
                                                <option value="{{ $p->id }}">{{ $p->nome }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-3 px-1">
                                        <input type="number" min="1" max="10" :name="`horarios[${index}][ordem_aula]`" x-model="h.ordem_aula" class="w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded text-sm py-2 shadow-sm text-center" placeholder="1">
                                    </td>
                                    <td class="py-3 pl-2 text-center">
                                        <button type="button" @click="removeHorario(index)" class="p-1.5 text-red-500 hover:text-white hover:bg-red-500 rounded-md transition-colors shadow-sm bg-white border border-red-200" title="Remover">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>

                    <div x-show="horarios.length === 0" class="text-center py-10 bg-white rounded-lg border border-dashed border-slate-300 text-slate-500 flex flex-col items-center justify-center">
                        <svg class="w-10 h-10 mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm">Nenhum horário adicionado na grade ainda.</p>
                        <p class="text-xs mt-1 text-slate-400">Clique no botão "Adicionar Aula" para montar os horários da semana para esta turma.</p>
                    </div>
                </div>

                {{-- Footer / Botões --}}
                <div class="px-6 py-5 bg-slate-50 flex items-center justify-end space-x-4 border-t border-slate-200">
                    <a href="{{ route('secretaria-escolar.horarios.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-md font-semibold text-xs text-slate-700 uppercase tracking-widest hover:bg-slate-50 transition ease-in-out duration-150 shadow-sm">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm" style="background-color: #000000;" :disabled="horarios.length === 0 || !turmaId || !escolaId" :class="{'opacity-50 cursor-not-allowed': horarios.length === 0 || !turmaId || !escolaId}">
                        Salvar Grade
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function horarioForm() {
            return {
                escolaId: '{{ old('escola_id') }}',
                turmaId: '{{ old('turma_id') }}',
                horarios: [
                    { dia_semana: '', horario_inicial: '', horario_final: '', disciplina_id: '', professor_id: '', ordem_aula: '' }
                ],
                addHorario() {
                    // Copiar o último horário como base se existir
                    let last = this.horarios.length > 0 ? this.horarios[this.horarios.length - 1] : null;
                    this.horarios.push({ 
                        dia_semana: last ? last.dia_semana : '', 
                        horario_inicial: last ? last.horario_inicial : '', 
                        horario_final: last ? last.horario_final : '', 
                        disciplina_id: '', 
                        professor_id: '', 
                        ordem_aula: typeof last?.ordem_aula === 'number' ? last.ordem_aula + 1 : ''
                    });
                },
                removeHorario(index) {
                    this.horarios.splice(index, 1);
                }
            }
        }
    </script>
    @endpush
</x-secretaria-escolar-layout>
