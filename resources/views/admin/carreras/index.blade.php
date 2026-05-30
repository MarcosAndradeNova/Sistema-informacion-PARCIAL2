<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Gestión de Cupos por Carrera') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <div class="p-8 lg:p-12">
                    
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Configurar Cupos</h3>
                        <p class="text-gray-500">Define la cantidad máxima de estudiantes que podrán ser admitidos por cada carrera y el semestre de postulación.</p>
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

                    <form method="POST" action="{{ route('admin.carreras.update') }}">
                        @csrf
                        
                        <div class="overflow-x-auto rounded-xl border border-gray-200 mb-6">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Código</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Carrera</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Semestre</th>
                                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Cupos Disponibles</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($carreras as $carrera)
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-medium">
                                                {{ $carrera->codigo }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                                {{ $carrera->nombre }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="text" 
                                                       name="carreras[{{ $carrera->codigo }}][semestre]" 
                                                       value="{{ old('carreras.'.$carrera->codigo.'.semestre', $carrera->semestre ?? '1-2026') }}"
                                                       class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm shadow-sm"
                                                       required>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <input type="number" 
                                                       name="carreras[{{ $carrera->codigo }}][cupo]" 
                                                       value="{{ old('carreras.'.$carrera->codigo.'.cupo', $carrera->cupo ?? 150) }}"
                                                       min="0"
                                                       class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm shadow-sm font-medium"
                                                       required>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
