<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center">
                <svg class="w-8 h-8 mr-3 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                {{ __('Mis Materias y Temario') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="text-center max-w-2xl mx-auto mb-10">
                <p class="text-gray-500 text-lg">Prepárate para tu prueba de admisión. Aquí encontrarás las materias que serán evaluadas y el puntaje que cada una aporta a tu calificación final.</p>
            </div>

            @if($materias->isEmpty())
                <div class="bg-white rounded-2xl p-12 text-center shadow-sm border border-gray-100 flex flex-col items-center">
                    <div class="w-24 h-24 bg-yellow-50 text-yellow-500 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Aún no hay materias registradas</h3>
                    <p class="text-gray-500">El departamento académico no ha publicado la malla curricular de admisión todavía.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">
                    @foreach($materias as $index => $materia)
                        <div class="group bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 flex flex-col h-full transform hover:-translate-y-2">
                            <!-- Cabecera de Tarjeta -->
                            <div class="relative h-32 bg-gradient-to-r {{ $index % 2 == 0 ? 'from-blue-600 to-indigo-700' : 'from-red-500 to-red-700' }} p-6 flex flex-col justify-between overflow-hidden">
                                <div class="absolute -right-4 -top-4 opacity-20">
                                    <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                                </div>
                                <div class="relative z-10 flex justify-between items-start">
                                    <span class="bg-white/20 text-white backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border border-white/30">Materia {{ $index + 1 }}</span>
                                    <span class="bg-white text-gray-900 px-3 py-1 rounded-full text-sm font-black shadow-sm">{{ $materia->puntos ?? 'N/A' }} pts</span>
                                </div>
                                <h3 class="relative z-10 text-2xl font-extrabold text-white uppercase tracking-tight mt-2 line-clamp-1" title="{{ $materia->nombre }}">{{ $materia->nombre }}</h3>
                            </div>
                            
                            <!-- Cuerpo (Temario) -->
                            <div class="p-6 flex-1 flex flex-col bg-white">
                                <h4 class="font-bold text-gray-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    Temario Evaluativo
                                </h4>
                                <ul class="space-y-3 flex-1">
                                    <li class="flex items-start">
                                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mr-3 mt-0.5">
                                            <span class="text-xs font-bold">1</span>
                                        </div>
                                        <p class="text-sm text-gray-600">Introducción y conceptos básicos de la materia.</p>
                                    </li>
                                    <li class="flex items-start">
                                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mr-3 mt-0.5">
                                            <span class="text-xs font-bold">2</span>
                                        </div>
                                        <p class="text-sm text-gray-600">Desarrollo y aplicaciones fundamentales orientadas a la facultad.</p>
                                    </li>
                                    <li class="flex items-start">
                                        <div class="flex-shrink-0 w-6 h-6 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center mr-3 mt-0.5">
                                            <span class="text-xs font-bold">3</span>
                                        </div>
                                        <p class="text-sm text-gray-600">Resolución de problemas de razonamiento lógico.</p>
                                    </li>
                                </ul>
                                
                                <div class="mt-6 pt-4 border-t border-gray-100">
                                    @php
                                        $nombreMateria = mb_strtolower($materia->nombre, 'UTF-8');
                                        $archivoPdf = null;
                                        if (str_contains($nombreMateria, 'matem')) {
                                            $archivoPdf = 'matematicas.pdf';
                                        } elseif (str_contains($nombreMateria, 'computaci')) {
                                            $archivoPdf = 'computacion.pdf';
                                        } elseif (str_contains($nombreMateria, 'ingl')) {
                                            $archivoPdf = 'ingles.pdf';
                                        } elseif (str_contains($nombreMateria, 'físic') || str_contains($nombreMateria, 'fisic')) {
                                            $archivoPdf = 'fisica.pdf';
                                        }
                                    @endphp

                                    @if($archivoPdf)
                                        <a href="{{ asset('bancos_preguntas/' . $archivoPdf) }}" target="_blank" class="w-full py-2.5 rounded-xl bg-blue-50 text-blue-600 font-semibold text-sm hover:bg-blue-100 transition flex items-center justify-center">
                                            Descargar Banco de Preguntas (PDF)
                                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        </a>
                                    @else
                                        <button class="w-full py-2.5 rounded-xl bg-gray-50 text-gray-400 font-semibold text-sm cursor-not-allowed flex items-center justify-center" disabled title="Aún no disponible">
                                            Banco de preguntas no disponible
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
