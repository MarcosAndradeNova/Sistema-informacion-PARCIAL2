<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('postulantes.index') }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                Registrar Postulante
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-slate-100">
                <form action="{{ route('postulantes.store') }}" method="POST" class="p-8" id="postulanteForm">
                    @csrf

                    <h3 class="text-lg font-semibold text-slate-800 mb-6 border-b border-slate-100 pb-2">Datos Personales</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- CI -->
                        <div>
                            <label for="ci" class="block text-sm font-medium text-slate-700 mb-1">Carnet de Identidad <span class="text-red-500">*</span></label>
                            <input type="text" name="ci" id="ci" value="{{ old('ci') }}" required pattern="[0-9]+"
                                   class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm shadow-sm" placeholder="Ej. 12345678">
                            @error('ci') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <!-- Nombres -->
                        <div>
                            <label for="nombres" class="block text-sm font-medium text-slate-700 mb-1">Nombres <span class="text-red-500">*</span></label>
                            <input type="text" name="nombres" id="nombres" value="{{ old('nombres') }}" required
                                   class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm shadow-sm" placeholder="Tus nombres">
                            @error('nombres') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <!-- Apellidos -->
                        <div>
                            <label for="apellidos" class="block text-sm font-medium text-slate-700 mb-1">Apellidos <span class="text-red-500">*</span></label>
                            <input type="text" name="apellidos" id="apellidos" value="{{ old('apellidos') }}" required
                                   class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm shadow-sm" placeholder="Tus apellidos">
                            @error('apellidos') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <!-- Fecha de Nacimiento -->
                        <div>
                            <label for="fecha_nacimiento" class="block text-sm font-medium text-slate-700 mb-1">Fecha de Nacimiento <span class="text-red-500">*</span></label>
                            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required max="{{ date('Y-m-d') }}"
                                   class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm shadow-sm">
                            @error('fecha_nacimiento') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <!-- Sexo -->
                        <div>
                            <label for="sexo" class="block text-sm font-medium text-slate-700 mb-1">Sexo <span class="text-red-500">*</span></label>
                            <select name="sexo" id="sexo" required class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm shadow-sm">
                                <option value="" disabled {{ old('sexo') ? '' : 'selected' }}>Seleccione...</option>
                                <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                                <option value="O" {{ old('sexo') == 'O' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('sexo') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <!-- Teléfono -->
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-slate-700 mb-1">Teléfono <span class="text-red-500">*</span></label>
                            <input type="tel" name="telefono" id="telefono" value="{{ old('telefono') }}" required pattern="[0-9]{7,15}"
                                   class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm shadow-sm" placeholder="Ej. 70988656">
                            @error('telefono') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <!-- Correo Electrónico -->
                        <div class="md:col-span-2">
                            <label for="correo_electronico" class="block text-sm font-medium text-slate-700 mb-1">Correo Electrónico <span class="text-red-500">*</span></label>
                            <input type="email" name="correo_electronico" id="correo_electronico" value="{{ old('correo_electronico') }}" required
                                   class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm shadow-sm" placeholder="usuario@gmail.com">
                            @error('correo_electronico') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <!-- Dirección -->
                        <div class="md:col-span-2">
                            <label for="direccion" class="block text-sm font-medium text-slate-700 mb-1">Dirección <span class="text-red-500">*</span></label>
                            <input type="text" name="direccion" id="direccion" value="{{ old('direccion') }}" required
                                   class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm shadow-sm" placeholder="Avenida / Calle / Nro">
                            @error('direccion') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-slate-800 mb-6 border-b border-slate-100 pb-2">Datos Académicos</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Colegio -->
                        <div>
                            <label for="colegioprocedencia" class="block text-sm font-medium text-slate-700 mb-1">Colegio de Procedencia <span class="text-red-500">*</span></label>
                            <input type="text" name="colegioprocedencia" id="colegioprocedencia" value="{{ old('colegioprocedencia') }}" required
                                   class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm shadow-sm" placeholder="Nombre del colegio">
                            @error('colegioprocedencia') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <!-- Ciudad -->
                        <div>
                            <label for="ciudad" class="block text-sm font-medium text-slate-700 mb-1">Ciudad <span class="text-red-500">*</span></label>
                            <input type="text" name="ciudad" id="ciudad" value="{{ old('ciudad') }}" required
                                   class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm shadow-sm" placeholder="Ej. Santa Cruz">
                            @error('ciudad') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <!-- Carrera Primera Opción -->
                        <div>
                            <label for="carrera_primera_opcion" class="block text-sm font-medium text-slate-700 mb-1">Carrera 1ra Opción <span class="text-red-500">*</span></label>
                            <select name="carrera_primera_opcion" id="carrera_primera_opcion" required class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm shadow-sm">
                                <option value="" disabled {{ old('carrera_primera_opcion') ? '' : 'selected' }}>Seleccione una carrera...</option>
                                @foreach($carreras as $carrera)
                                    <option value="{{ $carrera->id }}" {{ old('carrera_primera_opcion') == $carrera->id ? 'selected' : '' }}>{{ $carrera->nombre }}</option>
                                @endforeach
                            </select>
                            @error('carrera_primera_opcion') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <!-- Carrera Segunda Opción -->
                        <div>
                            <label for="carrera_segunda_opcion" class="block text-sm font-medium text-slate-700 mb-1">Carrera 2da Opción</label>
                            <select name="carrera_segunda_opcion" id="carrera_segunda_opcion" class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm shadow-sm">
                                <option value="" {{ old('carrera_segunda_opcion') ? '' : 'selected' }}>Ninguna...</option>
                                @foreach($carreras as $carrera)
                                    <option value="{{ $carrera->id }}" {{ old('carrera_segunda_opcion') == $carrera->id ? 'selected' : '' }}>{{ $carrera->nombre }}</option>
                                @endforeach
                            </select>
                            @error('carrera_segunda_opcion') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <!-- Título Bachiller -->
                        <div class="md:col-span-2 flex items-center">
                            <input id="titulo_bachiller" name="titulo_bachiller" type="checkbox" value="1" {{ old('titulo_bachiller') ? 'checked' : '' }}
                                   class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                            <label for="titulo_bachiller" class="ml-2 block text-sm text-slate-700">
                                ¿Presentó título de bachiller?
                            </label>
                        </div>
                        
                        <!-- Otros -->
                        <div class="md:col-span-2">
                            <label for="otros_requisitos" class="block text-sm font-medium text-slate-700 mb-1">Observaciones u otros datos</label>
                            <textarea name="otros_requisitos" id="otros_requisitos" rows="3"
                                      class="w-full rounded-xl border-slate-200 focus:ring-indigo-500 focus:border-indigo-500 text-sm shadow-sm">{{ old('otros_requisitos') }}</textarea>
                            @error('otros_requisitos') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                        <a href="{{ route('postulantes.index') }}" class="px-5 py-2.5 bg-white border border-slate-300 text-slate-700 rounded-xl hover:bg-slate-50 transition-colors font-medium">
                            Cancelar
                        </a>
                        <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl shadow-md transition-colors font-medium flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Guardar Postulante
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
