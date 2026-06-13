<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight border-l-4 border-indigo-600 pl-3">
            {{ __('Mi Horario de Clases y Aulas') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                <div class="px-6 py-5 border-b border-gray-200 bg-indigo-50 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-bold text-indigo-900">Carga Horaria Asignada</h3>
                </div>

                <div class="p-6">
                    @if($asignaciones->isEmpty())
                        <div class="text-center py-10">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Sin Horarios Asignados</h3>
                            <p class="mt-1 text-sm text-gray-500">Aún no se le han asignado grupos, materias ni aulas para el periodo actual.</p>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($asignaciones as $asignacion)
                                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-300">
                                    <div class="bg-indigo-600 px-4 py-3">
                                        <h4 class="text-white font-bold text-lg truncate">{{ $asignacion->materia }}</h4>
                                        <p class="text-indigo-100 text-sm">Grupo: {{ $asignacion->codigogrupo }}</p>
                                    </div>
                                    <div class="p-4 space-y-3">
                                        <div class="flex items-center text-gray-700">
                                            <svg class="h-5 w-5 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span class="font-medium">{{ $asignacion->dia }}</span>
                                        </div>
                                        <div class="flex items-center text-gray-700">
                                            <svg class="h-5 w-5 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="font-medium">{{ \Carbon\Carbon::parse($asignacion->iniciohorario)->format('H:i') }} - {{ \Carbon\Carbon::parse($asignacion->finhorario)->format('H:i') }}</span>
                                        </div>
                                        <div class="flex items-center text-gray-700">
                                            <svg class="h-5 w-5 mr-3 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            <span class="font-medium">Aula {{ $asignacion->nroaula }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
