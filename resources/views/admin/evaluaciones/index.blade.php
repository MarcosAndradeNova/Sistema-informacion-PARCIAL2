<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight border-l-4 border-indigo-600 pl-3">
            {{ __('Consulta de Evaluaciones') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Selección de Materia y Grupo -->
            <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6">
                <form action="{{ route('admin.evaluaciones.index') }}" method="GET" class="flex flex-col md:flex-row md:items-end gap-6">
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
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-md shadow-sm border border-transparent focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 transition-colors">
                        Consultar Notas
                    </button>
                </form>
            </div>

            @if($materiaSeleccionada && $grupoSeleccionado)
                <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6">
                    <div class="mb-4">
                        <h3 class="text-lg leading-6 font-bold text-gray-900">Resultados Generales</h3>
                        <p class="text-sm text-gray-500">Materia: {{ $materiaSeleccionada->nombre }} | Grupo: {{ $grupoSeleccionado->codigo }}</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-indigo-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-indigo-900 uppercase tracking-wider">Estudiante</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-indigo-900 uppercase tracking-wider">CI</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-indigo-900 uppercase tracking-wider">Nota 1</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-indigo-900 uppercase tracking-wider">Nota 2</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-indigo-900 uppercase tracking-wider">Nota 3</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-indigo-900 uppercase tracking-wider">Nota 4</th>
                                    <th class="px-6 py-3 text-center text-xs font-bold text-indigo-900 uppercase tracking-wider">Nota 5</th>
                                    <th class="px-6 py-3 text-center text-xs font-extrabold text-indigo-900 uppercase tracking-wider">Nota Final</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($estudiantes as $estudiante)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $estudiante->apellidopat }} {{ $estudiante->apellidomat }} {{ $estudiante->nombre }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            {{ $estudiante->ci }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $estudiante->nota1 ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $estudiante->nota2 ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $estudiante->nota3 ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $estudiante->nota4 ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">{{ $estudiante->nota5 ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold {{ ($estudiante->notafinal ?? 0) >= 51 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $estudiante->notafinal ?? '0.00' }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">No hay estudiantes inscritos en este grupo y materia.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="bg-white border-l-4 border-indigo-500 p-6 shadow-sm rounded-lg flex items-start">
                    <svg class="w-6 h-6 text-indigo-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm text-gray-600">Seleccione la materia y el grupo en el formulario superior para visualizar las calificaciones y promedios.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
