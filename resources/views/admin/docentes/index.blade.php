<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Docentes') }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('admin.docentes.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm transition shadow-sm">
                    + Agregar Nuevo Docente
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-bold text-gray-900">Listado de Docentes Registrados</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Docente</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Contacto</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materia Especializada</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aprobación</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($docentes as $docente)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-bold">
                                                {{ substr($docente->nombre, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 capitalize">{{ strtolower($docente->nombre) }} {{ strtolower($docente->apellidopat) }}</div>
                                                <div class="text-sm text-gray-500">CI: {{ $docente->ci }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $docente->email }}</div>
                                        <div class="text-sm text-gray-500">{{ $docente->telefono ?? 'Sin teléfono' }}</div>
                                    </td>
                                    @php
                                        $infoDocente = \App\Models\Docente::where('ciusuario', $docente->ci)->first();
                                        $estado = $infoDocente ? $infoDocente->estado : 'INCOMPLETO';
                                    @endphp
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $materiaEspecializada = $infoDocente && $infoDocente->idmateria 
                                                ? \App\Models\Materia::find($infoDocente->idmateria) 
                                                : null;
                                        @endphp
                                        @if($materiaEspecializada)
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                                {{ $materiaEspecializada->nombre }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400 italic">Sin asignar</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($estado === 'APROBADO')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aprobado</span>
                                        @elseif($estado === 'RECHAZADO')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rechazado</span>
                                        @elseif($estado === 'PENDIENTE')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Registro Incompleto</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-2">
                                        @if($estado === 'PENDIENTE' || $estado === 'RECHAZADO')
                                            <form action="{{ route('admin.docentes.aprobar', $docente->ci) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-green-50 text-green-700 hover:bg-green-100 border border-green-200 rounded-md font-bold transition-colors">
                                                    Aprobar
                                                </button>
                                            </form>
                                        @endif
                                        @if($estado === 'PENDIENTE' || $estado === 'APROBADO')
                                            <form action="{{ route('admin.docentes.rechazar', $docente->ci) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 hover:bg-red-100 border border-red-200 rounded-md font-bold transition-colors">
                                                    Rechazar
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('admin.docentes.edit', $docente->ci) }}" class="text-blue-600 hover:text-blue-900 font-bold block mb-2">Editar Info</a>
                                        
                                        <form action="{{ route('admin.docentes.destroy', $docente->ci) }}" method="POST" onsubmit="return confirm('¿Está completamente seguro de eliminar a este docente? Todos sus datos y horarios se perderán.');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                        No hay docentes registrados en el sistema.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
