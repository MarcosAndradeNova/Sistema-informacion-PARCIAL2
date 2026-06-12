@php
    $usuario = \App\Models\Usuario::where('email', Auth::user()->email)->first();
    $isAdmin = $usuario && $usuario->tipo === 'A';
    
    // Validar si es un docente APROBADO
    $isDocenteAprobado = false;
    $esDocentePendiente = false;
    $registroIncompleto = false;
    if ($usuario && $usuario->tipo === 'D') {
        $docenteInfo = \App\Models\Docente::where('ciusuario', $usuario->ci)->first();
        if ($docenteInfo && $docenteInfo->estado === 'APROBADO') {
            $isDocenteAprobado = true;
        } elseif ($docenteInfo && $docenteInfo->estado === 'PENDIENTE') {
            $esDocentePendiente = true;
        } else {
            // El usuario existe pero no se completó la inserción en la tabla docente
            $registroIncompleto = true;
        }
    }
    
    $esPostulanteActivo = false;
    $tipo = $usuario ? $usuario->tipo : 'P';
    if ($usuario && $tipo === 'P') {
        $postulanteInfo = \App\Models\Postulante::where('ciusuario', $usuario->ci)->first();
        if ($postulanteInfo && $postulanteInfo->estadodocum === 'INSCRITO') {
            $esPostulanteActivo = true;
        }
    }
@endphp

<div x-data="{ collapsed: false }" 
     class="flex flex-col h-full bg-white border-r border-gray-200 shadow-xl transition-all duration-300"
     :class="collapsed ? 'w-20' : 'w-72'">
    
    <!-- Logo & Toggle -->
    <div class="h-16 flex items-center justify-between px-4 border-b border-gray-100 bg-indigo-600">
        <div class="flex items-center" x-show="!collapsed" x-transition.duration.300ms>
            <x-application-logo class="block h-9 w-auto fill-current text-white" />
            <span class="ml-3 text-white font-bold text-xl tracking-wider">CUP FICCT</span>
        </div>
        
        <button @click="collapsed = !collapsed" class="text-white hover:text-indigo-200 focus:outline-none transition-colors" :class="collapsed ? 'mx-auto' : ''">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" x-show="collapsed"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" x-show="!collapsed"/>
            </svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 overflow-y-auto py-4 space-y-6 flex flex-col justify-between">
        <nav class="px-3 space-y-1">
            
            <!-- SECCIÓN: GENERAL -->
            <div x-show="!collapsed" class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-4 px-3">General</div>
            <a href="{{ route('dashboard') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('dashboard') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span x-show="!collapsed" x-transition.opacity.duration.300ms>Dashboard</span>
            </a>

            @if($isAdmin)
            <a href="{{ route('admin.bitacora.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.bitacora.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('admin.bitacora.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span x-show="!collapsed">Bitácora</span>
            </a>
            @endif

            @if($isAdmin)
            <a href="{{ route('admin.carreras.index') }}" class="mt-1 group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.carreras.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('admin.carreras.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <span x-show="!collapsed">Carreras y Cupos</span>
            </a>
            @endif

            <!-- SECCIÓN: POSTULANTES/NUEVOS -->
            @if(!$usuario || $esDocentePendiente || $registroIncompleto)
            <div x-show="!collapsed" class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-6 px-3">Registro de Usuario</div>
            
            @if(Auth::user()->role === 'docente' || $esDocentePendiente || $registroIncompleto)
            <a href="{{ route('docente.inscripcion.create') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('docente.inscripcion.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('docente.inscripcion.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
                <span x-show="!collapsed">Ficha de Postulación Docente</span>
            </a>
            @else
            <a href="{{ route('inscripcion.estado') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('inscripcion.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('inscripcion.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span x-show="!collapsed">Ficha de Postulación</span>
            </a>
            @endif

            @elseif($tipo === 'P' && $usuario)
            <div x-show="!collapsed" class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-6 px-3">Registro de Postulantes</div>
            
            <a href="{{ route('inscripcion.estado') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('inscripcion.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('inscripcion.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span x-show="!collapsed">Ficha de Postulación</span>
            </a>
            @endif

            @if($isAdmin)
            <a href="{{ route('admin.postulantes') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.postulantes*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('admin.postulantes*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span x-show="!collapsed">Registro de Postulantes</span>
            </a>

            <a href="{{ route('docente.mis_grupos') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('docente.mis_grupos') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('docente.mis_grupos') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span x-show="!collapsed">Mis Grupos</span>
            </a>

            <a href="{{ route('admin.docentes.index') }}" class="mt-1 group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.docentes.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('admin.docentes.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                <span x-show="!collapsed">Gestionar Docentes</span>
            </a>
            @endif

            <!-- SECCIÓN: PANEL ESTUDIANTE -->
            @if($esPostulanteActivo)
            <div x-show="!collapsed" class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-6 px-3">Mi Panel de Estudio</div>
            
            <a href="{{ route('estudiante.mi_grupo') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('estudiante.mi_grupo') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('estudiante.mi_grupo') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span x-show="!collapsed">Mi Grupo</span>
            </a>

            <a href="{{ route('estudiante.mis_materias') }}" class="mt-1 group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('estudiante.mis_materias') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('estudiante.mis_materias') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <span x-show="!collapsed">Mis Materias</span>
            </a>

            <a href="{{ route('estudiante.mis_examenes') }}" class="mt-1 group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('estudiante.mis_examenes') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('estudiante.mis_examenes') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span x-show="!collapsed">Exámenes y Notas</span>
            </a>
            @endif

            <!-- SECCIÓN: EXÁMENES -->
            @if($isAdmin)
            <div x-show="!collapsed" class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-6 px-3">Verificación de Exámenes</div>
            
            <a href="{{ route('admin.examenes.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.examenes.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('admin.examenes.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span x-show="!collapsed">Exámenes y Notas</span>
            </a>

            <a href="{{ route('examenes.puntos') }}" class="mt-1 group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('examenes.puntos') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('examenes.puntos') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span x-show="!collapsed">Configurar Materias</span>
            </a>
            @endif

            <!-- SECCIÓN: GRUPOS -->
            @if($isAdmin)
            <div x-show="!collapsed" class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-6 px-3">Gestión de Grupos</div>
            <a href="{{ route('admin.grupos.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.grupos.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('admin.grupos.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span x-show="!collapsed">Gestionar Grupos</span>
            </a>

            <!-- SECCIÓN: REPORTES -->
            <div x-show="!collapsed" class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-6 px-3">Reportes</div>
            <a href="{{ route('admin.reportes.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.reportes.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('admin.reportes.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span x-show="!collapsed">Generar Reportes</span>
            </a>
            @endif

            @if($isDocenteAprobado)
            <!-- SECCIÓN: PANEL DOCENTE -->
            <div x-show="!collapsed" class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-6 px-3">Mi Panel de Docente</div>
            
            <a href="{{ route('docente.mi_materia') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('docente.mi_materia') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('docente.mi_materia') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span x-show="!collapsed">Mi Materia</span>
            </a>
            
            <a href="{{ route('docente.mis_grupos') }}" class="mt-1 group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('docente.mis_grupos') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('docente.mis_grupos') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span x-show="!collapsed">Mis Grupos</span>
            </a>

            <a href="{{ route('docente.calificaciones') }}" class="mt-1 group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('docente.calificaciones') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('docente.calificaciones') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span x-show="!collapsed">Calificaciones</span>
            </a>
            
            <a href="{{ route('docente.cronograma') }}" class="mt-1 group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('docente.cronograma') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('docente.cronograma') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span x-show="!collapsed">Cronograma Exámenes</span>
            </a>
            @endif
        </nav>

        <!-- SECCIÓN: USUARIO / AUTENTICACIÓN -->
        <div class="px-3">
            <div class="border-t border-gray-200 pt-4">
                <a href="{{ route('profile.edit') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 hover:text-indigo-600">
                    <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span x-show="!collapsed">Mi Perfil</span>
                </a>

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="group flex w-full items-center px-3 py-2 text-sm font-medium rounded-lg text-red-600 hover:bg-red-50">
                        <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 text-red-500 group-hover:text-red-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span x-show="!collapsed">Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
