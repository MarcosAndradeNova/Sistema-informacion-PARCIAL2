<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center">
                <svg class="w-8 h-8 mr-3 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                {{ __('Mi Grupo Asignado') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Banner Principal -->
            <div class="relative rounded-2xl overflow-hidden shadow-lg bg-gradient-to-r from-blue-600 to-indigo-800 text-white">
                <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                <div class="relative p-8 md:p-10 flex flex-col md:flex-row items-center justify-between">
                    <div>
                        <p class="text-blue-100 font-semibold tracking-wider uppercase text-sm mb-1">Clasificación Universitaria</p>
                        <h3 class="text-4xl font-extrabold tracking-tight mb-2">{{ $grupo->nombre }}</h3>
                        <p class="text-indigo-100 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            Capacidad máxima: <span class="font-bold ml-1">{{ $grupo->capacidad }} estudiantes</span>
                        </p>
                    </div>
                    <div class="mt-6 md:mt-0">
                        <div class="bg-white/20 p-4 rounded-xl backdrop-blur-md border border-white/30 text-center">
                            <p class="text-3xl font-black">{{ $companeros->count() + 1 }}</p>
                            <p class="text-xs uppercase tracking-wider text-blue-100 font-medium">Inscritos actuales</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de Compañeros -->
            <div>
                <h4 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    Compañeros de Aula
                    <span class="ml-3 text-sm font-medium bg-indigo-100 text-indigo-700 py-1 px-3 rounded-full">{{ $companeros->count() }} acompañantes</span>
                </h4>
                
                @if($companeros->isEmpty())
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center flex flex-col items-center justify-center">
                        <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-4 border-4 border-white shadow-inner">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-1">¡Eres el primero!</h3>
                        <p class="text-gray-500 max-w-md mx-auto">Aún no hay otros postulantes asignados a este grupo. A medida que completen su inscripción, aparecerán aquí.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach($companeros as $companero)
                            <div class="group bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-xl hover:border-indigo-100 transition-all duration-300 transform hover:-translate-y-1">
                                <div class="flex flex-col items-center text-center">
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-100 to-blue-50 flex items-center justify-center text-indigo-600 font-black text-2xl mb-4 group-hover:scale-110 transition-transform shadow-inner">
                                        {{ substr($companero->usuario->nombre ?? 'U', 0, 1) }}
                                    </div>
                                    <h5 class="font-bold text-gray-900 mb-1 capitalize line-clamp-1 w-full" title="{{ strtolower($companero->usuario->nombre ?? 'Usuario') }} {{ strtolower($companero->usuario->apellido_pat ?? '') }}">
                                        {{ strtolower($companero->usuario->nombre ?? 'Usuario') }} <br>
                                        <span class="text-gray-600 font-medium">{{ strtolower($companero->usuario->apellido_pat ?? '') }}</span>
                                    </h5>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 mt-2">
                                        Estudiante
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
