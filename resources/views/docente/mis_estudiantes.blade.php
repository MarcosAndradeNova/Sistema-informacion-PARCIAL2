<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight border-l-4 border-blue-800 pl-3">
            {{ __('Registro General de Estudiantes') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Buscador -->
            <div class="bg-white p-4 shadow-sm border border-gray-200 flex justify-between items-center">
                <form action="{{ route('docente.mis_estudiantes') }}" method="GET" class="w-full max-w-lg flex items-center">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ $search ?? '' }}" class="focus:ring-blue-800 focus:border-blue-800 block w-full pl-10 sm:text-sm border-gray-300 rounded-none" placeholder="Buscar estudiante por nombre o apellido...">
                    </div>
                    <button type="submit" class="ml-3 bg-slate-800 hover:bg-slate-900 text-white font-medium py-2 px-4 border border-transparent shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-800 transition-colors">
                        Buscar
                    </button>
                    @if(isset($search) && $search !== '')
                        <a href="{{ route('docente.mis_estudiantes') }}" class="ml-2 text-sm text-blue-600 hover:text-blue-800 font-medium">Limpiar</a>
                    @endif
                </form>
            </div>
            
            @php $tieneResultados = false; @endphp
            @forelse($grupos as $grupo)
                @if($grupo->postulantes->count() > 0)
                    @php $tieneResultados = true; @endphp
                <div class="bg-white shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 bg-slate-100 flex items-center justify-between">
                        <h3 class="text-base font-bold text-gray-800 uppercase tracking-wide">Clasificación: {{ $grupo->nombre }}</h3>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total: {{ $grupo->postulantes->count() }} inscritos</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">N° Documento (CI)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Apellidos y Nombres</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estado Académico</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($grupo->postulantes as $postulante)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">
                                            {{ $postulante->ci_usuario }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 uppercase">
                                                {{ $postulante->usuario->apellido_pat ?? '' }} {{ $postulante->usuario->apellido_mat ?? '' }} {{ $postulante->usuario->nombre ?? '' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 border border-gray-300 text-xs font-medium bg-gray-100 text-gray-800">
                                                Inscrito Oficial
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            @empty
                <div class="bg-white border border-gray-200 p-10 text-center shadow-sm">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Registros Vacíos</h3>
                    <p class="mt-2 text-sm text-gray-500">No se encontraron expedientes de estudiantes activos en el periodo actual.</p>
                </div>
            @endforelse

            @if(!$tieneResultados && count($grupos) > 0)
                <div class="bg-white border border-gray-200 p-10 text-center shadow-sm">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Búsqueda sin resultados</h3>
                    <p class="mt-2 text-sm text-gray-500">No se encontraron estudiantes que coincidan con "{{ $search }}".</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
