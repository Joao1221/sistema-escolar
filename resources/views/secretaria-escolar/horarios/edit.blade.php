<x-secretaria-escolar-layout>
    <div class="px-8 py-6 max-w-4xl mx-auto">
        <div class="mb-6 px-6">
            <h2 class="text-2xl font-bold text-slate-800">Editar Horário de Aula</h2>
            <p class="text-sm text-slate-500 mt-1">Atualizar informações de um horário específico</p>
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

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <form action="{{ route('secretaria.horarios.update', $horario->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="escola_id" :value="__('Escola *')" class="text-slate-700" />
                        <select id="escola_id" name="escola_id" class="mt-1 block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            @foreach ($escolas as $escola)
                                <option value="{{ $escola->id }}" {{ (old('escola_id') ?? $horario->escola_id) == $escola->id ? 'selected' : '' }}>{{ $escola->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="turma_id" :value="__('Turma *')" class="text-slate-700" />
                        <select id="turma_id" name="turma_id" class="mt-1 block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            @foreach ($turmas as $turma)
                                <option value="{{ $turma->id }}" {{ (old('turma_id') ?? $horario->turma_id) == $turma->id ? 'selected' : '' }}>{{ $turma->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="dia_semana" :value="__('Dia da Semana *')" class="text-slate-700" />
                        <select id="dia_semana" name="dia_semana" class="mt-1 block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            @php $dias = [1 => 'Domingo', 2 => 'Segunda-feira', 3 => 'Terça-feira', 4 => 'Quarta-feira', 5 => 'Quinta-feira', 6 => 'Sexta-feira', 7 => 'Sábado']; @endphp
                            @foreach ($dias as $num => $nome)
                                <option value="{{ $num }}" {{ (old('dia_semana') ?? $horario->dia_semana) == $num ? 'selected' : '' }}>{{ $nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="horario_inicial" :value="__('Hora Inicial *')" class="text-slate-700" />
                            <input type="time" id="horario_inicial" name="horario_inicial" value="{{ old('horario_inicial') ?? \Carbon\Carbon::parse($horario->horario_inicial)->format('H:i') }}" class="mt-1 block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-center" required>
                        </div>
                        <div>
                            <x-input-label for="horario_final" :value="__('Hora Final *')" class="text-slate-700" />
                            <input type="time" id="horario_final" name="horario_final" value="{{ old('horario_final') ?? \Carbon\Carbon::parse($horario->horario_final)->format('H:i') }}" class="mt-1 block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-center" required>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <x-input-label for="disciplina_id" :value="__('Disciplina *')" class="text-slate-700" />
                        <select id="disciplina_id" name="disciplina_id" class="mt-1 block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                            @foreach ($disciplinas as $d)
                                <option value="{{ $d->id }}" {{ (old('disciplina_id') ?? $horario->disciplina_id) == $d->id ? 'selected' : '' }}>{{ $d->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2 flex gap-6">
                        <div class="flex-1">
                            <x-input-label for="professor_id" :value="__('Professor Responsável')" class="text-slate-700" />
                            <select id="professor_id" name="professor_id" class="mt-1 block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Nenhum professor alocado (TBD)</option>
                                @foreach ($professores as $p)
                                    <option value="{{ $p->id }}" {{ (old('professor_id') ?? $horario->professor_id) == $p->id ? 'selected' : '' }}>{{ $p->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-32">
                            <x-input-label for="ordem_aula" :value="__('Nº da Aula')" class="text-slate-700" />
                            <input type="number" id="ordem_aula" name="ordem_aula" min="1" max="10" value="{{ old('ordem_aula') ?? $horario->ordem_aula }}" class="mt-1 block w-full border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-center" placeholder="Ex: 1">
                        </div>
                    </div>
                </div>

                <div class="px-6 py-5 bg-slate-50 flex items-center justify-end space-x-4 border-t border-slate-200">
                    <a href="{{ route('secretaria.horarios.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-md font-semibold text-xs text-slate-700 uppercase tracking-widest hover:bg-slate-50 transition ease-in-out duration-150 shadow-sm">
                        Cancelar
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 transition ease-in-out duration-150 shadow-sm" style="background-color: #000000;">
                        Atualizar Horário
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-secretaria-escolar-layout>
