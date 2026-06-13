<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Bitácora de Movimientos del Sistema') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <div class="flex justify-between items-center mb-6">
                        <p class="text-gray-600">Registro histórico de todas las acciones importantes realizadas por los usuarios en la plataforma.</p>
                        <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 rounded border border-indigo-200">
                            {{ $bitacoras->total() }} Registros
                        </span>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Fecha y Hora</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Usuario</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Rol</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Acción Realizada</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Dirección IP</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($bitacoras as $bitacora)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($bitacora->fecha)->format('d/m/Y') }}</div>
                                            <div class="text-xs text-gray-500 flex items-center mt-1">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ \Carbon\Carbon::parse($bitacora->hora)->format('H:i:s') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-bold text-indigo-600 capitalize">
                                                {{ $bitacora->usuario ? strtolower($bitacora->usuario->nombre . ' ' . $bitacora->usuario->apellidopat) : 'Usuario Desconocido' }}
                                            </div>
                                            <div class="text-xs text-gray-500">CI: {{ $bitacora->ciusuario }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $tipo = $bitacora->usuario ? $bitacora->usuario->tipo : 'X';
                                                $rolColor = 'bg-gray-100 text-gray-800';
                                                $rolTexto = 'Desconocido';
                                                
                                                if($tipo === 'A') { $rolColor = 'bg-red-100 text-red-800'; $rolTexto = 'Administrador'; }
                                                elseif($tipo === 'D') { $rolColor = 'bg-blue-100 text-blue-800'; $rolTexto = 'Docente'; }
                                                elseif($tipo === 'E') { $rolColor = 'bg-green-100 text-green-800'; $rolTexto = 'Estudiante'; }
                                            @endphp
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $rolColor }}">
                                                {{ $rolTexto }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $bitacora->accion }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono text-xs">
                                            {{ $bitacora->ip }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            No hay registros en la bitácora todavía.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $bitacoras->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
