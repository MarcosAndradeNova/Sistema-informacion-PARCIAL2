<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal Universitario - FICCT</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Tailwind CSS (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-['Inter'] text-slate-800 bg-slate-50 flex flex-col min-h-screen">

    <!-- Navigation -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <!-- Decorative Red/Blue top bar -->
        <div class="h-1 w-full bg-gradient-to-r from-blue-700 via-blue-600 to-red-600"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-3">
                    <!-- Mixed Blue & Red Logo -->
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-700 to-red-600 text-white flex items-center justify-center rounded-xl font-bold text-2xl shadow-md">
                        F
                    </div>
                    <div>
                        <span class="font-bold text-xl text-slate-900 tracking-tight block">FICCT</span>
                        <span class="text-xs text-slate-500 font-semibold tracking-wider uppercase">Admisión Universitaria</span>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-semibold text-slate-600 hover:text-red-600 transition-colors">Ir al Panel</a>
                        @else
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-6 py-2.5 text-sm font-bold text-white bg-gradient-to-r from-blue-700 to-blue-600 hover:from-blue-800 hover:to-blue-700 rounded-lg shadow-md shadow-blue-200 hover:shadow-lg transition-all border-b-2 border-blue-900 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                    Registrarse
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
        <!-- Hero & Access Section -->
        <div class="relative pt-16 pb-16 lg:pt-24 lg:pb-24 overflow-hidden bg-white">
            <div class="absolute inset-0 z-0 pointer-events-none">
                <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-blue-50/50 via-red-50/10 to-transparent rounded-full transform translate-x-1/3 -translate-y-1/3"></div>
            </div>
            
            <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12 relative">
                    <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight text-slate-900 mb-4">
                        Portal de Acceso Universitario
                    </h1>
                    <p class="text-lg text-slate-600 max-w-2xl mx-auto font-medium">
                        Sistema Integrado del Curso Preuniversitario de la Facultad de Ingeniería en Ciencias de la Computación y Telecomunicaciones.
                    </p>
                </div>
                
                <!-- Role Cards (The Core Request) -->
                <div class="max-w-5xl mx-auto grid grid-cols-1 sm:grid-cols-3 gap-6 lg:gap-8 mt-12">
                    
                    <!-- Estudiante -->
                    <a href="{{ route('login', ['role' => 'estudiante']) }}" class="group flex flex-col items-center bg-white border border-slate-200 rounded-2xl p-8 text-center hover:border-blue-600 hover:shadow-xl hover:shadow-blue-100/50 transition-all duration-300 transform hover:-translate-y-2 cursor-pointer relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1 bg-blue-600 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
                        <div class="w-20 h-20 bg-slate-50 text-slate-600 group-hover:bg-blue-700 group-hover:text-white rounded-2xl flex items-center justify-center mb-5 transition-colors duration-300 shadow-sm border border-slate-100 group-hover:border-blue-700">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path></svg>
                        </div>
                        <h3 class="font-bold text-slate-900 text-xl group-hover:text-blue-700 transition-colors mb-2">Estudiante</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Acceso a notas, estado de postulación y horarios de grupos.
                        </p>
                    </a>

                    <!-- Docente -->
                    <a href="{{ route('login', ['role' => 'docente']) }}" class="group flex flex-col items-center bg-white border border-slate-200 rounded-2xl p-8 text-center hover:border-red-600 hover:shadow-xl hover:shadow-red-100/50 transition-all duration-300 transform hover:-translate-y-2 cursor-pointer relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1 bg-red-600 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
                        <div class="w-20 h-20 bg-slate-50 text-slate-600 group-hover:bg-red-600 group-hover:text-white rounded-2xl flex items-center justify-center mb-5 transition-colors duration-300 shadow-sm border border-slate-100 group-hover:border-red-600">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <h3 class="font-bold text-slate-900 text-xl group-hover:text-red-600 transition-colors mb-2">Docente</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Gestión de calificaciones, aulas y evaluación de postulantes.
                        </p>
                    </a>

                    <!-- Administrativo -->
                    <a href="{{ route('login', ['role' => 'administrativo']) }}" class="group flex flex-col items-center bg-white border border-slate-200 rounded-2xl p-8 text-center hover:border-slate-800 hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 transform hover:-translate-y-2 cursor-pointer relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1 bg-slate-800 transform origin-left scale-x-0 group-hover:scale-x-100 transition-transform duration-300"></div>
                        <div class="w-20 h-20 bg-slate-50 text-slate-600 group-hover:bg-slate-800 group-hover:text-white rounded-2xl flex items-center justify-center mb-5 transition-colors duration-300 shadow-sm border border-slate-100 group-hover:border-slate-800">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="font-bold text-slate-900 text-xl group-hover:text-slate-800 transition-colors mb-2">Administrativo</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Control académico, reportes y configuración del sistema.
                        </p>
                    </a>
                </div>
            </div>
        </div>

        <!-- Institutional Features -->
        <div class="py-16 bg-slate-50 border-t border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="p-6 rounded-2xl bg-white border border-slate-100 shadow-sm group hover:border-red-200 transition-colors">
                        <div class="w-12 h-12 bg-blue-50 text-blue-700 rounded-lg flex items-center justify-center mb-5 group-hover:bg-red-50 group-hover:text-red-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Registro Digital</h3>
                        <p class="text-sm text-slate-600">Proceso de inscripción simplificado y validado en tiempo real de forma segura.</p>
                    </div>
                    
                    <div class="p-6 rounded-2xl bg-white border border-slate-100 shadow-sm group hover:border-blue-200 transition-colors">
                        <div class="w-12 h-12 bg-red-50 text-red-600 rounded-lg flex items-center justify-center mb-5 group-hover:bg-blue-50 group-hover:text-blue-700 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Resultados Automáticos</h3>
                        <p class="text-sm text-slate-600">Procesamiento instantáneo de calificaciones y publicación de estados de admisión.</p>
                    </div>
                    
                    <div class="p-6 rounded-2xl bg-white border border-slate-100 shadow-sm group hover:border-slate-300 transition-colors">
                        <div class="w-12 h-12 bg-slate-100 text-slate-700 rounded-lg flex items-center justify-center mb-5 group-hover:bg-slate-200 group-hover:text-slate-800 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Asignación Transparente</h3>
                        <p class="text-sm text-slate-600">Distribución organizada de estudiantes en aulas mediante algoritmos optimizados.</p>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 py-12 border-t-4 border-red-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-8">
                
                <!-- Brand Info -->
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-700 to-red-600 text-white flex items-center justify-center rounded-xl font-bold text-xl shadow-lg">
                            F
                        </div>
                        <span class="font-bold text-2xl tracking-tight text-white block">FICCT</span>
                    </div>
                    <p class="text-sm leading-relaxed max-w-xs">
                        Formación tecnológica para el futuro. Impulsando la innovación y el desarrollo profesional en ciencias de la computación.
                    </p>
                </div>

                <!-- Enlaces Rápidos -->
                <div>
                    <h4 class="text-lg font-bold text-white mb-4">Enlaces Rápidos</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-red-400 transition-colors">Portal UAGRM</a></li>
                        <li><a href="#" class="hover:text-red-400 transition-colors">Carreras FICCT</a></li>
                        <li><a href="#" class="hover:text-red-400 transition-colors">Soporte Técnico</a></li>
                    </ul>
                </div>

                <!-- Contacto -->
                <div>
                    <h4 class="text-lg font-bold text-white mb-4">Contacto</h4>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start gap-3">
                            <span class="text-lg">📍</span>
                            <span>Ciudad Universitaria<br>Módulo 236</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-lg">📞</span>
                            <span>(+591) 70988656</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <span class="text-lg">✉️</span>
                            <a href="mailto:cup.ficct@uagrm.edu.bo" class="hover:text-red-400 transition-colors">cup.ficct@uagrm.edu.bo</a>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-slate-800 pt-8 text-center text-xs text-slate-500">
                &copy; {{ date('Y') }} Facultad de Ingeniería en Ciencias de la Computación y Telecomunicaciones. Universidad Autónoma Gabriel René Moreno. Todos los derechos reservados.
            </div>
        </div>
    </footer>

</body>
</html>
