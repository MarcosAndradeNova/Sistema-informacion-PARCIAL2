<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalles del Grupo: ') }} {{ $grupo->nombre }}
            </h2>
            <a href="{{ route('admin.grupos.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">&larr; Volver a Grupos</a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 bg-white border-b border-gray-200 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">{{ $grupo->nombre }}</h3>
                        <p class="text-gray-500 text-sm">Capacidad: {{ count($postulaciones) }} / {{ $grupo->cupo ?? 70 }} estudiantes asignados.</p>
                    </div>
                    <div>
                        @if(isset($grupo->estado) && $grupo->estado)
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">Grupo Activo</span>
                        @else
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">Grupo Activo</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h4 class="text-md font-bold text-gray-800 mb-4">Lista de Estudiantes (Postulantes)</h4>
                    
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CI</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre Completo</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Colegio de Procedencia</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($postulaciones as $postulacion)
                                    @php $postulante = $postulacion->postulante; @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $postulante ? $postulante->ciusuario : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $postulante && $postulante->usuario ? $postulante->usuario->nombre . ' ' . $postulante->usuario->apellidopat . ' ' . $postulante->usuario->apellidomat : 'Desconocido' }}
                                            </div>
                                            <div class="text-sm text-gray-500">{{ $postulante && $postulante->usuario ? $postulante->usuario->email : '' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $postulante ? $postulante->colegioprocedencia : 'N/A' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            Aún no hay estudiantes asignados a este grupo.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
