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
    
    $isCoordinador = false;
    if ($usuario && $usuario->tipo === 'C') {
        $isCoordinador = true;
    }
    
    $esPostulanteActivo = false;
    $tipo = $usuario ? $usuario->tipo : 'P';
    if ($usuario && $tipo === 'P') {
        $postulanteInfo = \App\Models\Postulante::where('ciusuario', $usuario->ci)->first();
        if ($postulanteInfo && $postulanteInfo->estadodocum === 'INSCRITO') {
            $esPostulanteActivo = true;
        }
    }

    // Lógicas de visualización para las categorías
    $isDocenteFicha = (!$usuario || $esDocentePendiente || $registroIncompleto) && (Auth::user()->role === 'docente' || $esDocentePendiente || $registroIncompleto);
    $isPostulanteFicha = ((!$usuario || $esDocentePendiente || $registroIncompleto) && !(Auth::user()->role === 'docente' || $esDocentePendiente || $registroIncompleto)) || ($tipo === 'P' && $usuario);

    $showPersonal = $isAdmin || $isDocenteFicha;
    $showInscripcion = $isAdmin || $isPostulanteFicha || $isCoordinador;
    $showAcademica = $isAdmin || $esPostulanteActivo || $isDocenteAprobado || $isCoordinador;
    $showEvaluaciones = $isAdmin || $esPostulanteActivo || $isDocenteAprobado || $isCoordinador;
    $showReportes = $isAdmin || $isCoordinador;
@endphp

<div class="fixed inset-y-0 left-0 z-30 lg:static flex flex-col h-full bg-white border-r border-gray-200 shadow-xl transition-all duration-300 flex-shrink-0"
     :class="{
         '-translate-x-full lg:translate-x-0': !sidebarOpen,
         'translate-x-0': sidebarOpen,
         'w-72': !collapsed || sidebarOpen,
         'lg:w-20': collapsed && !sidebarOpen
     }">
    
    <!-- Logo & Toggle -->
    <div class="h-16 flex items-center justify-between px-4 border-b border-gray-100 bg-indigo-600">
        <div class="flex items-center" x-show="!collapsed || sidebarOpen" x-transition.duration.300ms>
            <span class="ml-3 text-white font-bold text-xl tracking-wider">CUP FICCT</span>
        </div>
        
        <button @click="if(window.innerWidth < 1024) { sidebarOpen = false; } else { collapsed = !collapsed; }" class="text-white hover:text-indigo-200 focus:outline-none transition-colors" :class="(collapsed && !sidebarOpen) ? 'mx-auto' : ''">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <!-- Icono cuando está colapsado (desktop) -->
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" x-show="collapsed && !sidebarOpen"/>
                <!-- Icono cuando está expandido o abierto en móvil -->
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" x-show="!collapsed || sidebarOpen"/>
            </svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <div class="flex-1 overflow-y-auto py-4 space-y-2 flex flex-col justify-between">
        <nav class="px-3 space-y-2">
            
            <!-- 1. Seguridad y Acceso -->
            <div x-data="{ open: {{ request()->routeIs('dashboard', 'admin.roles.*', 'profile.edit') ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="if(collapsed) { collapsed = false; open = true; } else { open = !open; }" class="w-full group flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg hover:bg-gray-50 hover:text-indigo-600 {{ request()->routeIs('dashboard', 'admin.roles.*', 'profile.edit') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700' }}">
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('dashboard', 'admin.roles.*', 'profile.edit') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <span x-show="!collapsed || sidebarOpen" class="text-left font-semibold">Seguridad y Acceso</span>
                    </div>
                    <svg x-show="!collapsed || sidebarOpen" :class="{'rotate-180': open}" class="ml-2 h-5 w-5 transform transition-transform text-gray-400 group-hover:text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open && !collapsed" class="pl-11 space-y-1 mt-1 border-l-2 border-indigo-100 ml-5">
                    <a href="{{ route('dashboard') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">Dashboard</a>
                    <a href="{{ route('profile.edit') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('profile.edit') ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">Administrar Perfil</a>
                    @if($isAdmin)
                    <a href="{{ route('admin.roles.index') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.roles.*') ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">Gestionar Roles y Permisos</a>
                    @endif
                </div>
            </div>

            <!-- 2. Gestión de Personal -->
            @if($showPersonal)
            <div x-data="{ open: {{ request()->routeIs('admin.docentes.*', 'docente.inscripcion.*', 'admin.coordinadores.*') ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="if(collapsed) { collapsed = false; open = true; } else { open = !open; }" class="w-full group flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg hover:bg-gray-50 hover:text-indigo-600 {{ request()->routeIs('admin.docentes.*', 'docente.inscripcion.*', 'admin.coordinadores.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700' }}">
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('admin.docentes.*', 'docente.inscripcion.*', 'admin.coordinadores.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span x-show="!collapsed || sidebarOpen" class="text-left font-semibold">Gestión de Personal</span>
                    </div>
                    <svg x-show="!collapsed || sidebarOpen" :class="{'rotate-180': open}" class="ml-2 h-5 w-5 transform transition-transform text-gray-400 group-hover:text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open && !collapsed" class="pl-11 space-y-1 mt-1 border-l-2 border-indigo-100 ml-5">
                    @if($isAdmin)
                    <a href="{{ route('admin.docentes.index') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.docentes.*') ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">Gestionar Docentes</a>
                    <a href="{{ route('admin.coordinadores.index') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.coordinadores.*') ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">Gestionar Coordinador</a>
                    @endif
                    @if($isDocenteFicha)
                    <a href="{{ route('docente.inscripcion.create') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('docente.inscripcion.*') ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">Ficha de Postulación Docente</a>
                    @endif
                </div>
            </div>
            @endif

            <!-- 3. Inscripción y Pago -->
            @if($showInscripcion)
            <div x-data="{ open: {{ request()->routeIs('admin.postulantes*', 'inscripcion.*') ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="if(collapsed) { collapsed = false; open = true; } else { open = !open; }" class="w-full group flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg hover:bg-gray-50 hover:text-indigo-600 {{ request()->routeIs('admin.postulantes*', 'inscripcion.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700' }}">
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('admin.postulantes*', 'inscripcion.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span x-show="!collapsed || sidebarOpen" class="text-left font-semibold">Inscripción y Pago</span>
                    </div>
                    <svg x-show="!collapsed || sidebarOpen" :class="{'rotate-180': open}" class="ml-2 h-5 w-5 transform transition-transform text-gray-400 group-hover:text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open && !collapsed" class="pl-11 space-y-1 mt-1 border-l-2 border-indigo-100 ml-5">
                    @if($isAdmin || $isCoordinador)
                    <a href="{{ route('admin.postulantes.create') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.postulantes.create') ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">Registrar Postulante</a>
                    <a href="{{ route('admin.postulantes') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.postulantes') ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">Verificar Documentos</a>
                    @endif
                    @if($esPostulanteActivo)
                    <a href="{{ route('inscripcion.estado') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('inscripcion.*') ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">Estado de Admisión</a>
                    @elseif($tipo === 'P' && $usuario && !$esPostulanteActivo)
                    <a href="{{ route('inscripcion.estado') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('inscripcion.*') ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">Verificar mi Estado</a>
                    @endif
                </div>
            </div>
            @endif

            <!-- 4. Organización Académica y Grupos -->
            @if($showAcademica)
            <div x-data="{ open: {{ request()->routeIs('admin.carreras.*', 'examenes.puntos', 'admin.grupos.*', 'admin.asignacion.*', 'admin.examenes.*', 'estudiante.mi_grupo', 'estudiante.mis_materias', 'docente.mis_grupos', 'docente.cronograma', 'docente.mi_horario') ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="if(collapsed) { collapsed = false; open = true; } else { open = !open; }" class="w-full group flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg hover:bg-gray-50 hover:text-indigo-600 {{ request()->routeIs('admin.carreras.*', 'examenes.puntos', 'admin.grupos.*', 'admin.asignacion.*', 'admin.examenes.*', 'estudiante.mi_grupo', 'estudiante.mis_materias', 'docente.mis_grupos', 'docente.cronograma', 'docente.mi_horario') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700' }}">
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('admin.carreras.*', 'examenes.puntos', 'admin.grupos.*', 'admin.asignacion.*', 'admin.examenes.*', 'estudiante.mi_grupo', 'estudiante.mis_materias', 'docente.mis_grupos', 'docente.cronograma', 'docente.mi_horario') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        <span x-show="!collapsed || sidebarOpen" class="text-left font-semibold leading-tight pr-2">Organización Académica y Grupos</span>
                    </div>
                    <svg x-show="!collapsed || sidebarOpen" :class="{'rotate-180': open}" class="ml-1 flex-shrink-0 h-5 w-5 transform transition-transform text-gray-400 group-hover:text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open && !collapsed" class="pl-11 space-y-1 mt-1 border-l-2 border-indigo-100 ml-5">
                    @if($isAdmin)
                    <a href="{{ route('admin.carreras.index') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.carreras.*') ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">Gestionar Carreras</a>
                    <a href="{{ route('examenes.puntos') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('examenes.puntos') ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">Gestionar Materias</a>
                    @endif
                    @if($isAdmin || $isCoordinador)
                    <a href="{{ route('admin.grupos.index') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.grupos.*') ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">{{ $isCoordinador ? 'Consulta de Grupos' : 'Gestionar Grupos' }}</a>
                    @endif
                    @if($isAdmin)
                    <a href="{{ route('admin.asignacion.index') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.asignacion.*') ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">Asignar Docente a Grupo</a>
                    <a href="{{ route('admin.examenes.index') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.examenes.*') ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">Gestionar Examen</a>
                    @endif
                    @if($esPostulanteActivo)
                    <a href="{{ route('estudiante.mi_grupo') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('estudiante.mi_grupo') ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">Visualizar Mi Grupo</a>
                    <a href="{{ route('estudiante.mis_materias') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('estudiante.mis_materias') ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">Visualizar Mis Materias</a>
                    @endif
                    @if($isDocenteAprobado)
                    <a href="{{ route('docente.mis_grupos') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('docente.mis_grupos') ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">Visualizar Mis Grupos</a>
                    <a href="{{ route('docente.mi_horario') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('docente.mi_horario') ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">Ver mi Horario y Aulas</a>
                    <a href="{{ route('docente.cronograma') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('docente.cronograma') ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">Cronograma Exámenes</a>
                    @endif
                </div>
            </div>
            @endif

            <!-- 5. Evaluaciones y Resultados -->
            @if($showEvaluaciones)
            <div x-data="{ open: {{ request()->routeIs('admin.evaluaciones.*', 'estudiante.mis_examenes', 'docente.calificaciones') ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="if(collapsed) { collapsed = false; open = true; } else { open = !open; }" class="w-full group flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg hover:bg-gray-50 hover:text-indigo-600 {{ request()->routeIs('admin.evaluaciones.*', 'estudiante.mis_examenes', 'docente.calificaciones') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700' }}">
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('admin.evaluaciones.*', 'estudiante.mis_examenes', 'docente.calificaciones') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span x-show="!collapsed || sidebarOpen" class="text-left font-semibold">Evaluaciones y Resultados</span>
                    </div>
                    <svg x-show="!collapsed || sidebarOpen" :class="{'rotate-180': open}" class="ml-2 h-5 w-5 transform transition-transform text-gray-400 group-hover:text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open && !collapsed" class="pl-11 space-y-1 mt-1 border-l-2 border-indigo-100 ml-5">
                    @if($isAdmin || $isCoordinador)
                    <a href="{{ route('admin.evaluaciones.index') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.evaluaciones.*') ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">Consulta de Notas / Admisión</a>
                    @endif
                    @if($esPostulanteActivo)
                    <a href="{{ route('estudiante.mis_examenes') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('estudiante.mis_examenes') ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">Visualizar Exámenes y Notas</a>
                    @endif
                    @if($isDocenteAprobado)
                    <a href="{{ route('docente.calificaciones') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('docente.calificaciones') ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">Registrar notas de examen</a>
                    @endif
                </div>
            </div>
            @endif

            <!-- 6. Reportes y Auditoría -->
            @if($showReportes)
            <div x-data="{ open: {{ request()->routeIs('admin.reportes.*', 'admin.bitacora.*') ? 'true' : 'false' }} }" class="space-y-1">
                <button @click="if(collapsed) { collapsed = false; open = true; } else { open = !open; }" class="w-full group flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg hover:bg-gray-50 hover:text-indigo-600 {{ request()->routeIs('admin.reportes.*', 'admin.bitacora.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700' }}">
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('admin.reportes.*', 'admin.bitacora.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span x-show="!collapsed || sidebarOpen" class="text-left font-semibold">Reportes y Auditoría</span>
                    </div>
                    <svg x-show="!collapsed || sidebarOpen" :class="{'rotate-180': open}" class="ml-2 h-5 w-5 transform transition-transform text-gray-400 group-hover:text-indigo-600" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
                <div x-show="open && !collapsed" class="pl-11 space-y-1 mt-1 border-l-2 border-indigo-100 ml-5">
                    <a href="{{ route('admin.reportes.index') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.reportes.*') ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">Generar Reportes</a>
                    @if($isAdmin)
                    <a href="{{ route('admin.bitacora.index') }}" class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.bitacora.*') ? 'text-indigo-700 font-bold' : 'text-gray-600 hover:text-indigo-600 hover:bg-gray-50' }}">Consultar Bitácora</a>
                    @endif
                </div>
            </div>
            @endif

        </nav>

        <!-- SECCIÓN: USUARIO / AUTENTICACIÓN -->
        <div class="px-3 pb-2">
            <div class="border-t border-gray-200 pt-4">


                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="group flex w-full items-center px-3 py-2 text-sm font-medium rounded-lg text-red-600 hover:bg-red-50">
                        <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 text-red-500 group-hover:text-red-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span x-show="!collapsed || sidebarOpen">Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
