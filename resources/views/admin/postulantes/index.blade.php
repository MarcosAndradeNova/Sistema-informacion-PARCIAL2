<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight flex items-center">
            <svg class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            {{ Auth::user()->role === 'coordinador' ? __('Consulta de Admisión') : __('Registrar Postulante') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if (session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-50 border-l-4 border-green-500 flex items-center shadow-sm">
                    <svg class="h-6 w-6 text-green-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 rounded-xl bg-red-50 border-l-4 border-red-500 flex items-center shadow-sm">
                    <svg class="h-6 w-6 text-red-500 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <div class="p-6 bg-white border-b border-gray-200 flex flex-col md:flex-row md:items-center justify-between">
                    <div>
                        <h3 class="text-lg leading-6 font-bold text-gray-900">Lista de Documentos a Verificar</h3>
                        <p class="mt-1 text-sm text-gray-500">Administra los estados de admisión de cada estudiante.</p>
                    </div>
                    <div class="mt-4 md:mt-0 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" id="searchInput" class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md" placeholder="Buscar por CI o Apellido...">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="postulantesTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Carnet / Titulo</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Postulante</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($postulantes as $p)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $p->ciusuario }}</div>
                                        <div class="text-sm text-gray-500">Tít: {{ $p->titulobachiller ? 'Sí' : 'No' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $p->apellidopat }} {{ $p->apellidomat }} {{ $p->nombre }}</div>
                                        <div class="text-sm text-gray-500">{{ $p->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($p->estadodocum == 'PENDIENTE')
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pendiente de Revisión
                                            </span>
                                        @elseif($p->estadodocum == 'APROBADO')
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Documentos Aprobados (Falta Pago)
                                            </span>
                                        @elseif($p->estadodocum == 'RECHAZADO')
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Observado
                                            </span>
                                        @else
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ str_replace('_', ' ', $p->estadodocum) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        @if($p->estadodocum == 'PENDIENTE' || $p->estadodocum == 'RECHAZADO')
                                            @if(Auth::user()->role === 'admin')
                                            <div class="flex justify-end space-x-2">
                                                <!-- Botón Aprobar -->
                                                <form action="{{ route('admin.postulantes.aprobar', $p->ciusuario) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none" onclick="return confirm('¿Confirmar que el estudiante entregó TODOS los documentos físicos?')">
                                                        Aprobar
                                                    </button>
                                                </form>

                                                <!-- Botón Observar (Abre Modal) -->
                                                <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'rechazar-{{ $p->ciusuario }}')" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none">
                                                    Observar
                                                </button>
                                            </div>
                                            @else
                                            <span class="text-gray-400 italic text-xs">Solo Admin</span>
                                            @endif

                                            <!-- Modal de Rechazo -->
                                            <x-modal name="rechazar-{{ $p->ciusuario }}" focusable>
                                                <form method="post" action="{{ route('admin.postulantes.rechazar', $p->ciusuario) }}" class="p-6 text-left">
                                                    @csrf
                                                    <h2 class="text-lg font-bold text-gray-900 mb-4">
                                                        Observar Documentación
                                                    </h2>
                                                    <p class="text-sm text-gray-600 mb-4">
                                                        Escribe el motivo por el cual los documentos no fueron aceptados. El estudiante verá este mensaje.
                                                    </p>
                                                    <div class="mt-2">
                                                        <textarea name="observaciones" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500" placeholder="Ej. Falta la firma en la fotocopia del CI..." required></textarea>
                                                    </div>
                                                    <div class="mt-6 flex justify-end">
                                                        <button type="button" x-on:click="$dispatch('close')" class="mr-3 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                                            Cancelar
                                                        </button>
                                                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-bold rounded-md text-white bg-red-600 hover:bg-red-700">
                                                            Guardar Observación
                                                        </button>
                                                    </div>
                                                </form>
                                            </x-modal>
                                        @elseif($p->estadodocum == 'APROBADO' && Auth::user()->role === 'admin')
                                            <form action="{{ route('admin.postulantes.enviar_pago', $p->ciusuario) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-bold rounded shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none" onclick="return confirm('¿Desea generar y enviar el enlace de pago al correo del postulante?')">
                                                    <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                                    Enviar Enlace Pago
                                                </button>
                                            </form>
                                        @elseif($p->estadodocum == 'INSCRITO')
                                            <span class="text-gray-500 text-xs italic">Inscrito</span>
                                        @else
                                            <span class="text-gray-400 italic text-xs">Acción completada</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            @if(count($postulantes) == 0)
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                        No hay postulantes registrados en este momento.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Script sencillo para el buscador en tiempo real -->
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#postulantesTable tbody tr');
            
            rows.forEach(row => {
                let text = row.innerText.toLowerCase();
                if(text.includes(filter)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</x-app-layout>
