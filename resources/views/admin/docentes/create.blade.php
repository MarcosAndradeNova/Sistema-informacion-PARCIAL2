<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Agregar Nuevo Docente') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.docentes.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Datos Personales -->
                            <div class="col-span-2 text-lg font-bold text-gray-800 border-b pb-2 mb-2">Datos Personales y de Usuario</div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Carnet de Identidad (CI)</label>
                                <input type="text" name="ci" value="{{ old('ci') }}" required class="mt-1 border-gray-300 rounded-md shadow-sm w-full focus:ring-indigo-500 focus:border-indigo-500">
                                @error('ci') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nombre(s)</label>
                                <input type="text" name="nombre" value="{{ old('nombre') }}" required class="mt-1 border-gray-300 rounded-md shadow-sm w-full focus:ring-indigo-500 focus:border-indigo-500">
                                @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Apellido Paterno</label>
                                <input type="text" name="apellidopat" value="{{ old('apellidopat') }}" required class="mt-1 border-gray-300 rounded-md shadow-sm w-full focus:ring-indigo-500 focus:border-indigo-500">
                                @error('apellidopat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Apellido Materno (Opcional)</label>
                                <input type="text" name="apellidomat" value="{{ old('apellidomat') }}" class="mt-1 border-gray-300 rounded-md shadow-sm w-full focus:ring-indigo-500 focus:border-indigo-500">
                                @error('apellidomat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                                <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 border-gray-300 rounded-md shadow-sm w-full focus:ring-indigo-500 focus:border-indigo-500">
                                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Contraseña temporal</label>
                                <input type="text" name="password" required class="mt-1 border-gray-300 rounded-md shadow-sm w-full focus:ring-indigo-500 focus:border-indigo-500">
                                <p class="text-xs text-gray-500 mt-1">El docente usará esta contraseña para su primer ingreso.</p>
                                @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Perfil Profesional -->
                            <div class="col-span-2 text-lg font-bold text-gray-800 border-b pb-2 mt-4 mb-2">Perfil Profesional</div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Profesión</label>
                                <input type="text" name="profesion" value="{{ old('profesion') }}" required placeholder="Ej. Ingeniero de Sistemas" class="mt-1 border-gray-300 rounded-md shadow-sm w-full focus:ring-indigo-500 focus:border-indigo-500">
                                @error('profesion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nivel de Formación</label>
                                <select name="nivelformacion" required class="mt-1 border-gray-300 rounded-md shadow-sm w-full focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="Licenciatura">Licenciatura</option>
                                    <option value="Maestría">Maestría</option>
                                    <option value="Doctorado">Doctorado</option>
                                    <option value="Diplomado">Diplomado</option>
                                    <option value="Técnico Superior">Técnico Superior</option>
                                </select>
                                @error('nivelformacion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Años de Experiencia</label>
                                <input type="number" name="experiencia" value="{{ old('experiencia', 0) }}" required min="0" class="mt-1 border-gray-300 rounded-md shadow-sm w-full focus:ring-indigo-500 focus:border-indigo-500">
                                @error('experiencia') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Materias que puede impartir</label>
                                <div class="grid grid-cols-2 gap-4 border p-4 rounded-md bg-gray-50">
                                    @foreach($materias as $materia)
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="materias[]" value="{{ $materia->id }}" class="form-checkbox text-indigo-600" {{ (is_array(old('materias')) && in_array($materia->id, old('materias'))) ? 'checked' : '' }}>
                                            <span class="ml-2">{{ $materia->nombre }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('materias') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-8 pt-4 border-t border-gray-200">
                            <a href="{{ route('admin.docentes.index') }}" class="bg-gray-200 text-gray-700 font-bold py-2 px-6 rounded-md hover:bg-gray-300 transition">Cancelar</a>
                            <button type="submit" class="bg-indigo-600 text-white font-bold py-2 px-6 rounded-md hover:bg-indigo-700 transition">Guardar y Aprobar Docente</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
