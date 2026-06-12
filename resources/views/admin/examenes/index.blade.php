<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight border-l-4 border-indigo-600 pl-3">
            {{ __('Auditoría y Gestión de Calificaciones') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-600 text-green-800 p-4 shadow-sm" role="alert">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Selección de Materia y Grupo -->
            <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6">
                <form action="{{ route('admin.examenes.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-6">
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-2 uppercase tracking-wide">Materia</label>
                        <select name="materia_id" class="focus:ring-indigo-600 focus:border-indigo-600 block w-full text-sm border-gray-300 rounded-md bg-gray-50 p-2.5">
                            <option value="">Seleccione materia...</option>
                            @foreach($materias as $materia)
                                <option value="{{ $materia->id }}" {{ ($materiaSeleccionada && $materiaSeleccionada->id == $materia->id) ? 'selected' : '' }}>
                                    {{ $materia->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-semibold text-slate-700 mb-2 uppercase tracking-wide">Grupo</label>
                        <select name="grupo_id" class="focus:ring-indigo-600 focus:border-indigo-600 block w-full text-sm border-gray-300 rounded-md bg-gray-50 p-2.5">
                            <option value="">Seleccione grupo...</option>
                            @foreach($grupos as $grupo)
                                <option value="{{ $grupo->codigo }}" {{ ($grupoSeleccionado && $grupoSeleccionado->codigo == $grupo->codigo) ? 'selected' : '' }}>
                                    Grupo {{ $grupo->codigo }} ({{ $grupo->turno }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2.5 px-6 rounded-md shadow-sm border border-transparent focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600 transition-colors">
                        Visualizar Actas
                    </button>
                </form>
            </div>

            @if($materiaSeleccionada && $grupoSeleccionado)
                <div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                        <h3 class="text-base font-bold text-gray-800 uppercase tracking-wide">
                            Acta Oficial: {{ $materiaSeleccionada->nombre }} - Grupo {{ $grupoSeleccionado->codigo }}
                        </h3>
                        <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full uppercase tracking-wider border border-indigo-200">
                            {{ $estudiantes->count() }} Estudiantes
                        </span>
                    </div>

                    @if($estudiantes->isEmpty())
                        <div class="p-10 text-center">
                            <p class="text-gray-500 text-sm">No se encontraron estudiantes inscritos en este grupo para la materia seleccionada.</p>
                        </div>
                    @else
                        <form action="{{ route('admin.examenes.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="materia_id" value="{{ $materiaSeleccionada->id }}">
                            <input type="hidden" name="grupo_id" value="{{ $grupoSeleccionado->codigo }}">

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-300 border-b border-gray-200">
                                    <thead class="bg-indigo-50 text-indigo-900">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider border-r border-indigo-100">CI</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider border-r border-indigo-100">Estudiante</th>
                                            @foreach($examenes as $examen)
                                            <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider border-r border-indigo-100">
                                                {{ $examen->descripcion }}<br>
                                                <span class="text-[10px] font-normal text-indigo-600">(100 pts)</span>
                                            </th>
                                            @endforeach
                                            <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wider">Promedio</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($estudiantes as $estudiante)
                                            @php
                                                $ci = $estudiante->ciusuario;
                                                $postulacion = $estudiante->postulaciones->where('codgrupo', $grupoSeleccionado->codigo)->first();
                                                $codpost = $postulacion ? $postulacion->codpost : '';
                                                
                                                $suma = 0;
                                                $cantidad = 0;
                                            @endphp
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900 font-mono border-r border-gray-100">
                                                    {{ $ci }}
                                                    <input type="hidden" name="notas[{{ $ci }}][codpost]" value="{{ $codpost }}">
                                                </td>
                                                <td class="px-6 py-3 whitespace-nowrap border-r border-gray-100">
                                                    <div class="text-sm font-bold text-gray-900 uppercase">
                                                        {{ $estudiante->usuario->apellidopat ?? '' }} {{ $estudiante->usuario->apellidomat ?? '' }} {{ $estudiante->usuario->nombre ?? '' }}
                                                    </div>
                                                </td>
                                                @foreach($examenes as $examen)
                                                    @php
                                                        $nro = $examen->nro;
                                                        $calif = isset($calificacionesMap[$ci][$nro]) ? $calificacionesMap[$ci][$nro]->calificacion : '';
                                                        if ($calif !== '') {
                                                            $suma += floatval($calif);
                                                            $cantidad++;
                                                        }
                                                    @endphp
                                                    <td class="px-4 py-3 whitespace-nowrap border-r border-gray-100 bg-gray-50/50">
                                                        <input type="number" step="0.01" min="0" max="100" name="notas[{{ $ci }}][nota{{ $nro }}]" value="{{ $calif }}" class="w-full text-center text-sm font-semibold text-gray-900 border-gray-300 focus:border-indigo-600 focus:ring-indigo-600 rounded-md px-2 py-1 shadow-sm">
                                                    </td>
                                                @endforeach
                                                @php
                                                    $promedio = $cantidad > 0 ? $suma / $examenes->count() : 0;
                                                @endphp
                                                <td class="px-6 py-3 whitespace-nowrap text-center bg-gray-50">
                                                    <span class="inline-flex items-center justify-center px-4 py-1.5 text-sm font-extrabold rounded-full shadow-sm {{ $promedio >= 51 ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300' }}">
                                                        {{ number_format($promedio, 2) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="px-6 py-5 bg-slate-50 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                                <div class="flex items-center text-sm text-red-600 font-semibold">
                                    <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    <span>Atención: Modificar estas notas como administrador es una acción directamente auditable.</span>
                                </div>
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 px-8 rounded-md shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600 transition-colors w-full sm:w-auto text-base">
                                    Guardar y Auditar Cambios
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            @else
                <div class="bg-white border-l-4 border-indigo-500 p-6 shadow-sm rounded-lg flex items-start">
                    <svg class="w-6 h-6 text-indigo-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm text-gray-600">Seleccione la materia y el grupo en el formulario superior para visualizar o editar las calificaciones. Recuerde que cualquier cambio anulará el registro ingresado por el docente y quedará registrado bajo su usuario en la bitácora del sistema.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
