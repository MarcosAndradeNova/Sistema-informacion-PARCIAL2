<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight border-l-4 border-blue-800 pl-3">
            {{ __('Registrar Notas de Examen') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-600 text-green-800 p-4 shadow-sm" role="alert">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if(isset($configAbierto) && $configAbierto === 'abierto')
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 shadow-sm mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700 font-medium">
                                El periodo de registro de notas se encuentra <strong>HABILITADO</strong>. 
                                @if(isset($diasRestantes) && $diasRestantes >= 0)
                                    Tiempo restante sugerido: {{ $diasRestantes }} días.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-red-50 border-l-4 border-red-500 p-4 shadow-sm mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 font-medium">
                                El sistema de registro de notas está actualmente <strong>CERRADO</strong>. Aún no está habilitada la fecha para subir notas.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Selección de Materia -->
            <div class="bg-white shadow-sm border border-gray-200 p-6">
                <form action="{{ route('docente.calificaciones') }}" method="GET" class="flex flex-col sm:flex-row sm:items-end space-y-4 sm:space-y-0 sm:space-x-4">
                    <div class="flex-1 max-w-sm">
                        <label class="block text-sm font-semibold text-slate-700 mb-2 uppercase tracking-wide">Asignatura a evaluar</label>
                        <select name="materia_id" class="focus:ring-blue-800 focus:border-blue-800 block w-full sm:text-sm border-gray-300 bg-gray-50">
                            <option value="">Seleccione un grupo/asignatura...</option>
                            @foreach($materias as $materia)
                                <option value="{{ $materia->id }}" {{ ($materiaSeleccionada && $materiaSeleccionada->id == $materia->id) ? 'selected' : '' }}>
                                    {{ $materia->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-medium py-2 px-6 shadow-sm border border-transparent focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-800 transition-colors">
                        Generar Planilla
                    </button>
                </form>
            </div>

            @if($materiaSeleccionada)
                <div class="bg-white shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 bg-slate-100 flex justify-between items-center">
                        <h3 class="text-base font-bold text-gray-800 uppercase tracking-wide">Planilla de Notas Oficial: {{ $materiaSeleccionada->nombre }}</h3>
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Registros: {{ $estudiantes->count() }}</span>
                    </div>

                    @if($estudiantes->isEmpty())
                        <div class="p-10 text-center">
                            <p class="text-gray-500 text-sm">No existen expedientes habilitados para esta asignatura en el periodo vigente.</p>
                        </div>
                    @else
                        <form action="{{ route('docente.calificaciones.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="materia_id" value="{{ $materiaSeleccionada->id }}">

                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-300 border-b border-gray-200">
                                    <thead class="bg-slate-800 text-white">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider border-r border-slate-600">N° Doc (CI)</th>
                                            <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider border-r border-slate-600">Apellidos y Nombres</th>
                                            @foreach($examenes as $examen)
                                            <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider border-r border-slate-600">{{ $examen->descripcion }}<br><span class="text-[10px] font-normal text-gray-300">(Sobre 100)</span></th>
                                            @endforeach
                                            <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wider">Promedio<br><span class="text-[10px] font-normal text-gray-300">Final</span></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($estudiantes as $estudiante)
                                            @php
                                                $ci = $estudiante->ciusuario;
                                                $postulacion = $estudiante->postulaciones->whereIn('codgrupo', $codigosGrupos)->first();
                                                $codpost = $postulacion ? $postulacion->codpost : '';
                                                $codigogrupo = $postulacion ? $postulacion->codgrupo : '';

                                                $suma = 0;
                                                $cantidad = 0;
                                            @endphp
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900 font-mono border-r border-gray-100">
                                                    {{ $ci }}
                                                    <input type="hidden" name="notas[{{ $ci }}][codpost]" value="{{ $codpost }}">
                                                    <input type="hidden" name="notas[{{ $ci }}][codigogrupo]" value="{{ $codigogrupo }}">
                                                </td>
                                                <td class="px-6 py-3 whitespace-nowrap border-r border-gray-100">
                                                    <div class="text-sm font-medium text-gray-900 uppercase">
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
                                                        @if(isset($configAbierto) && $configAbierto === 'abierto')
                                                            <input type="number" step="0.01" min="0" max="100" name="notas[{{ $ci }}][nota{{ $nro }}]" value="{{ $calif }}" class="w-full text-center text-sm border-gray-300 focus:border-blue-800 focus:ring-blue-800 shadow-sm px-2 py-1">
                                                        @else
                                                            <span class="w-full inline-block text-center text-sm px-2 py-1 text-gray-500 bg-gray-100 border border-transparent rounded cursor-not-allowed">{{ $calif !== '' ? $calif : '-' }}</span>
                                                        @endif
                                                    </td>
                                                @endforeach
                                                @php
                                                    $promedio = $cantidad > 0 ? $suma / $examenes->count() : 0;
                                                @endphp
                                                <td class="px-6 py-3 whitespace-nowrap text-center bg-gray-50">
                                                    <span class="inline-flex items-center justify-center px-2 py-1 text-sm font-bold border {{ $promedio >= 51 ? 'border-green-200 bg-green-50 text-green-700' : 'border-red-200 bg-red-50 text-red-700' }}">
                                                        {{ number_format($promedio, 2) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="px-6 py-5 bg-slate-50 border-t border-gray-200 flex justify-between items-center">
                                @if(isset($configAbierto) && $configAbierto === 'abierto')
                                    <p class="text-xs text-gray-500 italic">Declaro que las calificaciones introducidas en la presente acta son correctas y definitivas.</p>
                                    <button type="submit" class="bg-blue-800 hover:bg-blue-900 text-white font-medium py-2 px-6 shadow-sm border border-transparent focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-800 transition-colors">
                                        Firmar y Guardar Acta
                                    </button>
                                @else
                                    <p class="text-xs text-red-500 font-medium italic">El registro de notas se encuentra cerrado. Comuníquese con administración si requiere habilitación.</p>
                                    <button type="button" disabled class="bg-gray-400 text-white font-medium py-2 px-6 shadow-sm border border-transparent cursor-not-allowed transition-colors">
                                        Guardar Deshabilitado
                                    </button>
                                @endif
                            </div>
                        </form>
                    @endif
                </div>
            @else
                @if($materias->isNotEmpty())
                <div class="bg-white border-l-4 border-gray-400 p-6 shadow-sm">
                    <p class="text-sm text-gray-600">Seleccione la asignatura en el selector superior para desplegar la planilla de actas oficial. Recuerde que el ingreso de notas es un proceso auditable.</p>
                </div>
                @endif
            @endif

        </div>
    </div>
</x-app-layout>
