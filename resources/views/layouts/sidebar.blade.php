@php
    $usuario = \App\Models\Usuario::where('email', Auth::user()->email)->first();
    $isAdmin = $usuario && $usuario->tipo === 'A';
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

            <!-- SECCIÓN: POSTULANTES -->
            <div x-show="!collapsed" class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-6 px-3">Postulantes</div>
            
            <a href="{{ route('inscripcion.estado') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('inscripcion.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('inscripcion.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span x-show="!collapsed">Ficha de Postulación</span>
            </a>

            @if($isAdmin)
            <a href="{{ route('admin.postulantes') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.postulantes*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('admin.postulantes*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span x-show="!collapsed">Lista de Postulantes</span>
            </a>
            @endif

            <!-- SECCIÓN: EXÁMENES -->
            @if($isAdmin)
            <div x-show="!collapsed" class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-6 px-3">Académico</div>
            
            <a href="{{ route('examenes.index') }}" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('examenes.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50 hover:text-indigo-600' }}">
                <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 {{ request()->routeIs('examenes.*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span x-show="!collapsed">Exámenes y Notas</span>
            </a>
            @endif

            <!-- SECCIÓN: GRUPOS -->
            @if($isAdmin)
            <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 hover:text-indigo-600">
                <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span x-show="!collapsed">Grupos (Aulas)</span>
            </a>

            <!-- SECCIÓN: REPORTES -->
            <div x-show="!collapsed" class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-6 px-3">Administración</div>
            <a href="#" class="group flex items-center px-3 py-2 text-sm font-medium rounded-lg text-gray-700 hover:bg-gray-50 hover:text-indigo-600">
                <svg class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 text-gray-400 group-hover:text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span x-show="!collapsed">Reportes</span>
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
