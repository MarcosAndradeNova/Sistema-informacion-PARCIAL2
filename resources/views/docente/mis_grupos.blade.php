<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight border-l-4 border-blue-800 pl-3">
            {{ __('Mis Grupos y Estudiantes') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md shadow-sm">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Buscador -->
            <div class="bg-white p-4 shadow-sm border border-gray-200 flex justify-between items-center rounded-lg">
                <form action="{{ route('docente.mis_grupos') }}" method="GET" class="w-full max-w-lg flex items-center">
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
                        <a href="{{ route('docente.mis_grupos') }}" class="ml-2 text-sm text-blue-600 hover:text-blue-800 font-medium">Limpiar</a>
                    @endif
                </form>
            </div>
            
            @forelse($gruposDocente as $miGrupo)
                @php 
                    // Encontrar el grupo correspondiente en la colección cargada con postulantes
                    $grupoData = $grupos->where('codigo', $miGrupo->codigogrupo)->first();
                    $estudiantes = $grupoData ? $grupoData->postulantes : collect();
                @endphp
                <div x-data="{ expanded: false }" class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden">
                    
                    <!-- Cabecera del Grupo -->
                    <div class="px-6 py-5 border-b border-gray-200 bg-slate-50">
                        <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
                            <div>
                                <h3 class="text-xl font-bold text-gray-800 uppercase tracking-wide">
                                    Grupo: {{ $miGrupo->codigogrupo }} - {{ $miGrupo->nombre_grupo }}
                                </h3>
                                <div class="mt-2 text-sm text-gray-600 flex items-center gap-4">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $miGrupo->dia }}
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ \Carbon\Carbon::parse($miGrupo->iniciohorario)->format('H:i') }} a {{ \Carbon\Carbon::parse($miGrupo->finhorario)->format('H:i') }}
                                    </span>
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        Aula {{ $miGrupo->nroaula }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Botón y WhatsApp -->
                            <div class="flex flex-col items-end gap-2">
                                <div class="flex items-center space-x-2">
                                    <button @click="expanded = !expanded" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                        <span x-text="expanded ? 'Ocultar Estudiantes' : 'Ver Estudiantes ({{ $estudiantes->count() }})'"></span>
                                        <svg class="w-4 h-4 ml-2 transform transition-transform" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Formulario de Enlace de WhatsApp -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <form action="{{ route('docente.mis_grupos.whatsapp') }}" method="POST" class="flex items-center gap-3">
                                @csrf
                                <input type="hidden" name="grupodocente_id" value="{{ $miGrupo->codigogrupo }}">
                                
                                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-600">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 0 0-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                                </div>
                                <div class="flex-1">
                                    <input type="url" name="whatsapp_link" value="{{ $miGrupo->whatsapp_link }}" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500" placeholder="https://chat.whatsapp.com/...">
                                </div>
                                <button type="submit" class="px-4 py-2 bg-green-50 text-green-700 hover:bg-green-100 border border-green-200 rounded-md font-bold text-xs transition-colors">
                                    Guardar Enlace
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Lista de Estudiantes (Oculta por defecto) -->
                    <div x-show="expanded" x-transition.opacity class="border-t border-gray-200">
                        @if($estudiantes->count() > 0)
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
                                    @foreach($estudiantes as $postulante)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">
                                                {{ $postulante->ciusuario }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900 uppercase">
                                                    {{ $postulante->usuario->apellidopat ?? '' }} {{ $postulante->usuario->apellidomat ?? '' }} {{ $postulante->usuario->nombre ?? '' }}
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
                        @else
                            <div class="p-6 text-center text-gray-500 text-sm">
                                No hay estudiantes asignados o que coincidan con la búsqueda en este grupo.
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white border border-gray-200 p-10 text-center shadow-sm rounded-lg">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Registros Vacíos</h3>
                    <p class="mt-2 text-sm text-gray-500">Actualmente no tienes grupos asignados en el sistema.</p>
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>
