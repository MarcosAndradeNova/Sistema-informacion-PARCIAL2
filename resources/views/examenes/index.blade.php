<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Módulo de Exámenes y Notas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if (session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-6">
                        <form action="{{ route('examenes.index') }}" method="GET" class="flex w-1/2">
                            <input type="text" name="search" placeholder="Buscar postulante para calificar..." value="{{ request('search') }}" class="w-full rounded-l-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-r-md">Buscar</button>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">CI</th>
                                    <th scope="col" class="px-6 py-3">Postulante</th>
                                    <th scope="col" class="px-6 py-3">Materias Evaluadas</th>
                                    <th scope="col" class="px-6 py-3">Estado General</th>
                                    <th scope="col" class="px-6 py-3 text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($postulantes as $post)
                                    @php
                                        // A simple logic to show if they passed everything or not
                                        $evaluadas = $post->calificaciones->count();
                                        $aprobadas = $post->calificaciones->where('estado', 'APROBADO')->count();
                                    @endphp
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ $post->ciusuario }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $post->usuario->nombre ?? '' }} {{ $post->usuario->apellidopat ?? '' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $evaluadas }} / 4
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($evaluadas == 4 && $aprobadas == 4)
                                                <span class="bg-green-100 text-green-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">ADMITIDO</span>
                                            @elseif($evaluadas > 0)
                                                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">EN PROCESO</span>
                                            @else
                                                <span class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">SIN NOTAS</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('examenes.edit', $post->ciusuario) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                                Registrar / Ver Notas
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center">No hay postulantes registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $postulantes->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
