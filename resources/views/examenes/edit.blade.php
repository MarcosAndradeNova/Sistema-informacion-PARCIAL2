<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Calificaciones de: ') }} {{ $postulante->usuario->nombre }} {{ $postulante->usuario->apellido_pat }}
            </h2>
            <a href="{{ route('examenes.index') }}" class="text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Volver a la lista</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="mb-6 bg-blue-50 dark:bg-blue-900 border-l-4 border-blue-500 p-4">
                        <p class="font-bold">Reglas de Calificación:</p>
                        <ul class="list-disc ml-5 text-sm">
                            <li>Cada materia tiene 3 exámenes (Nota 1, Nota 2, Nota 3).</li>
                            <li>Las notas deben estar entre 0 y 100.</li>
                            <li>El sistema calculará automáticamente el <strong>Promedio = (N1 + N2 + N3) / 3</strong>.</li>
                            <li>Estado: <strong>APROBADO</strong> (Promedio >= 60) o <strong>REPROBADO</strong> (Promedio < 60).</li>
                        </ul>
                    </div>

                    <form method="POST" action="{{ route('examenes.update', $postulante->ci_usuario) }}">
                        @csrf
                        @method('PUT')

                        <div class="overflow-x-auto mb-6">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-300">
                                    <tr>
                                        <th class="px-4 py-3">Materia</th>
                                        <th class="px-4 py-3 w-24">Nota 1</th>
                                        <th class="px-4 py-3 w-24">Nota 2</th>
                                        <th class="px-4 py-3 w-24">Nota 3</th>
                                        <th class="px-4 py-3 bg-gray-300 dark:bg-gray-600">Promedio</th>
                                        <th class="px-4 py-3 text-center">Estado Final</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($calificaciones as $calificacion)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-4 py-4 font-bold text-gray-900 dark:text-white">
                                            {{ $calificacion->materia }}
                                        </td>
                                        <td class="px-4 py-4">
                                            <input type="number" min="0" max="100" name="notas[{{ $calificacion->id }}][nota1]" value="{{ $calificacion->nota1 }}" class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-center focus:ring-indigo-500 p-1">
                                        </td>
                                        <td class="px-4 py-4">
                                            <input type="number" min="0" max="100" name="notas[{{ $calificacion->id }}][nota2]" value="{{ $calificacion->nota2 }}" class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-center focus:ring-indigo-500 p-1">
                                        </td>
                                        <td class="px-4 py-4">
                                            <input type="number" min="0" max="100" name="notas[{{ $calificacion->id }}][nota3]" value="{{ $calificacion->nota3 }}" class="w-full rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-center focus:ring-indigo-500 p-1">
                                        </td>
                                        <td class="px-4 py-4 bg-gray-100 dark:bg-gray-700 font-bold text-center text-lg text-indigo-600 dark:text-indigo-400">
                                            {{ $calificacion->promedio }}
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            @if($calificacion->estado == 'APROBADO')
                                                <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded dark:bg-green-900 dark:text-green-300 uppercase">
                                                    APROBADO
                                                </span>
                                            @else
                                                <span class="bg-red-100 text-red-800 text-xs font-bold px-3 py-1 rounded dark:bg-red-900 dark:text-red-300 uppercase">
                                                    REPROBADO
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="bg-blue-600 hover:bg-blue-700 px-6 py-3 text-sm">
                                Guardar Notas y Calcular Promedios
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
