<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight border-l-4 border-indigo-600 pl-3">
            {{ __('Gestionar Examen') }}
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
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-6 rounded-md shadow-sm border border-transparent focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 transition-colors">
                        Configurar Fechas
                    </button>
                </form>
            </div>

            @if($materiaSeleccionada && $grupoSeleccionado)
                <!-- Gestión de Exámenes (Especifico por Grupo y Materia) -->
                <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-6 mb-6">
                    <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4">Configuración de Exámenes para {{ $materiaSeleccionada->nombre }} - Grupo {{ $grupoSeleccionado->codigo }}</h3>
                    <form action="{{ route('admin.examenes.update_examenes') }}" method="POST">
                        @csrf
                        <input type="hidden" name="materia_id" value="{{ $materiaSeleccionada->id }}">
                        <input type="hidden" name="grupo_id" value="{{ $grupoSeleccionado->codigo }}">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($examenes as $examen)
                            <div class="border border-gray-200 rounded p-4 bg-gray-50">
                                <label class="block text-sm font-semibold text-slate-700 mb-2">Examen {{ $examen->nro }}</label>
                                <input type="text" name="examenes[{{ $examen->nro }}][descripcion]" value="{{ $examen->descripcion }}" class="mb-3 focus:ring-indigo-600 focus:border-indigo-600 block w-full text-sm border-gray-300 rounded-md p-2" placeholder="Descripción (ej. Primer Parcial)">
                                <label class="block text-xs font-semibold text-slate-500 mb-1">Fecha Programada</label>
                                @php
                                    $fechaActual = $grupoDocenteInfo ? $grupoDocenteInfo->{'fecha_examen'.$examen->nro} : null;
                                    $fechaValor = $fechaActual ? \Carbon\Carbon::parse($fechaActual)->format('Y-m-d') : '';
                                @endphp
                                <input type="date" name="examenes[{{ $examen->nro }}][fecha]" value="{{ $fechaValor }}" class="focus:ring-indigo-600 focus:border-indigo-600 block w-full text-sm border-gray-300 rounded-md p-2" required>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-6 rounded-md shadow-sm border border-transparent focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 transition-colors">
                                Guardar Fechas
                            </button>
                        </div>
                    </form>
                </div>

            @else
                <div class="bg-white border-l-4 border-indigo-500 p-6 shadow-sm rounded-lg flex items-start">
                    <svg class="w-6 h-6 text-indigo-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm text-gray-600">Seleccione la materia y el grupo en el formulario superior para visualizar o editar la configuración de fechas de exámenes. La modificación de notas corresponde únicamente a los docentes asignados.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
