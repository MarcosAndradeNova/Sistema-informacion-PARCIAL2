<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Inicio') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Banner Section -->
            <div class="relative rounded-2xl overflow-hidden shadow-2xl h-80 sm:h-96 group">
                <img src="{{ asset('images/ficct_banner.png') }}" alt="FICCT Campus" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-8">
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-white mb-2 drop-shadow-md">
                        Facultad de Ingeniería en Ciencias de la Computación y Telecomunicaciones
                    </h1>
                    <p class="text-xl text-blue-100 font-medium max-w-3xl drop-shadow">
                        Formando a los líderes tecnológicos del mañana con excelencia y compromiso.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                <!-- Objetivos Card -->
                <div class="md:col-span-2 bg-white rounded-2xl p-8 shadow-lg border border-gray-100 hover:shadow-xl transition-shadow">
                    <div class="flex items-center mb-4">
                        <div class="bg-indigo-100 p-3 rounded-lg mr-4">
                            <svg class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800">Nuestros Objetivos</h3>
                    </div>
                    <p class="text-gray-600 leading-relaxed text-lg mb-4">
                        El <strong>CUP (Curso Universitario de Preparación)</strong> de la FICCT tiene como objetivo principal nivelar y evaluar a los futuros estudiantes, asegurando que cuenten con las bases matemáticas, lógicas y analíticas necesarias para afrontar los desafíos de las carreras tecnológicas de nuestra facultad.
                    </p>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        Buscamos identificar el talento y la dedicación, promoviendo un ambiente de excelencia académica desde el primer día.
                    </p>
                </div>

                <!-- Contact & Info Card -->
                <div class="bg-gradient-to-br from-indigo-800 to-blue-900 rounded-2xl p-8 shadow-lg text-white">
                    <h3 class="text-2xl font-bold mb-6 border-b border-indigo-500 pb-2">Información de Contacto</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-blue-300 mr-3 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <div>
                                <strong class="block text-blue-100">Ubicación</strong>
                                <span class="text-gray-300">Av. Busch, Módulo 236 (Ciudad Universitaria)</span>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-blue-300 mr-3 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <div>
                                <strong class="block text-blue-100">Teléfono</strong>
                                <span class="text-gray-300">+591 3 355-6677</span>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <svg class="h-6 w-6 text-blue-300 mr-3 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <div>
                                <strong class="block text-blue-100">Correo Institucional</strong>
                                <span class="text-gray-300">admisiones@ficct.uagrm.edu.bo</span>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Carreras Section -->
            <div class="pt-6">
                <h3 class="text-3xl font-bold text-gray-800 mb-8 text-center">Nuestras Carreras</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8">
                    
                    <!-- Carrera 1 -->
                    <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                        <div class="h-40 bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center">
                            <svg class="h-20 w-20 text-white opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                            </svg>
                        </div>
                        <div class="p-6">
                            <h4 class="text-xl font-bold text-gray-900 mb-2">Ingeniería Informática</h4>
                            <p class="text-gray-600 mb-4">Desarrollo de software avanzado, inteligencia artificial y arquitecturas computacionales.</p>
                        </div>
                    </div>

                    <!-- Carrera 2 -->
                    <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                        <div class="h-40 bg-gradient-to-r from-cyan-500 to-blue-600 flex items-center justify-center">
                            <svg class="h-20 w-20 text-white opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                            </svg>
                        </div>
                        <div class="p-6">
                            <h4 class="text-xl font-bold text-gray-900 mb-2">Ingeniería de Sistemas</h4>
                            <p class="text-gray-600 mb-4">Análisis, diseño y gestión de sistemas de información empresariales e integración de IT.</p>
                        </div>
                    </div>

                    <!-- Carrera 3 -->
                    <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                        <div class="h-40 bg-gradient-to-r from-purple-500 to-pink-600 flex items-center justify-center">
                            <svg class="h-20 w-20 text-white opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="p-6">
                            <h4 class="text-xl font-bold text-gray-900 mb-2">Ingeniería en Robótica</h4>
                            <p class="text-gray-600 mb-4">Diseño y desarrollo de sistemas mecatrónicos, robótica industrial e inteligencia artificial aplicada.</p>
                        </div>
                    </div>

                    <!-- Carrera 4 -->
                    <div class="bg-white rounded-2xl shadow-md overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100">
                        <div class="h-40 bg-gradient-to-r from-teal-500 to-emerald-600 flex items-center justify-center">
                            <svg class="h-20 w-20 text-white opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.905 14.141 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0" />
                            </svg>
                        </div>
                        <div class="p-6">
                            <h4 class="text-xl font-bold text-gray-900 mb-2">Redes y Telecomunicaciones</h4>
                            <p class="text-gray-600 mb-4">Diseño de infraestructura de redes, ciberseguridad y tecnologías de comunicación global.</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
