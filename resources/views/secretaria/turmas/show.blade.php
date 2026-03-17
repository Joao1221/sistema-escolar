<x-secretaria-layout>

    <div class="flex justify-between items-start mb-6">
        <div>
            <div class="flex items-center space-x-3">
                <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 text-[10px] font-bold rounded uppercase">{{ $turma->ano_letivo }}</span>
                <h1 class="text-2xl font-bold text-gray-800 uppercase">{{ $turma->nome }}</h1>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ $turma->ativa ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ $turma->ativa ? 'ATIVA' : 'INATIVA' }}
                </span>
            </div>
            <p class="text-sm text-gray-500 mt-1 uppercase">{{ $turma->escola->nome }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('secretaria.turmas.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                Voltar
            </a>
            <div class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg font-semibold text-xs uppercase tracking-widest cursor-not-allowed italic" title="A edição é feita apenas no Portal Escolar">
                Somente Leitura
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- Card de Informações --}}
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-8">
                <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-6 border-b pb-2">Panorama da Turma</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Modalidade de Ensino</p>
                        <p class="text-sm text-gray-800 font-bold uppercase mt-1">{{ $turma->modalidade->nome }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Série / Etapa</p>
                        <p class="text-sm text-gray-800 font-bold uppercase mt-1">{{ $turma->serie_etapa }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Turno de Funcionamento</p>
                        <p class="text-sm text-gray-800 font-bold uppercase mt-1">{{ $turma->turno }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Capacidade Total</p>
                        <p class="text-sm text-gray-800 font-bold uppercase mt-1">{{ $turma->vagas }} vagas</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Multisseriada</p>
                        <p class="text-sm text-gray-800 font-bold uppercase mt-1">{{ $turma->is_multisseriada ? 'Sim' : 'Não' }}</p>
                    </div>
                </div>
            </div>

            {{-- Área Estatística --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 p-8">
                 <h3 class="text-sm font-bold text-gray-400 uppercase tracking-widest border-b pb-2 mb-6">Informações Acadêmicas</h3>
                 <div class="bg-indigo-50 border border-indigo-100 p-6 rounded-2xl text-center">
                    <p class="text-indigo-800 text-sm font-medium">Os dados de matrículas, frequência e desempenho estarão disponíveis em módulos futuros.</p>
                 </div>
            </div>
        </div>

        {{-- Sidebar da Unidade --}}
        <div class="space-y-6 text-medium">
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-indigo-50">
                <h4 class="text-indigo-400 text-[10px] font-bold uppercase tracking-widest mb-4">Dados da Unidade</h4>
                <div class="space-y-4">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Escola</p>
                        <p class="text-sm font-bold uppercase text-gray-800">{{ $turma->escola->nome }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Gestor</p>
                        <p class="text-sm text-gray-700">{{ $turma->escola->nome_gestor ?: 'Não informado' }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Cidade/UF</p>
                        <p class="text-sm text-gray-700 uppercase">{{ $turma->escola->cidade }} - {{ $turma->escola->uf }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

</x-secretaria-layout>
