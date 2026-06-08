<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestión de Docentes') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-bold text-gray-900">Listado de Docentes Registrados</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Docente</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Contacto</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Materia Asignada</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($docentes as $docente)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-700 font-bold">
                                                {{ substr($docente->nombre, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 capitalize">{{ strtolower($docente->nombre) }} {{ strtolower($docente->apellido_pat) }}</div>
                                                <div class="text-sm text-gray-500">CI: {{ $docente->ci }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $docente->email }}</div>
                                        <div class="text-sm text-gray-500">{{ $docente->telefono ?? 'Sin teléfono' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($docente->estado_aprobacion === 'APROBADO')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Aprobado</span>
                                        @elseif($docente->estado_aprobacion === 'RECHAZADO')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rechazado</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @php
                                            $materiaAsignada = \App\Models\Materia::where('docente_ci', $docente->ci)->first();
                                        @endphp
                                        
                                        @if($materiaAsignada)
                                            <span class="font-semibold text-indigo-600">{{ $materiaAsignada->nombre }}</span>
                                        @else
                                            <span class="text-gray-400 italic">Ninguna</span>
                                        @endif
                                        
                                        @if($docente->estado_aprobacion === 'APROBADO')
                                        <form action="{{ route('admin.docentes.asignar_materia', $docente->ci) }}" method="POST" class="mt-2 flex space-x-2">
                                            @csrf
                                            <select name="materia_id" class="text-xs border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <option value="">Seleccionar materia...</option>
                                                @foreach($materias as $m)
                                                    <option value="{{ $m->id }}" {{ $materiaAsignada && $materiaAsignada->id == $m->id ? 'selected' : '' }}>{{ $m->nombre }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="text-xs bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-1 px-2 rounded">
                                                Asignar
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if($docente->estado_aprobacion !== 'APROBADO')
                                            <form action="{{ route('admin.docentes.aprobar', $docente->ci) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900 mr-3 font-bold">Aprobar</button>
                                            </form>
                                        @endif
                                        @if($docente->estado_aprobacion !== 'RECHAZADO')
                                            <form action="{{ route('admin.docentes.rechazar', $docente->ci) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-bold">Rechazar</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                        No hay docentes registrados en el sistema.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
