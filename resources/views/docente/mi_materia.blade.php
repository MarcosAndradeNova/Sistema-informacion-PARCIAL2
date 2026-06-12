<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight border-l-4 border-blue-800 pl-3">
            {{ __('Mi Materia y Contenido Académico Oficial') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-600 text-green-800 p-4 shadow-sm" role="alert">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @forelse($materias as $materia)
                @php
                    // Determinar los archivos pdf según el nombre de la materia
                    $nombreMateria = mb_strtolower($materia->nombre, 'UTF-8');
                    $archivoTemario = null;
                    $archivoBanco = null;
                    
                    if (str_contains($nombreMateria, 'matem')) {
                        $archivoTemario = 'temario_matematicas.pdf';
                        $archivoBanco = 'matematicas.pdf';
                    } elseif (str_contains($nombreMateria, 'computaci')) {
                        $archivoTemario = 'temario_computacion.pdf';
                        $archivoBanco = 'computacion.pdf';
                    } elseif (str_contains($nombreMateria, 'ingl')) {
                        $archivoTemario = 'temario_ingles.pdf';
                        $archivoBanco = 'ingles.pdf';
                    } elseif (str_contains($nombreMateria, 'físic') || str_contains($nombreMateria, 'fisic')) {
                        $archivoTemario = 'temario_fisica.pdf';
                        $archivoBanco = 'fisica.pdf';
                    }
                @endphp

                <div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-6 py-5 border-b border-gray-200 bg-slate-800 flex justify-between items-center">
                        <h3 class="text-xl font-bold text-white uppercase tracking-wide">{{ $materia->nombre }}</h3>
                        <span class="bg-slate-700 text-gray-100 text-sm font-bold px-3 py-1 border border-slate-600 rounded-md shadow-sm">{{ $materia->puntos }} PUNTOS</span>
                    </div>

                    <div class="p-8">
                        <div class="mb-8">
                            <h4 class="text-lg font-bold text-slate-800 mb-2 border-b pb-2">Plan de Estudios Oficial</h4>
                            <p class="text-sm text-gray-600 mb-6">A continuación se encuentran los documentos oficiales con los temas que deberá impartir en sus grupos asignados. Es obligatorio cubrir la totalidad del temario durante el periodo académico.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Tarjeta de Temario -->
                                <div class="border border-indigo-200 bg-indigo-50 rounded-lg p-6 flex flex-col items-center text-center shadow-sm hover:shadow-md transition-shadow">
                                    <div class="w-16 h-16 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                    </div>
                                    <h5 class="font-bold text-indigo-900 mb-1">Programa Analítico (Temario)</h5>
                                    <p class="text-xs text-indigo-700 mb-4 h-10">Contiene la estructura de temas, unidades y competencias a desarrollar en clase.</p>
                                    
                                    @if($archivoTemario)
                                        <a href="{{ asset('temarios/' . $archivoTemario) }}" target="_blank" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 transition ease-in-out duration-150">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            Descargar PDF
                                        </a>
                                    @else
                                        <button disabled class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed">
                                            No Disponible Aún
                                        </button>
                                    @endif
                                </div>

                                <!-- Tarjeta de Banco de Preguntas -->
                                <div class="border border-blue-200 bg-blue-50 rounded-lg p-6 flex flex-col items-center text-center shadow-sm hover:shadow-md transition-shadow">
                                    <div class="w-16 h-16 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <h5 class="font-bold text-blue-900 mb-1">Banco de Preguntas</h5>
                                    <p class="text-xs text-blue-700 mb-4 h-10">Material de apoyo oficial para preparar a los estudiantes de cara al examen.</p>
                                    
                                    @if($archivoBanco)
                                        <a href="{{ asset('bancos_preguntas/' . $archivoBanco) }}" target="_blank" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 transition ease-in-out duration-150">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            Descargar PDF
                                        </a>
                                    @else
                                        <button disabled class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest cursor-not-allowed">
                                            No Disponible Aún
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Formulario para Material del Docente -->
                        <div class="mt-8">
                            <h4 class="text-lg font-bold text-slate-800 mb-4 border-b pb-2">Material Didáctico Propio (Opcional)</h4>
                            <form action="{{ route('docente.mi_materia.update', $materia->id) }}" method="POST">
                                @csrf
                                <div class="bg-gray-50 border border-gray-200 p-6 rounded-lg">
                                    <label class="block text-sm font-semibold text-slate-700 mb-2 uppercase tracking-wide">Enlace a su Repositorio / Drive</label>
                                    <input type="url" name="enlaces_material" value="{{ $materia->enlaces_material }}" class="focus:ring-blue-800 focus:border-blue-800 block w-full sm:text-sm border-gray-300 rounded-md p-3 shadow-sm" placeholder="https://drive.google.com/drive/folders/...">
                                    <p class="mt-2 text-xs text-gray-500">Si tiene material adicional, bibliografía o diapositivas que desea compartir con sus estudiantes, pegue el enlace aquí.</p>
                                    
                                    <div class="mt-4 flex justify-end">
                                        <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white font-medium py-2 px-6 rounded-md shadow-sm border border-transparent focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-800 transition-colors">
                                            Guardar Enlace
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            @empty
                <div class="bg-white border border-gray-200 p-10 text-center shadow-sm rounded-lg">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Sin asignaciones registradas</h3>
                    <p class="mt-2 text-sm text-gray-500">Actualmente no consta como docente titular de ninguna asignatura en los registros académicos.</p>
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>
