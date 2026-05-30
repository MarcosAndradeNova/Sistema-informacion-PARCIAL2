<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reportes Administrativos') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen" x-data="{ activeTab: 'resumen' }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Navegación de Pestañas -->
            <div class="bg-white rounded-t-xl shadow-sm border-b border-gray-200 overflow-x-auto">
                <nav class="flex space-x-1 p-2" aria-label="Tabs">
                    <button @click="activeTab = 'resumen'" :class="{'bg-indigo-100 text-indigo-700': activeTab === 'resumen', 'text-gray-500 hover:text-gray-700 hover:bg-gray-50': activeTab !== 'resumen'}" class="px-4 py-2 rounded-lg font-medium text-sm transition-colors whitespace-nowrap">Resumen</button>
                    <button @click="activeTab = 'general'" :class="{'bg-indigo-100 text-indigo-700': activeTab === 'general', 'text-gray-500 hover:text-gray-700 hover:bg-gray-50': activeTab !== 'general'}" class="px-4 py-2 rounded-lg font-medium text-sm transition-colors whitespace-nowrap">Lista General</button>
                    <button @click="activeTab = 'aprobados'" :class="{'bg-green-100 text-green-700': activeTab === 'aprobados', 'text-gray-500 hover:text-gray-700 hover:bg-gray-50': activeTab !== 'aprobados'}" class="px-4 py-2 rounded-lg font-medium text-sm transition-colors whitespace-nowrap">Aprobados</button>
                    <button @click="activeTab = 'reprobados'" :class="{'bg-red-100 text-red-700': activeTab === 'reprobados', 'text-gray-500 hover:text-gray-700 hover:bg-gray-50': activeTab !== 'reprobados'}" class="px-4 py-2 rounded-lg font-medium text-sm transition-colors whitespace-nowrap">Reprobados</button>
                    <button @click="activeTab = 'promedios'" :class="{'bg-blue-100 text-blue-700': activeTab === 'promedios', 'text-gray-500 hover:text-gray-700 hover:bg-gray-50': activeTab !== 'promedios'}" class="px-4 py-2 rounded-lg font-medium text-sm transition-colors whitespace-nowrap">Promedios</button>
                    <button @click="activeTab = 'estadisticas'" :class="{'bg-purple-100 text-purple-700': activeTab === 'estadisticas', 'text-gray-500 hover:text-gray-700 hover:bg-gray-50': activeTab !== 'estadisticas'}" class="px-4 py-2 rounded-lg font-medium text-sm transition-colors whitespace-nowrap">Estadísticas Materias</button>
                    <button @click="activeTab = 'grupos'" :class="{'bg-yellow-100 text-yellow-700': activeTab === 'grupos', 'text-gray-500 hover:text-gray-700 hover:bg-gray-50': activeTab !== 'grupos'}" class="px-4 py-2 rounded-lg font-medium text-sm transition-colors whitespace-nowrap">Ranking Grupos</button>
                </nav>
            </div>

            <!-- Contenido de las Pestañas -->
            <div class="bg-white rounded-b-xl shadow-sm p-6 min-h-[500px]">

                <!-- 1. Resumen -->
                <div x-show="activeTab === 'resumen'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100">
                    <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">Avisos y Reportes Generales</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="bg-indigo-50 rounded-xl p-5 border border-indigo-100">
                            <p class="text-sm text-indigo-500 font-semibold uppercase">Total Postulantes</p>
                            <p class="text-4xl font-bold text-indigo-700 mt-2">{{ $postulantesGenerales->count() }}</p>
                        </div>
                        <div class="bg-green-50 rounded-xl p-5 border border-green-100">
                            <p class="text-sm text-green-500 font-semibold uppercase">Total Aprobados</p>
                            <p class="text-4xl font-bold text-green-700 mt-2">{{ $postulantesAprobados->count() }}</p>
                            <p class="text-xs text-green-600 mt-1">Más de 60 en 4 materias</p>
                        </div>
                        <div class="bg-red-50 rounded-xl p-5 border border-red-100">
                            <p class="text-sm text-red-500 font-semibold uppercase">Total Reprobados</p>
                            <p class="text-4xl font-bold text-red-700 mt-2">{{ $postulantesReprobados->count() }}</p>
                        </div>
                        <div class="bg-yellow-50 rounded-xl p-5 border border-yellow-100">
                            <p class="text-sm text-yellow-600 font-semibold uppercase">Grupos Habilitados</p>
                            <p class="text-4xl font-bold text-yellow-700 mt-2">{{ $cantidadGrupos }}</p>
                        </div>
                    </div>
                </div>

                <!-- 2. Lista General -->
                <div x-show="activeTab === 'general'" x-cloak>
                    <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2">Lista General de Postulantes</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">CI</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grupo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado Admisión</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($postulantesGenerales as $p)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $p->ci_usuario }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $p->usuario->nombre }} {{ $p->usuario->apellido_pat }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $p->grupo ? $p->grupo->nombre : 'Sin asignar' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            {{ str_replace('_', ' ', $p->estado_admision) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 3. Aprobados -->
                <div x-show="activeTab === 'aprobados'" x-cloak>
                    <h3 class="text-xl font-bold text-green-700 mb-6 border-b pb-2 border-green-200">Postulantes Aprobados</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-green-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase">CI</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-green-700 uppercase">Materias Aprobadas (>=60)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($postulantesAprobados as $p)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $p->ci_usuario }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $p->usuario->nombre }} {{ $p->usuario->apellido_pat }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">{{ $p->materias_aprobadas_count }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 4. Reprobados -->
                <div x-show="activeTab === 'reprobados'" x-cloak>
                    <h3 class="text-xl font-bold text-red-700 mb-6 border-b pb-2 border-red-200">Postulantes Reprobados</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-red-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-red-700 uppercase">CI</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-red-700 uppercase">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-red-700 uppercase">Materias Aprobadas (>=60)</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($postulantesReprobados as $p)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $p->ci_usuario }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $p->usuario->nombre }} {{ $p->usuario->apellido_pat }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-red-600">{{ $p->materias_aprobadas_count }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 5. Promedios Generales -->
                <div x-show="activeTab === 'promedios'" x-cloak>
                    <h3 class="text-xl font-bold text-blue-700 mb-6 border-b pb-2 border-blue-200">Promedios Generales</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-blue-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase">CI</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase">Nombre</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-blue-700 uppercase">Promedio Final</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($promediosGenerales as $p)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $p->ci_usuario }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $p->usuario->nombre }} {{ $p->usuario->apellido_pat }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $p->promedio_general >= 60 ? 'text-green-600' : 'text-red-600' }}">{{ $p->promedio_general }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 6. Estadísticas por Materia -->
                <div x-show="activeTab === 'estadisticas'" x-cloak>
                    <h3 class="text-xl font-bold text-purple-700 mb-6 border-b pb-2 border-purple-200">Estadísticas por Materia</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($estadisticasMateria as $est)
                        <div class="bg-white border rounded-xl shadow-sm p-5">
                            <h4 class="text-lg font-bold text-gray-800 mb-4">{{ $est->materia }}</h4>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Evaluados:</span>
                                    <span class="font-medium text-gray-900">{{ $est->total_evaluados }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Promedio General:</span>
                                    <span class="font-medium text-gray-900">{{ round($est->promedio_general, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Aprobados:</span>
                                    <span class="font-bold text-green-600">{{ $est->total_aprobados }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-gray-500">Reprobados:</span>
                                    <span class="font-bold text-red-600">{{ $est->total_reprobados }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- 7. Grupos y Docentes / Mejores Grupos -->
                <div x-show="activeTab === 'grupos'" x-cloak>
                    <h3 class="text-xl font-bold text-yellow-700 mb-6 border-b pb-2 border-yellow-200">Ranking de Grupos con Mayor Cantidad de Aprobados</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        @foreach($gruposRanking as $index => $grupo)
                        <div class="bg-white border-2 {{ $index === 0 ? 'border-yellow-400 shadow-yellow-100' : 'border-gray-100' }} rounded-xl shadow-sm p-6 relative overflow-hidden">
                            @if($index === 0)
                            <div class="absolute top-0 right-0 bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1 rounded-bl-lg">
                                1° LUGAR
                            </div>
                            @endif
                            
                            <h4 class="text-xl font-bold text-gray-800 mb-2">{{ $grupo->nombre }}</h4>
                            <div class="flex items-center text-gray-600 mb-4">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="font-bold text-lg">{{ $grupo->cantidad_aprobados }}</span> <span class="ml-1">aprobados</span>
                            </div>
                            
                            <div class="text-sm text-gray-500 border-t pt-3 mt-3">
                                Total asignados: {{ $grupo->postulantes_count }} estudiantes
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2 mt-12">Docentes por Grupos</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Grupo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Materia</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Docente</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($docentesPorGrupo as $grupo)
                                    @foreach($grupo->docentes as $docente)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $grupo->nombre }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $docente->pivot->materia }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $docente->nombre }} {{ $docente->apellido_pat }}</td>
                                    </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No hay docentes asignados a los grupos todavía.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Agregar estilos para x-cloak (AlpineJS) -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
