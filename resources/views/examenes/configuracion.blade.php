<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Configuración de Materias y Puntos') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <div class="p-8 lg:p-12">
                    
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Asignación de Puntos</h3>
                        <p class="text-gray-500">Configura cuántos puntos valdrá cada materia en el examen de admisión. La suma de todas las materias debe ser exactamente 100.</p>
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

                    <form method="POST" action="{{ route('examenes.puntos.update') }}" id="puntos-form">
                        @csrf
                        
                        <div class="space-y-6">
                            @foreach($materias as $materia)
                                <div class="flex items-center justify-between p-5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 transition-colors duration-200 group">
                                    <div class="flex items-center space-x-4">
                                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-200">
                                            <span class="text-indigo-600 font-bold text-lg">{{ substr($materia->nombre, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <label for="materia_{{ $materia->id }}" class="block text-sm font-semibold text-gray-900">{{ $materia->nombre }}</label>
                                            <p class="text-xs text-gray-500">Puntos máximos permitidos en el examen</p>
                                        </div>
                                    </div>
                                    
                                    <div class="relative flex items-center w-32">
                                        <input type="number" 
                                               id="materia_{{ $materia->id }}" 
                                               name="puntos[{{ $materia->id }}]" 
                                               value="{{ old('puntos.'.$materia->id, $materia->puntos) }}"
                                               min="0" max="100"
                                               class="puntos-input block w-full rounded-lg border-gray-300 pr-12 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm font-medium text-gray-900 shadow-sm"
                                               required>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">pts</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row items-center justify-between">
                            <div class="flex items-center mb-4 sm:mb-0 bg-gray-100 px-4 py-3 rounded-lg w-full sm:w-auto justify-between sm:justify-start space-x-4">
                                <span class="text-sm font-medium text-gray-600">Total asignado:</span>
                                <div class="flex items-baseline space-x-1">
                                    <span id="total-puntos" class="text-2xl font-black text-indigo-600">0</span>
                                    <span class="text-sm font-medium text-gray-500">/ 100</span>
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                </svg>
                                Guardar Configuración
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Script para sumar en tiempo real -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.puntos-input');
            const totalSpan = document.getElementById('total-puntos');
            
            function updateTotal() {
                let sum = 0;
                inputs.forEach(input => {
                    const val = parseInt(input.value) || 0;
                    sum += val;
                });
                
                totalSpan.textContent = sum;
                
                if (sum === 100) {
                    totalSpan.classList.remove('text-red-500', 'text-indigo-600');
                    totalSpan.classList.add('text-green-500');
                } else if (sum > 100) {
                    totalSpan.classList.remove('text-green-500', 'text-indigo-600');
                    totalSpan.classList.add('text-red-500');
                } else {
                    totalSpan.classList.remove('text-green-500', 'text-red-500');
                    totalSpan.classList.add('text-indigo-600');
                }
            }
            
            inputs.forEach(input => {
                input.addEventListener('input', updateTotal);
            });
            
            // Inicializar al cargar
            updateTotal();
        });
    </script>
</x-app-layout>
