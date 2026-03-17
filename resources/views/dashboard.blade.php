<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Painel Inicial (Dashboard)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold">Bem-vindo(a), {{ Auth::user()->name }}!</h3>
                    
                    <p class="mt-4">
                        <strong>Seu Perfil de Acesso:</strong> 
                        @foreach (Auth::user()->getRoleNames() as $role)
                            <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">{{ $role }}</span>
                        @endforeach
                    </p>

                    @hasrole('Administrador da Rede')
                        <div class="mt-4 p-4 bg-green-100 text-green-800 rounded">
                            <p>✅ Você tem acesso total como Administrador da Rede. A infraestrutura e as permissões estão ativas.</p>
                        </div>
                    @endhasrole
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
