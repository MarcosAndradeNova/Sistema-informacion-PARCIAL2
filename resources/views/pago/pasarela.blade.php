<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pasarela de Pago Segura</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-50">
    <div class="min-h-screen py-12 flex items-center justify-center">
        <div class="max-w-4xl w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-3xl border border-gray-100 flex flex-col lg:flex-row">
                
                <!-- Lado Izquierdo: Resumen -->
                <div class="w-full lg:w-5/12 bg-indigo-900 text-white p-8 lg:p-12 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center mb-8">
                            <span class="ml-3 text-xl font-bold tracking-widest text-indigo-100 uppercase">SISTEMA PREUNIVERSITARIO</span>
                        </div>
                        
                        <h3 class="text-2xl font-bold mb-6">Resumen de Pago</h3>
                        
                        <div class="space-y-4 text-indigo-200">
                            <div class="flex justify-between border-b border-indigo-800 pb-2">
                                <span>Postulante:</span>
                                <span class="font-bold text-white">{{ $usuario->nombre }} {{ $usuario->apellidopat }}</span>
                            </div>
                            <div class="flex justify-between border-b border-indigo-800 pb-2">
                                <span>CI:</span>
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
                        <span class="text-4xl font-extrabold text-white">Bs. 350.00</span>
                    </div>
                </div>

                <!-- Lado Derecho: Formulario Tarjeta -->
                <div class="w-full lg:w-7/12 p-8 lg:p-12">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        Pago con Tarjeta
                    </h3>
                    
                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded relative" role="alert">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pago.procesar', ['ci' => $usuario->ci]) }}" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Número de Tarjeta</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="text" name="numero_tarjeta" required class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md py-3 px-4" placeholder="0000 0000 0000 0000" maxlength="16">
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Vencimiento</label>
                                <input type="text" name="fecha_expiracion" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md py-3 px-4" placeholder="MM/AA" maxlength="5">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">CVC</label>
                                <input type="text" name="cvv" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md py-3 px-4" placeholder="123" maxlength="4">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nombre en la tarjeta</label>
                            <input type="text" name="nombre_titular" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md py-3 px-4" placeholder="JUAN PEREZ">
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg text-lg font-extrabold text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all transform hover:-translate-y-1">
                                Confirmar y Pagar
                            </button>
                        </div>
                        <p class="mt-4 text-xs text-center text-gray-400 flex items-center justify-center">
                            <svg class="h-4 w-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Pago 100% encriptado y procesado de forma segura
                        </p>
                    </form>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
