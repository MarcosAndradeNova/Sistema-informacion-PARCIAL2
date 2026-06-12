<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl">
                
                <div class="bg-gradient-to-r from-blue-800 to-indigo-900 p-8 text-center">
                    <h2 class="text-3xl font-extrabold text-white tracking-tight">
                        Estado de tu Admisión
                    </h2>
                    <p class="mt-2 text-blue-200 text-lg">
                        Sigue el progreso de tu postulación paso a paso
                    </p>
                </div>

                <div class="p-8">
                    @if (session('success'))
                        <div class="mb-8 p-4 rounded-xl bg-green-50 border-l-4 border-green-500 flex items-center">
                            <svg class="h-6 w-6 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <p class="text-green-700 font-medium">{{ session('success') }}</p>
                        </div>
                    @endif

                    <div class="relative mb-12">
                        <!-- Progress Bar Background -->
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t-4 border-gray-200"></div>
                        </div>
                        
                        <!-- Progress Steps -->
                        <div class="relative flex justify-between">
                            
                            <!-- Step 1: Registrado -->
                            <div class="flex flex-col items-center">
                                <div class="h-12 w-12 rounded-full bg-indigo-600 border-4 border-white flex items-center justify-center text-white shadow-md">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <span class="mt-3 text-sm font-semibold text-indigo-600">Registrado</span>
                            </div>

                            <!-- Step 2: Documentos -->
                            <div class="flex flex-col items-center">
                                @php
                                    $isDocsDone = in_array($postulante->estadodocum, ['VERIFICADO', 'APROBADO', 'INSCRITO', 'INSCRITO']);
                                    $isDocsCurrent = in_array($postulante->estadodocum, ['PENDIENTE', 'RECHAZADO']);
                                @endphp
                                <div class="h-12 w-12 rounded-full {{ $isDocsDone ? 'bg-indigo-600 text-white border-white' : ($isDocsCurrent ? 'bg-white border-4 border-indigo-600 text-indigo-600' : 'bg-white border-4 border-gray-300 text-gray-400') }} flex items-center justify-center shadow-md">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <span class="mt-3 text-sm font-semibold {{ $isDocsDone || $isDocsCurrent ? 'text-indigo-600' : 'text-gray-500' }}">Documentos</span>
                            </div>

                            <!-- Step 3: Pago -->
                            <div class="flex flex-col items-center">
                                @php
                                    $isPagoDone = in_array($postulante->estadodocum, ['INSCRITO', 'INSCRITO']);
                                    $isPagoCurrent = $postulante->estadodocum == 'APROBADO';
                                @endphp
                                <div class="h-12 w-12 rounded-full {{ $isPagoDone ? 'bg-indigo-600 text-white border-white' : ($isPagoCurrent ? 'bg-white border-4 border-indigo-600 text-indigo-600' : 'bg-white border-4 border-gray-300 text-gray-400') }} flex items-center justify-center shadow-md">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                                <span class="mt-3 text-sm font-semibold {{ $isPagoDone || $isPagoCurrent ? 'text-indigo-600' : 'text-gray-500' }}">Pago</span>
                            </div>

                            <!-- Step 4: Postulante -->
                            <div class="flex flex-col items-center">
                                @php
                                    $isActive = $postulante->estadodocum == 'INSCRITO';
                                @endphp
                                <div class="h-12 w-12 rounded-full {{ $isActive ? 'bg-indigo-600 text-white border-white' : 'bg-white border-4 border-gray-300 text-gray-400' }} flex items-center justify-center shadow-md">
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span class="mt-3 text-sm font-semibold {{ $isActive ? 'text-indigo-600' : 'text-gray-500' }}">Oficial</span>
                            </div>

                        </div>
                    </div>

                    <!-- Estado Actual Detail Box -->
                    <div class="bg-gray-50 rounded-xl p-8 border border-gray-200">
                        @if($postulante->estadodocum == 'PENDIENTE')
                            <div class="text-center">
                                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 mb-4">
                                    <svg class="h-8 w-8 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">Fase 3: Entrega de Documentos Físicos</h3>
                                <p class="text-gray-600 mb-6 text-lg">
                                    Tu registro inicial está completo. Ahora debes dirigirte físicamente a la universidad para entregar tu documentación (fotocopia de CI, título de bachiller y certificado de nacimiento).
                                </p>
                                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 text-left w-full max-w-2xl mx-auto">
                                    <h4 class="font-bold text-xl text-gray-800 mb-4 border-b pb-2">Documentos a Presentar:</h4>
                                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-6">
                                        <li>Fotocopia simple del Carnet de Identidad (CI).</li>
                                        <li>Fotocopia legalizada del Título de Bachiller o libreta de 6to de secundaria.</li>
                                        <li>Certificado de Nacimiento original actualizado.</li>
                                        <li>2 Fotografías 3x4 fondo rojo.</li>
                                    </ul>
                                    
                                    <div class="bg-yellow-50 p-4 rounded-md border border-yellow-200 mb-4">
                                        <p class="text-yellow-800 font-semibold"><span class="mr-2">⏰</span> Fecha Límite: <span class="font-normal">5 días hábiles a partir del registro online.</span></p>
                                    </div>

                                    <h4 class="font-semibold text-gray-800 mb-2">Lugar de entrega:</h4>
                                    <p class="text-gray-600"><span class="font-medium text-gray-700">Oficina:</span> Dirección de Admisiones - Módulo 236 (Planta Baja)</p>
                                    <p class="text-gray-600"><span class="font-medium text-gray-700">Horario:</span> Lunes a Viernes, 08:00 - 16:00</p>
                                </div>
                            </div>
                        
                        @elseif($postulante->estadodocum == 'RECHAZADO')
                            <div class="text-center">
                                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                                    <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-red-700 mb-2">Documentación Observada</h3>
                                <p class="text-gray-600 mb-6 text-lg">
                                    Hubo un problema con la documentación que entregaste. Por favor lee las observaciones y acércate nuevamente a la oficina.
                                </p>
                                <div class="bg-red-50 p-6 rounded-lg border border-red-200 text-left">
                                    <h4 class="font-bold text-red-800 mb-2">Observaciones del Administrador:</h4>
                                    <p class="text-red-700">No hay detalles. Acércate a la oficina.</p>
                                </div>
                            </div>

                        @elseif($postulante->estadodocum == 'VERIFICADO' || $postulante->estadodocum == 'APROBADO')
                            <div class="text-center">
                                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-blue-100 mb-4">
                                    <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-2">Fase 6: Pago de Matrícula</h3>
                                <p class="text-gray-600 mb-6 text-lg">
                                    ¡Excelente! Tus documentos han sido verificados y aprobados. Ahora debes realizar el pago para convertirte oficialmente en postulante.
                                </p>
                                <a href="{{ route('pago.create') }}" class="inline-flex justify-center items-center px-8 py-4 border border-transparent text-lg font-bold rounded-xl text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-xl transition-transform transform hover:scale-105">
                                    Proceder al Pago
                                    <svg class="ml-2 -mr-1 h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                    </svg>
                                </a>
                                <p class="mt-4 text-sm text-gray-500">Monto a pagar: Bs. 300.- (Inscripción CUP)</p>
                            </div>

                        @elseif($postulante->estadodocum == 'INSCRITO')
                            <div class="text-center">
                                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-4">
                                    <svg class="h-10 w-10 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-3xl font-extrabold text-green-700 mb-2">¡Felicidades, ya eres Postulante Oficial!</h3>
                                <p class="text-gray-600 mb-6 text-lg">
                                    Has completado exitosamente todas las fases. Se te ha asignado un grupo y pronto podrás ver tus horarios y fechas de exámenes.
                                </p>
                                <a href="{{ route('dashboard') }}" class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                    Ir a mi Dashboard
                                </a>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
