<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight border-l-4 border-indigo-600 pl-3">
            {{ __('Gestionar Roles y Permisos') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-600 text-green-800 p-4 shadow-sm" role="alert">
                    <p class="font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-600 text-red-800 p-4 shadow-sm" role="alert">
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-base font-bold text-gray-800 uppercase tracking-wide">
                        Usuarios del Sistema
                    </h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-indigo-50 text-indigo-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Correo Electrónico</th>
                                <th class="px-6 py-3 text-left text-xs font-bold uppercase tracking-wider">Rol Actual</th>
                                <th class="px-6 py-3 text-center text-xs font-bold uppercase tracking-wider">Cambiar Rol</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($users as $user)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-gray-900">{{ $user->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $roleColors = [
                                                'admin' => 'bg-purple-100 text-purple-800 border-purple-200',
                                                'docente' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                'coordinador' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                'estudiante' => 'bg-green-100 text-green-800 border-green-200',
                                            ];
                                            $colorClass = $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                        @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $colorClass }} uppercase">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <form action="{{ route('admin.roles.update', $user->id) }}" method="POST" class="flex items-center justify-center gap-2">
                                            @csrf
                                            <select name="role" class="text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500">
                                                @foreach($roles_db as $rol_db)
                                                    @php
                                                        $map = ['Postulante' => 'estudiante', 'Admin' => 'admin'];
                                                        $slug = $map[$rol_db->descripcion] ?? strtolower(str_replace(' ', '_', $rol_db->descripcion));
                                                    @endphp
                                                    <option value="{{ $slug }}" {{ $user->role === $slug ? 'selected' : '' }}>
                                                        {{ $rol_db->descripcion }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-1.5 px-3 rounded text-xs shadow-sm transition-colors">
                                                Actualizar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($users->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
