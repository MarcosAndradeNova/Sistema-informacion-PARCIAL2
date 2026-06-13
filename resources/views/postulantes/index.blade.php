<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">
            Gestión de Postulantes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl relative flex items-center gap-3 shadow-sm" role="alert">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="block sm:inline font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-slate-100">
                <div class="p-6 bg-white border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
                    
                    <!-- Buscador -->
                    <form method="GET" action="{{ route('postulantes.index') }}" class="w-full md:w-1/2">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   class="pl-10 pr-4 py-2 w-full border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50 text-sm" 
                                   placeholder="Buscar por CI, Nombre, Apellido o Correo...">
                            @if(request('search'))
                                <a href="{{ route('postulantes.index') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </a>
                            @endif
                        </div>
                    </form>

                    <!-- Acciones -->
                    <div>
                        <a href="{{ route('postulantes.create') }}" class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-xl shadow-md transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Registrar Postulante
                        </a>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full whitespace-nowrap text-sm text-left text-slate-600">
                        <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th scope="col" class="px-6 py-4 font-semibold">CI</th>
                                <th scope="col" class="px-6 py-4 font-semibold">Postulante</th>
                                <th scope="col" class="px-6 py-4 font-semibold">Contacto</th>
                                <th scope="col" class="px-6 py-4 font-semibold">1ra Opción</th>
                                <th scope="col" class="px-6 py-4 font-semibold text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($postulantes as $postulante)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-medium text-slate-900">
                                        {{ $postulante->ciusuario }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-900">{{ $postulante->usuario->nombres }} {{ $postulante->usuario->apellidos }}</div>
                                        <div class="text-xs text-slate-500">{{ $postulante->ciudad }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>{{ $postulante->usuario->correo_electronico }}</div>
                                        <div class="text-xs text-slate-500">{{ $postulante->usuario->telefono ?? 'Sin teléfono' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ $postulante->primeraOpcion->nombre ?? 'No asignada' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('postulantes.edit', $postulante->ciusuario) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 p-2 rounded-lg transition-colors tooltip" title="Editar">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('postulantes.destroy', $postulante->ciusuario) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de eliminar este postulante?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 p-2 rounded-lg transition-colors tooltip" title="Eliminar">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                        <svg class="mx-auto h-12 w-12 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        No se encontraron postulantes registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($postulantes->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                        {{ $postulantes->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
