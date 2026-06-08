<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center">
                <svg class="w-8 h-8 mr-3 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                {{ __('Rendimiento Académico') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-10">
            
            <!-- Cronograma Referencial -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Cronograma Oficial de Evaluaciones
                    </h3>
                    <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full border border-white/30">Semestre Actual</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-8 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Fase de Evaluación</th>
                                <th scope="col" class="px-8 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Fecha Programada</th>
                                <th scope="col" class="px-8 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Modalidad</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold">1</div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900">Primer Examen Parcial</div>
                                            <div class="text-xs text-gray-500">Evaluación de la primera mitad del temario</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Semana 2 de clases
                                    </span>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-sm font-medium text-gray-600">Presencial (Laboratorios)</td>
                            </tr>
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center font-bold">2</div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900">Segundo Examen Parcial</div>
                                            <div class="text-xs text-gray-500">Evaluación de la segunda mitad del temario</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Semana 4 de clases
                                    </span>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-sm font-medium text-gray-600">Presencial (Laboratorios)</td>
                            </tr>
                            <tr class="hover:bg-blue-50/50 transition-colors">
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center font-bold">F</div>
                                        <div class="ml-4">
                                            <div class="text-sm font-bold text-gray-900">Examen Final de Admisión</div>
                                            <div class="text-xs text-gray-500">Evaluación global de conocimientos</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        Semana 6 de clases
                                    </span>
                                </td>
                                <td class="px-8 py-5 whitespace-nowrap text-sm font-medium text-gray-600">Presencial en Facultades</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Calificaciones -->
            <div>
                <h3 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    Boleta de Calificaciones
                </h3>
                
                @if($calificaciones->isEmpty())
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center flex flex-col items-center">
                        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6 border-4 border-white shadow-inner">
                            <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-gray-800 mb-2">Evaluaciones en Progreso</h4>
                        <p class="text-gray-500 max-w-md mx-auto">Tu libreta de calificaciones aparecerá aquí en cuanto los docentes comiencen a subir las notas oficiales al sistema.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        @foreach($calificaciones as $index => $calificacion)
                            @php
                                $colorMateria = $index % 2 == 0 ? 'blue' : 'indigo';
                                $aprobado = $calificacion->promedio >= 51;
                            @endphp
                            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition relative overflow-hidden">
                                <!-- Línea decorativa superior -->
                                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-{{$colorMateria}}-500 to-{{$colorMateria}}-700"></div>
                                
                                <div class="flex justify-between items-start mb-6 mt-2">
                                    <div>
                                        <h4 class="font-black text-gray-800 text-xl uppercase tracking-tight">{{ $calificacion->materia }}</h4>
                                        <p class="text-sm text-gray-500 font-medium">Calificaciones Oficiales</p>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $aprobado ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-rose-100 text-rose-800 border border-rose-200' }}">
                                        {{ $calificacion->estado ?? ($aprobado ? 'Aprobado' : 'Reprobado') }}
                                    </span>
                                </div>
                                
                                <div class="grid grid-cols-3 gap-4 mb-6">
                                    <div class="bg-gray-50 p-4 rounded-xl text-center border border-gray-100">
                                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-2">Parcial 1</p>
                                        <p class="font-black text-2xl {{ $calificacion->nota1 >= 51 ? 'text-gray-800' : 'text-rose-600' }}">{{ $calificacion->nota1 ?? '-' }}</p>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-xl text-center border border-gray-100">
                                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-2">Parcial 2</p>
                                        <p class="font-black text-2xl {{ $calificacion->nota2 >= 51 ? 'text-gray-800' : 'text-rose-600' }}">{{ $calificacion->nota2 ?? '-' }}</p>
                                    </div>
                                    <div class="bg-gray-50 p-4 rounded-xl text-center border border-gray-100">
                                        <p class="text-xs text-gray-500 font-bold uppercase tracking-wider mb-2">Examen Final</p>
                                        <p class="font-black text-2xl {{ $calificacion->nota3 >= 51 ? 'text-gray-800' : 'text-rose-600' }}">{{ $calificacion->nota3 ?? '-' }}</p>
                                    </div>
                                </div>
                                
                                <div class="bg-{{$colorMateria}}-50 rounded-xl p-4 flex items-center justify-between border border-{{$colorMateria}}-100">
                                    <span class="text-{{$colorMateria}}-800 font-bold uppercase tracking-wider text-sm">Promedio Ponderado</span>
                                    <span class="text-3xl font-black {{ $aprobado ? 'text-emerald-600' : 'text-rose-600' }}">{{ $calificacion->promedio ?? '0.00' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
