<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight border-l-4 border-blue-800 pl-3">
            {{ __('Cronograma Académico Institucional') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white shadow-sm border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 bg-slate-800 flex items-center justify-between">
                    <h3 class="text-base font-bold text-white uppercase tracking-wide">
                        Fechas Oficiales de Evaluación Continua
                    </h3>
                    <span class="bg-slate-700 text-gray-100 text-xs font-medium px-3 py-1 border border-slate-600 shadow-sm">SEMESTRE VIGENTE</span>
                </div>
                
                <div class="p-6 border-b border-gray-200 bg-blue-50/50">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-0.5">
                            <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-bold text-gray-900 uppercase">Directriz Académica</h4>
                            <p class="text-sm text-gray-600 mt-1">
                                De acuerdo con el reglamento de la facultad, todo docente debe consignar las calificaciones de cada prueba parcial en el sistema <strong>dentro de un plazo máximo e improrrogable de 48 horas</strong> hábiles posteriores a la aplicación de la misma.
                            </p>
                        </div>
                    </div>
                </div>

                @if($gruposDocente->isEmpty())
                    <div class="p-10 text-center bg-white border-b border-gray-200">
                        <p class="text-gray-500 text-sm">No tiene materias/grupos asignados en este periodo.</p>
                    </div>
                @else
                    @foreach($gruposDocente as $grupo)
                    <div class="bg-slate-50 border-b border-gray-200 px-6 py-3">
                        <h4 class="text-md font-bold text-slate-800 uppercase">{{ $grupo->materia_nombre }} - Grupo {{ $grupo->codigogrupo }}</h4>
                    </div>
                    <div class="overflow-x-auto border-b border-gray-200 mb-4">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Etapa de Evaluación</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha Programada</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Modalidad / Ubicación</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($examenes as $examen)
                                    @php
                                        $fechaCampo = 'fecha_examen' . $examen->nro;
                                        $fecha = $grupo->$fechaCampo;
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8 border border-gray-300 {{ $examen->nro == 3 ? 'bg-slate-800 text-white' : 'bg-gray-100 text-gray-700' }} rounded-sm flex items-center justify-center font-bold">
                                                    {{ $examen->nro == 3 ? 'F' : $examen->nro }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-bold text-gray-900 uppercase">{{ $examen->descripcion }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($fecha)
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold bg-blue-50 text-blue-700 border border-blue-200 rounded-md shadow-sm">
                                                    {{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}
                                                </span>
                                            @else
                                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold bg-yellow-50 text-yellow-700 border border-yellow-200 rounded-md shadow-sm">
                                                    Por definir
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 uppercase">Presencial (Aulas designadas)</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endforeach
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
