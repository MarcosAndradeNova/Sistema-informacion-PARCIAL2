<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Panel Académico Docente') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <!-- Banner Section -->
            <div class="relative rounded-2xl overflow-hidden shadow-2xl h-80 sm:h-96 group">
                <img src="{{ asset('images/ficct_banner.png') }}" alt="FICCT Campus" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-emerald-900/40 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-8">
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-2 drop-shadow-md">
                        Portal Oficial del Docente Universitario
                    </h1>
                    <p class="text-xl text-emerald-100 font-medium max-w-3xl drop-shadow">
                        Gestión académica, control de estudiantes y evaluación de competencias.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Welcome Card -->
                <div class="md:col-span-2 bg-white rounded-2xl p-8 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="bg-emerald-100 p-3 rounded-lg mr-4">
                            <svg class="h-8 w-8 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">Bienvenido a su Panel Académico</h3>
                    </div>
                    <p class="text-gray-600 leading-relaxed text-lg mb-4">
                        A través de este portal usted tendrá acceso a las herramientas necesarias para gestionar sus materias, realizar el seguimiento del avance de sus estudiantes y cargar las calificaciones correspondientes al Curso Universitario de Preparación.
                    </p>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        El compromiso docente es fundamental para el desarrollo y nivelación de los futuros ingenieros de la facultad.
                    </p>
                </div>

                <!-- Avisos Card -->
                <div class="bg-gradient-to-br from-emerald-800 to-teal-900 rounded-2xl p-8 shadow-lg text-white">
                    <h3 class="text-2xl font-bold mb-6 border-b border-emerald-500 pb-2">Avisos Académicos</h3>
                    <ul class="space-y-4 text-emerald-100">
                        <li class="flex items-start bg-emerald-900/30 p-3 rounded-lg border border-emerald-700/50">
                            <svg class="h-6 w-6 text-yellow-400 mr-3 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-sm">Recuerde que el plazo máximo para la entrega de actas de notas en sistema es de 48 horas después de finalizado cada examen oficial.</p>
                        </li>
                        <li class="flex items-start bg-emerald-900/30 p-3 rounded-lg border border-emerald-700/50">
                            <svg class="h-6 w-6 text-emerald-400 mr-3 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-sm">El cronograma oficial de este semestre ya ha sido definido. Por favor evite reprogramar clases sin previo aviso a la dirección.</p>
                        </li>
                        <li class="flex items-start bg-emerald-900/30 p-3 rounded-lg border border-emerald-700/50">
                            <svg class="h-6 w-6 text-blue-400 mr-3 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-sm">Las licencias y permisos excepcionales deben solicitarse en formato físico en la ventanilla principal de la Facultad.</p>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
