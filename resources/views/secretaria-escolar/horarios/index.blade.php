<x-secretaria-escolar-layout>
    <div class="px-8 py-6">
        <div class="flex justify-between items-center mb-6 px-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Horários de Aulas</h2>
                <p class="text-sm text-slate-500 mt-1">Gestão de horários e grades curriculares das turmas</p>
            </div>
            
            <a href="{{ route('secretaria-escolar.horarios.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-800 active:bg-black focus:outline-none transition ease-in-out duration-150" style="background-color: #0f172a;">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Nova Grade / Horário
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="p-4 border-b border-slate-200 bg-slate-50">
                <form method="GET" action="{{ route('secretaria-escolar.horarios.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="escola_id" class="block text-xs font-semibold text-slate-600 uppercase mb-1">Escola</label>
                        <select name="escola_id" id="escola_id" class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" {{ $escolas->count() == 1 ? 'readonly style=pointer-events:none;background-color:#f8fafc;' : '' }}>
                            @if ($escolas->count() > 1)
                                <option value="">Todas</option>
                            @endif
                            @foreach ($escolas as $escola)
                                <option value="{{ $escola->id }}" {{ (request('escola_id') == $escola->id || $escolas->count() == 1) ? 'selected' : '' }}>{{ $escola->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="turma_id" class="block text-xs font-semibold text-slate-600 uppercase mb-1">Turma</label>
                        <select name="turma_id" id="turma_id" class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Todas</option>
                            @foreach ($turmas as $turma)
                                <option value="{{ $turma->id }}" {{ request('turma_id') == $turma->id ? 'selected' : '' }}>{{ $turma->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="professor_id" class="block text-xs font-semibold text-slate-600 uppercase mb-1">Professor</label>
                        <select name="professor_id" id="professor_id" class="mt-1 block w-full border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Todos</option>
                            @foreach ($professores as $prof)
                                <option value="{{ $prof->id }}" {{ request('professor_id') == $prof->id ? 'selected' : '' }}>{{ $prof->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-slate-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-slate-700 active:bg-slate-900 focus:outline-none transition ease-in-out duration-150">
                            Filtrar
                        </button>
                        <a href="{{ route('secretaria-escolar.horarios.index') }}" class="inline-flex justify-center items-center px-4 py-2 bg-white border border-slate-300 rounded-md font-semibold text-xs text-slate-700 uppercase tracking-widest hover:bg-slate-50 transition ease-in-out duration-150">
                            Limpar
                        </a>
                    </div>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-semibold">Turma</th>
                            <th scope="col" class="px-6 py-3 font-semibold text-center">Dia da Semana</th>
                            <th scope="col" class="px-6 py-3 font-semibold text-center">Horário</th>
                            <th scope="col" class="px-6 py-3 font-semibold">Disciplina / Professor</th>
                            <th scope="col" class="px-6 py-3 font-semibold text-right">Ação</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @php
                            $diasSemana = [1 => 'Domingo', 2 => 'Segunda-feira', 3 => 'Terça-feira', 4 => 'Quarta-feira', 5 => 'Quinta-feira', 6 => 'Sexta-feira', 7 => 'Sábado'];
                        @endphp
                        @forelse ($horarios as $h)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-900">{{ $h->turma->nome }}</div>
                                    <div class="text-xs text-slate-500">{{ $h->escola->nome }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $diasSemana[$h->dia_semana] ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-slate-900 font-medium">
                                        {{ \Carbon\Carbon::parse($h->horario_inicial)->format('H:i') }} às {{ \Carbon\Carbon::parse($h->horario_final)->format('H:i') }}
                                    </div>
                                    @if ($h->ordem_aula)
                                        <div class="text-xs text-slate-500">{{ $h->ordem_aula }}ª Aula</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium text-slate-900">{{ $h->disciplina->nome }}</div>
                                    <div class="text-xs text-slate-500">{{ $h->professor ? $h->professor->nome : 'Sem professor alocado' }}</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end space-x-3">
                                        <a href="{{ route('secretaria-escolar.horarios.edit', $h->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Editar">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                        <form action="{{ route('secretaria-escolar.horarios.destroy', $h->id) }}" method="POST" class="inline" onsubmit="return confirm('Tem certeza que deseja remover este horário?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700" title="Remover">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                    Nenhum horário cadastrado ou encontrado para os filtros selecionados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if ($horarios->hasPages())
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $horarios->links() }}
                </div>
            @endif
        </div>
    </div>
</x-secretaria-escolar-layout>
