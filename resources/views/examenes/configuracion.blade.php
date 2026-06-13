<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Gestionar Materias') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <div class="p-8 lg:p-12">
                    
                    <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Gestión de materias</h3>
                            <p class="text-gray-500">Habilita, inhabilita y crea materias para la gestión actual.</p>
                        </div>
                        <button type="button" id="toggle-edit" class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold shadow-sm hover:bg-indigo-700">
                            Editar materias
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

                    @if(session('error'))
                        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 flex items-start">
                            <svg class="h-5 w-5 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
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
                                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">ID</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Materia</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Estado</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($materias as $materia)
                                        @if($materia->estado === 'HABILITADO')
                                            <tr>
                                                <td class="px-6 py-4 text-sm text-gray-500">{{ $materia->id }}</td>
                                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $materia->nombre }}</td>
                                                <td class="px-6 py-4 text-sm">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">HABILITADO</span>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="edit-section" class="hidden mt-10 space-y-10">
                        <form method="POST" action="{{ route('examenes.puntos.update') }}" class="space-y-8">
                            @csrf
                            <input type="hidden" name="action" value="update_weights">

                            <div class="overflow-x-auto rounded-2xl border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Materia</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($materias as $materia)
                                            <tr>
                                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $materia->nombre }}</td>
                                                <td class="px-6 py-4">
                                                    <select name="estado[{{ $materia->id }}]" class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                                        <option value="HABILITADO" {{ $materia->estado === 'HABILITADO' ? 'selected' : '' }}>HABILITADO</option>
                                                        <option value="INHABILITADO" {{ $materia->estado === 'INHABILITADO' ? 'selected' : '' }}>INHABILITADO</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="flex items-center justify-between gap-4">
                                <div class="flex gap-3">
                                    <button type="button" id="cancel-edit" class="px-5 py-3 rounded-xl border border-gray-300 bg-white text-gray-700 font-semibold hover:bg-gray-50">Cancelar</button>
                                    <button type="submit" class="px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold shadow-sm hover:bg-indigo-700">Guardar cambios</button>
                                </div>
                            </div>
                        </form>

                        <div class="pt-8 border-t border-gray-200">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Crear Materia</h3>
                            <form method="POST" action="{{ route('examenes.puntos.update') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                                @csrf
                                <input type="hidden" name="action" value="create">
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
                                <div class="flex gap-3">
                                    <button type="submit" class="w-full px-5 py-3 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700">Crear Materia</button>
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
