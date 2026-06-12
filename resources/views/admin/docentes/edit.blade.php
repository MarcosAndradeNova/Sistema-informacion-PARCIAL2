<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Información de Docente') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.docentes.update', $usuario->ci) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Datos Personales -->
                            <div class="col-span-2 text-lg font-bold text-gray-800 border-b pb-2 mb-2">Datos Personales y de Usuario</div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Carnet de Identidad (CI)</label>
                                <input type="text" value="{{ $usuario->ci }}" disabled class="mt-1 bg-gray-100 border-gray-300 rounded-md shadow-sm w-full text-gray-500 cursor-not-allowed">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nombre(s)</label>
                                <input type="text" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required class="mt-1 border-gray-300 rounded-md shadow-sm w-full focus:ring-indigo-500 focus:border-indigo-500">
                                @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Apellido Paterno</label>
                                <input type="text" name="apellidopat" value="{{ old('apellidopat', $usuario->apellidopat) }}" required class="mt-1 border-gray-300 rounded-md shadow-sm w-full focus:ring-indigo-500 focus:border-indigo-500">
                                @error('apellidopat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Apellido Materno</label>
                                <input type="text" name="apellidomat" value="{{ old('apellidomat', $usuario->apellidomat) }}" class="mt-1 border-gray-300 rounded-md shadow-sm w-full focus:ring-indigo-500 focus:border-indigo-500">
                                @error('apellidomat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                                <input type="email" name="email" value="{{ old('email', $usuario->email) }}" required class="mt-1 border-gray-300 rounded-md shadow-sm w-full focus:ring-indigo-500 focus:border-indigo-500">
                                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nueva Contraseña (Opcional)</label>
                                <input type="text" name="password" placeholder="Dejar en blanco para no cambiar" class="mt-1 border-gray-300 rounded-md shadow-sm w-full focus:ring-indigo-500 focus:border-indigo-500">
                                @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Perfil Profesional -->
                            <div class="col-span-2 text-lg font-bold text-gray-800 border-b pb-2 mt-4 mb-2">Perfil Profesional</div>
                            
                            @if($docente)
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Profesión</label>
                                <input type="text" name="profesion" value="{{ old('profesion', $docente->profesion) }}" required class="mt-1 border-gray-300 rounded-md shadow-sm w-full focus:ring-indigo-500 focus:border-indigo-500">
                                @error('profesion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nivel de Formación</label>
                                <select name="nivelformacion" required class="mt-1 border-gray-300 rounded-md shadow-sm w-full focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="Licenciatura" {{ $docente->nivelformacion == 'Licenciatura' ? 'selected' : '' }}>Licenciatura</option>
                                    <option value="Maestría" {{ $docente->nivelformacion == 'Maestría' ? 'selected' : '' }}>Maestría</option>
                                    <option value="Doctorado" {{ $docente->nivelformacion == 'Doctorado' ? 'selected' : '' }}>Doctorado</option>
                                    <option value="Diplomado" {{ $docente->nivelformacion == 'Diplomado' ? 'selected' : '' }}>Diplomado</option>
                                    <option value="Técnico Superior" {{ $docente->nivelformacion == 'Técnico Superior' ? 'selected' : '' }}>Técnico Superior</option>
                                </select>
                                @error('nivelformacion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Años de Experiencia</label>
                                <input type="number" name="experiencia" value="{{ old('experiencia', $docente->experiencia) }}" required min="0" class="mt-1 border-gray-300 rounded-md shadow-sm w-full focus:ring-indigo-500 focus:border-indigo-500">
                                @error('experiencia') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            @else
                            <div class="col-span-2 text-red-500 bg-red-100 p-4 rounded-md">
                                Este usuario es de tipo Docente, pero aún no ha completado su ficha en la tabla 'docente'.
                                Debes completar su registro desde otro panel o actualizar su estado directamente.
                            </div>
                            @endif

                        </div>

                        <div class="flex justify-end space-x-3 mt-8 pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.docentes.index') }}" class="bg-gray-200 text-gray-700 font-bold py-2 px-6 rounded-md hover:bg-gray-300 transition">Cancelar</a>
                            <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-6 rounded-md hover:bg-indigo-700 transition">Actualizar Docente</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
