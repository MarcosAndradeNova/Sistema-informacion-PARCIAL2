<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight border-l-4 border-blue-800 pl-3">
            {{ __('Actas de Calificaciones') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-600 text-green-800 p-4 shadow-sm" role="alert">
                    <p class="font-medium">{{ session('success') }}</p>
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
                                            <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider border-r border-slate-600">1er Parcial<br><span class="text-[10px] font-normal text-gray-300">(Sobre 100)</span></th>
                                            <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider border-r border-slate-600">2do Parcial<br><span class="text-[10px] font-normal text-gray-300">(Sobre 100)</span></th>
                                            <th class="px-4 py-3 text-center text-xs font-bold uppercase tracking-wider border-r border-slate-600">Ex. Final<br><span class="text-[10px] font-normal text-gray-300">(Sobre 100)</span></th>
                                            <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wider">Promedio<br><span class="text-[10px] font-normal text-gray-300">Final</span></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($estudiantes as $estudiante)
                                            @php
                                                $ci = $estudiante->ci_usuario;
                                                $calif = $calificacionesMap[$ci] ?? null;
                                                $n1 = $calif ? $calif->nota1 : '';
                                                $n2 = $calif ? $calif->nota2 : '';
                                                $n3 = $calif ? $calif->nota3 : '';
                                                $promedio = $calif ? $calif->promedio : 0;
                                            @endphp
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-900 font-mono border-r border-gray-100">
                                                    {{ $ci }}
                                                </td>
                                                <td class="px-6 py-3 whitespace-nowrap border-r border-gray-100">
                                                    <div class="text-sm font-medium text-gray-900 uppercase">
                                                        {{ $estudiante->usuario->apellido_pat ?? '' }} {{ $estudiante->usuario->apellido_mat ?? '' }} {{ $estudiante->usuario->nombre ?? '' }}
                                                    </div>
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap border-r border-gray-100 bg-gray-50/50">
                                                    <input type="number" step="0.01" min="0" max="100" name="notas[{{ $ci }}][nota1]" value="{{ $n1 }}" class="w-full text-center text-sm border-gray-300 focus:border-blue-800 focus:ring-blue-800 shadow-sm px-2 py-1">
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap border-r border-gray-100 bg-gray-50/50">
                                                    <input type="number" step="0.01" min="0" max="100" name="notas[{{ $ci }}][nota2]" value="{{ $n2 }}" class="w-full text-center text-sm border-gray-300 focus:border-blue-800 focus:ring-blue-800 shadow-sm px-2 py-1">
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap border-r border-gray-100 bg-gray-50/50">
                                                    <input type="number" step="0.01" min="0" max="100" name="notas[{{ $ci }}][nota3]" value="{{ $n3 }}" class="w-full text-center text-sm border-gray-300 focus:border-blue-800 focus:ring-blue-800 shadow-sm px-2 py-1">
                                                </td>
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
                                <p class="text-xs text-gray-500 italic">Declaro que las calificaciones introducidas en la presente acta son correctas y definitivas.</p>
                                <button type="submit" class="bg-blue-800 hover:bg-blue-900 text-white font-medium py-2 px-6 shadow-sm border border-transparent focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-800 transition-colors">
                                    Firmar y Guardar Acta
                                </button>
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
