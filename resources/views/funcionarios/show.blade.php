<x-secretaria-layout>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">{{ $funcionario->nome }}</h1>
            <p class="text-sm text-gray-500 mt-1 italic">{{ $funcionario->cargo }}</p>
        </div>
        <a href="{{ route('secretaria.funcionarios.index') }}" class="text-sm text-gray-500 hover:text-gray-700 font-medium transition">&larr; Voltar para Lista</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Card Lateral --}}
        <div class="md:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 text-center">
                <div class="w-24 h-24 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center text-4xl font-bold mx-auto mb-4 shadow">
                    {{ strtoupper(substr($funcionario->nome, 0, 1)) }}
                </div>
                <h3 class="text-xl font-bold text-gray-800">{{ $funcionario->nome }}</h3>
                <p class="text-sm text-indigo-600 font-semibold tracking-widest uppercase mt-1">{{ $funcionario->cargo }}</p>
                <div class="mt-6 pt-6 border-t border-gray-50">
                    <span class="inline-flex items-center px-4 py-1 rounded-full text-xs font-bold {{ $funcionario->ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $funcionario->ativo ? 'FUNCIONÁRIO ATIVO' : 'CADASTRO INATIVO' }}
                    </span>
                </div>
            </div>

            @can('editar funcionario')
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Controle</h4>
                <div class="flex flex-col space-y-3">
                    <a href="{{ route('secretaria.funcionarios.edit', $funcionario) }}" class="w-full text-center py-2 bg-indigo-600 text-white rounded-lg font-bold hover:bg-indigo-700 transition">Editar Perfil</a>
                    @can('ativar inativar funcionario')
                    <form action="{{ route('secretaria.funcionarios.toggle', $funcionario) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full py-2 border border-gray-200 text-gray-700 rounded-lg font-bold hover:bg-gray-50 transition">
                            {{ $funcionario->ativo ? 'Inativar Funcionário' : 'Reativar Cadastro' }}
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
            @endcan
        </div>

        {{-- Detalhes --}}
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 border-b pb-4 mb-6">Dados Cadastrais</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-8">
                    <div>
                        <dt class="text-xs font-bold text-gray-400 uppercase tracking-wide">CPF</dt>
                        <dd class="text-md font-medium text-gray-800">{{ $funcionario->cpf }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold text-gray-400 uppercase tracking-wide">E-mail</dt>
                        <dd class="text-md font-medium text-gray-800">{{ $funcionario->email ?? 'Não informado' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold text-gray-400 uppercase tracking-wide">Telefone</dt>
                        <dd class="text-md font-medium text-gray-800">{{ $funcionario->telefone ?? 'Não informado' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-bold text-gray-400 uppercase tracking-wide">Data de Cadastro</dt>
                        <dd class="text-md font-medium text-gray-800">{{ $funcionario->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                </dl>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 border-b pb-4 mb-6">Unidades Escolares em que Atua</h3>
                @if ($funcionario->escolas->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($funcionario->escolas as $escola)
                        <div class="flex items-center p-4 bg-gray-50 border border-gray-200 rounded-lg">
                            <span class="text-2xl mr-3">🏫</span>
                            <div>
                                <p class="font-bold text-gray-800 text-sm">{{ $escola->nome }}</p>
                                <p class="text-[10px] text-gray-500 uppercase">{{ $escola->cidade }} / {{ $escola->uf }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400 italic">Este funcionário não possui vínculos escolares ativos.</p>
                @endif
            </div>
        </div>

    </div>

</x-secretaria-layout>
