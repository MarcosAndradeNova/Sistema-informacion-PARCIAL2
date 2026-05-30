<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center">
            <svg class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            {{ __('Pasarela de Pago Segura') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl border border-gray-100">
                <div class="flex flex-col lg:flex-row">
                    
                    <!-- Lado Izquierdo: Resumen -->
                    <div class="w-full lg:w-5/12 bg-indigo-900 text-white p-8 lg:p-12 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center mb-8">
                                <x-application-logo class="block h-10 w-auto fill-current text-white opacity-90" />
                                <span class="ml-3 text-xl font-bold tracking-widest text-indigo-100 uppercase">CUP FICCT</span>
                            </div>
                            
                            <h3 class="text-2xl font-bold mb-6">Resumen de Matrícula</h3>
                            
                            <div class="space-y-4 text-indigo-200">
                                <div class="flex justify-between border-b border-indigo-800 pb-2">
                                    <span>Postulante:</span>
                                    <span class="font-bold text-white">{{ $usuario->nombre }} {{ $usuario->apellido_pat }}</span>
                                </div>
                                <div class="flex justify-between border-b border-indigo-800 pb-2">
                                    <span>Carnet:</span>
                                    <span class="font-bold text-white">{{ $usuario->ci }}</span>
                                </div>
                                <div class="flex justify-between border-b border-indigo-800 pb-2">
                                    <span>Concepto:</span>
                                    <span class="font-bold text-white text-right">Inscripción Curso<br>Pre-Universitario</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-12 pt-6 border-t border-indigo-700 flex justify-between items-end">
                            <span class="text-indigo-200 text-lg">Total a Pagar</span>
                            <span class="text-4xl font-extrabold text-white">Bs. 300</span>
                        </div>
                    </div>

                    <!-- Lado Derecho: Opciones de Pago -->
                    <div class="w-full lg:w-7/12 p-8 lg:p-12" x-data="{ metodo: 'QR' }">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Selecciona el método de pago</h3>
                        
                        <!-- Tabs -->
                        <div class="flex space-x-4 mb-8">
                            <button @click="metodo = 'QR'" 
                                    :class="metodo === 'QR' ? 'bg-indigo-50 border-indigo-600 text-indigo-700 shadow-sm' : 'border-gray-200 text-gray-500 hover:bg-gray-50'"
                                    class="flex-1 py-3 px-4 border-2 rounded-xl font-bold flex flex-col items-center justify-center transition-all">
                                <svg class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                                Pago Simple QR
                            </button>
                            <button @click="metodo = 'TARJETA'" 
                                    :class="metodo === 'TARJETA' ? 'bg-indigo-50 border-indigo-600 text-indigo-700 shadow-sm' : 'border-gray-200 text-gray-500 hover:bg-gray-50'"
                                    class="flex-1 py-3 px-4 border-2 rounded-xl font-bold flex flex-col items-center justify-center transition-all">
                                <svg class="h-6 w-6 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                                Tarjeta
                            </button>
                        </div>

                        <!-- Formulario y Contenido Dinámico -->
                        <form method="POST" action="{{ route('pago.store') }}">
                            @csrf
                            <input type="hidden" name="metodo_pago" :value="metodo">

                            <!-- Vista QR -->
                            <div x-show="metodo === 'QR'" x-transition class="text-center pb-6">
                                <div class="bg-gray-50 border border-gray-200 p-6 rounded-2xl inline-block shadow-inner mb-4">
                                    <img src="{{ asset('images/qr_code.png') }}" alt="Código QR de Pago" class="w-48 h-48 object-cover rounded-lg">
                                </div>
                                <p class="text-sm text-gray-500 mb-2">Escanea el código con la app de tu banco.</p>
                                <p class="text-xs text-indigo-600 font-semibold bg-indigo-50 inline-block px-3 py-1 rounded-full">Vence en 15:00 minutos</p>
                            </div>

                            <!-- Vista Tarjeta -->
                            <div x-show="metodo === 'TARJETA'" x-transition class="space-y-4 pb-6" style="display: none;">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Número de Tarjeta</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                            </svg>
                                        </div>
                                        <input type="text" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="0000 0000 0000 0000">
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Vencimiento</label>
                                        <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="MM/AA">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">CVC</label>
                                        <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="123">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nombre en la tarjeta</label>
                                    <input type="text" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="JUAN PEREZ">
                                </div>
                            </div>

                            <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg text-lg font-extrabold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all transform hover:-translate-y-1">
                                Confirmar Pago Realizado
                            </button>
                            <p class="mt-4 text-xs text-center text-gray-400 flex items-center justify-center">
                                <svg class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Pagos encriptados y procesados de forma segura
                            </p>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
