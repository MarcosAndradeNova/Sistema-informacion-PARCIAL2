<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestión de Docentes') }}
            </h2>
            <div class="flex space-x-3">
                <form action="{{ route('admin.docentes.auto_asignar') }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm transition shadow-sm" onclick="return confirm('¿Está seguro de generar horarios automáticamente para todos los grupos?');">
                        <svg class="w-4 h-4 inline-block mr-1 -mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                        Auto-Asignar Horarios
                    </button>
                </form>
                <a href="{{ route('admin.docentes.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm transition shadow-sm">
                    + Agregar Nuevo Docente
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
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
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Carga Horaria Asignada</th>
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
                                    <td class="px-6 py-4">
                                        @if($infoDocente && $infoDocente->estado === 'APROBADO')
                                            @php
                                                // Obtener asignaciones actuales
                                                $asignaciones = \Illuminate\Support\Facades\DB::table('grupodocente')
                                                    ->join('materia', 'grupodocente.idmateria', '=', 'materia.id')
                                                    ->join('horario', 'grupodocente.idhorario', '=', 'horario.id')
                                                    ->where('ciusuario', $docente->ci)
                                                    ->select('grupodocente.codigogrupo', 'materia.nombre as materia', 'horario.dia', 'horario.iniciohorario', 'horario.finhorario', 'horario.nroaula')
                                                    ->get();
                                            @endphp
                                            
                                            @if($asignaciones->count() > 0)
                                                <ul class="text-xs text-gray-600 space-y-1 mb-2">
                                                    @foreach($asignaciones as $asig)
                                                        <li class="bg-indigo-50 px-2 py-1 rounded">
                                                            <span class="font-bold text-indigo-700">{{ $asig->codigogrupo }}</span> - {{ $asig->materia }} 
                                                            <br>
                                                            <span class="text-gray-500">{{ \Carbon\Carbon::parse($asig->iniciohorario)->format('H:i') }} a {{ \Carbon\Carbon::parse($asig->finhorario)->format('H:i') }} | Aula: {{ $asig->nroaula }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <span class="text-sm text-gray-500 mb-2 block">Sin materias asignadas.</span>
                                            @endif
                                            
                                            <a href="{{ route('admin.docentes.asignar', $docente->ci) }}" class="text-xs inline-flex items-center text-indigo-600 hover:text-indigo-900 font-bold">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                                Gestionar Carga Horaria
                                            </a>
                                        @else
                                            <span class="text-sm text-gray-400">Requiere aprobación previa</span>
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
                                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
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
