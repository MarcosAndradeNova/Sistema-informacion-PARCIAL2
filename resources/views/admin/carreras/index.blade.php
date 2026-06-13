<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Gestionar Carreras') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <div class="p-8 lg:p-12">
                    
                    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Resumen de carreras habilitadas</h3>
                            <p class="text-gray-500">Visualiza las carreras habilitadas con su cupo y gestión actual.</p>
                        </div>
                        <button type="button" id="toggle-edit" class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold shadow-sm hover:bg-indigo-700">
                            Editar carrera
                        </button>
                    </div>

                    @if(session('success'))
                        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 flex items-start">
                            <svg class="h-5 w-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200">
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <h3 class="text-sm font-medium text-red-800">Hay errores en el formulario:</h3>
                                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div id="summary-section" class="space-y-4">
                        <div class="overflow-x-auto rounded-2xl border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Código</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Carrera</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Gestión Actual</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Cupo</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($carreras as $carrera)
                                        @if(($carrera->estado ?? 'HABILITADO') === 'HABILITADO')
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">{{ $carrera->codigo }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $carrera->nombre }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $carrera->semestre ?? '1/2026' }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-700">{{ $carrera->cupo ?? 0 }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="edit-section" class="hidden mt-10 space-y-10">
                        <form method="POST" action="{{ route('admin.carreras.update') }}" class="space-y-8">
                            @csrf
                            <input type="hidden" name="action" value="update">

                            <div class="overflow-x-auto rounded-xl border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Código</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Carrera</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Gestión Actual</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Cupo</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($carreras as $carrera)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">{{ $carrera->codigo }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $carrera->nombre }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <select name="carreras[{{ $carrera->codigo }}][estado]" class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm shadow-sm">
                                                        <option value="HABILITADO" {{ old('carreras.'.$carrera->codigo.'.estado', $carrera->estado ?? 'HABILITADO') === 'HABILITADO' ? 'selected' : '' }}>HABILITADO</option>
                                                        <option value="INHABILITADO" {{ old('carreras.'.$carrera->codigo.'.estado', $carrera->estado ?? 'HABILITADO') === 'INHABILITADO' ? 'selected' : '' }}>INHABILITADO</option>
                                                    </select>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input type="text"
                                                        name="carreras[{{ $carrera->codigo }}][semestre]"
                                                        value="{{ old('carreras.'.$carrera->codigo.'.semestre', $carrera->semestre ?? '1/2026') }}"
                                                        class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm shadow-sm"
                                                        required>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <input type="number"
                                                        name="carreras[{{ $carrera->codigo }}][cupo]"
                                                        value="{{ old('carreras.'.$carrera->codigo.'.cupo', $carrera->cupo ?? 0) }}"
                                                        min="0"
                                                        class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm shadow-sm font-medium"
                                                        required>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="flex items-center justify-end gap-3">
                                <button type="button" id="cancel-edit" class="px-5 py-3 rounded-xl border border-gray-300 bg-white text-gray-700 font-semibold hover:bg-gray-50">Cancelar</button>
                                <button type="submit" class="px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold shadow-sm hover:bg-indigo-700">Guardar cambios</button>
                            </div>
                        </form>

                        <div class="pt-8 border-t border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Crear nueva carrera</h3>
                            <form method="POST" action="{{ route('admin.carreras.update') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                @csrf
                                <input type="hidden" name="action" value="create">

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Código</label>
                                    <input type="text" name="codigo" value="{{ old('codigo') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                                    <input type="text" name="nombre" value="{{ old('nombre') }}" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                                    <select name="estado" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                                        <option value="HABILITADO">HABILITADO</option>
                                        <option value="INHABILITADO">INHABILITADO</option>
                                    </select>
                                </div>

                                <div>
                                    <button type="submit" class="w-full px-5 py-3 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700">Crear carrera</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const summarySection = document.getElementById('summary-section');
            const editSection = document.getElementById('edit-section');
            const toggleEdit = document.getElementById('toggle-edit');
            const cancelEdit = document.getElementById('cancel-edit');

            const openEdit = () => {
                summarySection.classList.add('hidden');
                editSection.classList.remove('hidden');
            };

            const closeEdit = () => {
                editSection.classList.add('hidden');
                summarySection.classList.remove('hidden');
            };

            toggleEdit?.addEventListener('click', openEdit);
            cancelEdit?.addEventListener('click', closeEdit);
        });
    </script>
</x-app-layout>
