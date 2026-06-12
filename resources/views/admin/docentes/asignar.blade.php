<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Asignar Carga Horaria: ') }} <span class="text-indigo-600">{{ $usuario->nombre }} {{ $usuario->apellidopat }}</span>
            </h2>
            <a href="{{ route('admin.docentes.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded text-sm transition">
                Volver a Docentes
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Mostrar Errores/Exitos -->
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded shadow-sm" role="alert">
                    <p class="font-bold">¡Éxito!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded shadow-sm" role="alert">
                    <p class="font-bold">Error</p>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Formulario de Asignación -->
                <div class="col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Nueva Asignación</h3>
                            
                            <form action="{{ route('admin.docentes.asignar_materia', $usuario->ci) }}" method="POST" class="space-y-4">
                                @csrf
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Grupo</label>
                                    <select name="grupo_codigo" required class="mt-1 border-gray-300 rounded-md shadow-sm w-full focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Seleccione un Grupo...</option>
                                        @foreach($grupos as $grupo)
                                            <option value="{{ $grupo->codigo }}">{{ $grupo->codigo }} (Cupo: {{ $grupo->cupo }})</option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Recuerda: Un grupo sólo puede tener 1 docente por materia.</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Materia</label>
                                    <select name="materia_id" required class="mt-1 border-gray-300 rounded-md shadow-sm w-full focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Seleccione una Materia...</option>
                                        @foreach($materias as $materia)
                                            <option value="{{ $materia->id }}">{{ $materia->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Horario y Aula</label>
                                    <select name="horario_id" required class="mt-1 border-gray-300 rounded-md shadow-sm w-full focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Seleccione Horario...</option>
                                        @foreach($horarios as $horario)
                                            <option value="{{ $horario->id }}">
                                                {{ $horario->dia }} | {{ \Carbon\Carbon::parse($horario->iniciohorario)->format('H:i') }} - {{ \Carbon\Carbon::parse($horario->finhorario)->format('H:i') }} | Aula: {{ $horario->nroaula }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">El sistema validará que el docente no tenga choques de horario.</p>
                                </div>

                                <div class="pt-4">
                                    <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-2 px-4 rounded hover:bg-indigo-700 transition">
                                        Asignar Carga Horaria
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Lista de Asignaciones Actuales -->
                <div class="col-span-1 md:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg h-full">
                        <div class="p-6 bg-white">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Carga Horaria Actual de este Docente</h3>
                            
                            @if($asignaciones->isEmpty())
                                <div class="text-center py-8 bg-gray-50 rounded-lg border border-dashed border-gray-300">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">Este docente no tiene grupos ni horarios asignados todavía.</p>
                                </div>
                            @else
                                <div class="overflow-x-auto border rounded-lg border-gray-200">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Grupo</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Materia</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Día y Horario</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Aula</th>
                                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($asignaciones as $asig)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">
                                                        {{ $asig->codigogrupo }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                                        {{ $asig->materia_nombre }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <span class="block font-medium text-gray-700">{{ $asig->dia }}</span>
                                                        {{ \Carbon\Carbon::parse($asig->iniciohorario)->format('H:i') }} - {{ \Carbon\Carbon::parse($asig->finhorario)->format('H:i') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-600">
                                                        {{ $asig->nroaula }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <form action="{{ route('admin.docentes.remover_materia', [$usuario->ci, $asig->codigogrupo, $asig->idmateria]) }}" method="POST" onsubmit="return confirm('¿Está seguro de quitar esta asignación al docente?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-2 py-1 rounded transition">Quitar</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
