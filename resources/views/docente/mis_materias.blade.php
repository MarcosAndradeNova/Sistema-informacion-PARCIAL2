<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight border-l-4 border-blue-800 pl-3">
            {{ __('Gestión de Materias y Contenido Académico') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-600 text-green-800 p-4 shadow-sm mb-6" role="alert">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @forelse($materias as $materia)
                <div class="bg-white shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 bg-slate-800 flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-white uppercase tracking-wide">{{ $materia->nombre }}</h3>
                        <span class="bg-slate-700 text-gray-100 text-xs font-medium px-3 py-1 border border-slate-600 shadow-sm">{{ $materia->puntos }} PUNTOS</span>
                    </div>

                    <form action="{{ route('docente.mis_materias.update', $materia->id) }}" method="POST" class="p-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Temario de Avance -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2 uppercase tracking-wide">Programa Analítico (Temario)</label>
                                <textarea name="temario_avance" rows="8" class="focus:ring-blue-800 focus:border-blue-800 block w-full sm:text-sm border-gray-300 p-3 shadow-inner bg-gray-50" placeholder="Especifique el contenido analítico a evaluar.">{{ $materia->temario_avance }}</textarea>
                                <p class="mt-2 text-xs text-gray-500">Documente de manera formal los temas y unidades de competencia para esta asignatura.</p>
                            </div>

                            <!-- Enlaces a Materiales -->
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2 uppercase tracking-wide">Repositorio de Material Didáctico</label>
                                <textarea name="enlaces_material" rows="8" class="focus:ring-blue-800 focus:border-blue-800 block w-full sm:text-sm border-gray-300 p-3 shadow-inner bg-gray-50" placeholder="Ingrese las URLs correspondientes a bibliografía, repositorios o documentos oficiales.">{{ $materia->enlaces_material }}</textarea>
                                <p class="mt-2 text-xs text-gray-500">Proporcione los enlaces a recursos en formato PDF o carpetas institucionales compartidas.</p>
                            </div>
                        </div>

                        <div class="mt-8 pt-5 border-t border-gray-200 flex justify-end">
                            <button type="submit" class="bg-blue-800 hover:bg-blue-900 text-white font-medium py-2 px-6 shadow-sm border border-transparent focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-800 transition-colors">
                                Actualizar Contenido Académico
                            </button>
                        </div>
                    </form>
                </div>
            @empty
                <div class="bg-white border border-gray-200 p-10 text-center shadow-sm">
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
