<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CUP - FICCT</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Tailwind CSS (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-['Inter'] text-slate-800 bg-slate-50">

    <!-- Navigation -->
    <nav class="bg-white/80 backdrop-blur-md fixed w-full z-50 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-600 text-white flex items-center justify-center rounded-xl font-bold text-xl shadow-lg shadow-indigo-200">
                        F
                    </div>
                    <div>
                        <span class="font-bold text-xl tracking-tight block">FICCT</span>
                        <span class="text-xs text-slate-500 font-medium tracking-wider uppercase">Admisión Universitaria</span>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-slate-600 hover:text-indigo-600 transition-colors">Ir al Panel</a>
                        @else
                            <a href="{{ route('login') }}" class="px-5 py-2.5 text-sm font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">Iniciar Sesión</a>
                            
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-md hover:shadow-lg transition-all">Registrarse</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-indigo-100 via-white to-white opacity-70"></div>
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-8">
                Sistema de Admisión <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">Curso Preuniversitario</span>
            </h1>
            
            <p class="mt-4 text-xl text-slate-600 max-w-2xl mx-auto mb-10 leading-relaxed">
                Gestiona tu postulación, revisa tus notas de Computación, Matemáticas, Inglés y Física, y conoce tu estado de admisión al instante.
            </p>
            
            <div class="flex justify-center gap-4">
                <a href="{{ route('login') }}" class="px-8 py-4 text-base font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-xl shadow-indigo-200 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    Ingresar al Sistema
                </a>
            </div>
        </div>
    </div>

    <!-- Features -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Registro Fácil</h3>
                    <p class="text-slate-600">Proceso de inscripción digitalizado y validado en tiempo real.</p>
                </div>
                
                <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Cálculo Automático</h3>
                    <p class="text-slate-600">Promedios y estado de admisión procesados instantáneamente por el sistema.</p>
                </div>
                
                <div class="p-8 rounded-2xl bg-slate-50 border border-slate-100 hover:shadow-lg transition-shadow">
                    <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center mb-6">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Asignación de Grupos</h3>
                    <p class="text-slate-600">Distribución inteligente de estudiantes en aulas y horarios disponibles.</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Institutional Info Section -->
    <div class="py-20 bg-slate-50 border-t border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-bold text-slate-900 mb-6">Información Institucional</h2>
                <p class="text-lg text-slate-600 mb-6 leading-relaxed">
                    El Curso Pre-universitario (CUP) de la Facultad de Ingeniería en Ciencias de la Computación y Telecomunicaciones (FICCT) es una modalidad de ingreso presencial y virtual, dirigida a bachilleres que desean formar parte de una de las carreras tecnológicas con mayor crecimiento e impacto profesional.
                </p>
                <p class="text-lg text-slate-600 leading-relaxed">
                    El programa cuenta con una carga académica mínima de 192 horas, brindando formación básica y nivelación académica para el ingreso universitario.
                </p>
            </div>

            <!-- Carreras Disponibles -->
            <h3 class="text-2xl font-bold text-center text-slate-900 mb-10">Carreras Disponibles</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow text-center">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                    </div>
                    <h4 class="font-bold text-slate-800">Ingeniería Informática</h4>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow text-center">
                    <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <h4 class="font-bold text-slate-800">Ingeniería de Sistemas</h4>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow text-center">
                    <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01m-7.08-7.071c3.904-3.905 10.236-3.906 14.142 0M1.394 9.393c5.857-5.857 15.355-5.857 21.213 0"></path></svg>
                    </div>
                    <h4 class="font-bold text-slate-800">Ingeniería en Redes y Telecom.</h4>
                </div>
                <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow text-center">
                    <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <h4 class="font-bold text-slate-800">Ingeniería en Robótica</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 mb-12">
                
                <!-- Brand Info -->
                <div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-indigo-500 text-white flex items-center justify-center rounded-xl font-bold text-xl shadow-lg">
                            F
                        </div>
                        <span class="font-bold text-2xl tracking-tight block">FICCT</span>
                    </div>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">
                        Formación tecnológica para el futuro. Impulsando la innovación y el desarrollo profesional en ciencias de la computación.
                    </p>
                </div>

                <!-- Síguenos -->
                <div>
                    <h4 class="text-lg font-semibold mb-6">Síguenos</h4>
                    <p class="text-slate-400 text-sm mb-4">Mantente informado sobre convocatorias, noticias y actividades académicas de la FICCT.</p>
                    <ul class="space-y-3">
                        <li>
                            <a href="#" class="text-indigo-400 hover:text-indigo-300 transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h-2v-6h2v6zm-1-7c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1zm5 7h-2v-3.5c0-.83-.67-1.5-1.5-1.5s-1.5.67-1.5 1.5V17h-2v-6h2v1.5c.31-.62.92-1.5 2-1.5 1.1 0 2 .9 2 2V17z"/></svg>
                                Sitio Web FICCT
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-indigo-400 hover:text-indigo-300 transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z"/></svg>
                                Facebook Facultativo
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contáctanos -->
                <div>
                    <h4 class="text-lg font-semibold mb-6">Contáctanos</h4>
                    <ul class="space-y-4 text-slate-400 text-sm">
                        <li class="flex items-start gap-3">
                            <span class="text-xl">📍</span>
                            <span>Ciudad Universitaria<br>Módulo 236</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-xl">📞</span>
                            <span>(+591) 70988656</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-xl">✉️</span>
                            <a href="mailto:cup.ficct@uagrm.edu.bo" class="hover:text-white transition-colors">cup.ficct@uagrm.edu.bo</a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-slate-800 pt-8 text-center text-sm text-slate-500">
                &copy; {{ date('Y') }} Facultad de Ingeniería en Ciencias de la Computación y Telecomunicaciones (FICCT). Todos los derechos reservados.
            </div>
        </div>
    </footer>

</body>
</html>
