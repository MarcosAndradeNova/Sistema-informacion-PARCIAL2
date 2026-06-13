<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight border-l-4 border-red-700 pl-3">
            {{ __('Ficha de Postulación Docente') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-100">
                
                <!-- Cabecera del formulario -->
                <div class="bg-gradient-to-r from-red-800 to-red-900 px-8 py-6 text-white">
                    <h3 class="text-2xl font-bold tracking-tight">Registro de Datos Personales</h3>
                    <p class="text-red-100 mt-2 text-sm">Por favor, complete cuidadosamente la siguiente información para iniciar su proceso de admisión como personal académico de la facultad.</p>
                </div>

                <!-- Errores de Validación -->
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-6 m-8 mb-0 rounded-r-lg">
                        <div class="flex items-center mb-2">
                            <svg class="h-5 w-5 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <h3 class="text-red-800 font-bold">Por favor, corrija los siguientes errores:</h3>
                        </div>
                        <ul class="list-disc list-inside text-sm text-red-700 ml-7 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('docente.inscripcion.store') }}" method="POST" class="p-8">
                    @csrf
                    
                    <h4 class="text-lg font-bold text-gray-800 border-b border-gray-200 pb-2 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Información de Identidad
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Carnet de Identidad (CI)</label>
                            <input type="text" name="ci" value="{{ old('ci') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50" placeholder="Ej: 1234567">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nombres</label>
                            <input type="text" name="nombre" value="{{ old('nombre') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50" placeholder="Sus nombres">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Apellido Paterno</label>
                            <input type="text" name="apellidopat" value="{{ old('apellidopat') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50" placeholder="Primer apellido">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Apellido Materno</label>
                            <input type="text" name="apellidomat" value="{{ old('apellidomat') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50" placeholder="Segundo apellido (Opcional)">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Fecha de Nacimiento</label>
                            <input type="date" name="fechanac" value="{{ old('fechanac') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Sexo</label>
                            <select name="sexo" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 bg-white">
                                <option value="" disabled selected>Seleccione una opción</option>
                                <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nacionalidad</label>
                            <input type="text" name="nacionalidad" value="{{ old('nacionalidad', 'Boliviana') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                        </div>
                    </div>

                    <h4 class="text-lg font-bold text-gray-800 border-b border-gray-200 pb-2 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        Información de Contacto
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Dirección de Domicilio</label>
                            <input type="text" name="direccion" value="{{ old('direccion') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50" placeholder="Ej: Av. Busch, Calle 3, Nro 123">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Teléfono / Celular</label>
                            <input type="text" name="telefono" value="{{ old('telefono') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50" placeholder="Ej: 77712345">
                        </div>
                    </div>

                    <h4 class="text-lg font-bold text-gray-800 border-b border-gray-200 pb-2 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Datos Académicos y Profesionales
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Profesión / Carrera</label>
                            <input type="text" name="carrera" value="{{ old('carrera') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50" placeholder="Ej: Ingeniero en Sistemas, Matemático, etc.">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nivel de Formación</label>
                            <select name="nivel_formacion" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 bg-white">
                                <option value="" disabled selected>Seleccione su nivel</option>
                                <option value="Licenciatura" {{ old('nivel_formacion') == 'Licenciatura' ? 'selected' : '' }}>Licenciatura</option>
                                <option value="Maestría" {{ old('nivel_formacion') == 'Maestría' ? 'selected' : '' }}>Maestría</option>
                                <option value="Doctorado" {{ old('nivel_formacion') == 'Doctorado' ? 'selected' : '' }}>Doctorado</option>
                                <option value="Postdoctorado" {{ old('nivel_formacion') == 'Postdoctorado' ? 'selected' : '' }}>Postdoctorado</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Años de Experiencia Profesional</label>
                            <input type="number" name="anios_experiencia" value="{{ old('anios_experiencia') }}" required min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50" placeholder="Ej: 5">
                        </div>
                    </div>

                    <h4 class="text-lg font-bold text-gray-800 border-b border-gray-200 pb-2 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        Especialidad Docente (Materias que puede impartir)
                    </h4>

                    <div class="mb-8">
                        <p class="text-sm text-gray-600 mb-4">Seleccione al menos una materia en la que usted esté capacitado para impartir clases en los cursos de pre-universitarios.</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-white p-5 border border-gray-200 rounded-lg shadow-sm">
                            @foreach($materias as $materia)
                                <label class="inline-flex items-center p-3 border border-gray-100 rounded hover:bg-red-50 transition-colors cursor-pointer">
                                    <input type="checkbox" name="materias[]" value="{{ $materia->id }}" class="form-checkbox h-5 w-5 text-red-600 border-gray-300 rounded focus:ring focus:ring-red-200 focus:ring-opacity-50" {{ (is_array(old('materias')) && in_array($materia->id, old('materias'))) ? 'checked' : '' }}>
                                    <span class="ml-3 text-gray-700 font-medium">{{ $materia->nombre }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-5 mb-8 text-sm text-blue-800 flex items-start">
                        <svg class="w-6 h-6 text-blue-600 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <span class="font-bold block mb-1">Sobre la entrega de documentos físicos:</span>
                            Al enviar este formulario, usted quedará registrado en el sistema con estado <strong>Pendiente</strong>. Para que su cuenta sea validada y pueda acceder al panel de docente, deberá apersonarse a la administración de la facultad y entregar su currículum y documentos respaldatorios físicos.
                        </div>
                    </div>

                    <div class="flex items-center justify-end border-t border-gray-200 pt-6">
                        <button type="submit" class="bg-red-700 hover:bg-red-800 text-white font-bold py-3 px-8 rounded-lg shadow-md hover:shadow-lg transition-all focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 flex items-center">
                            Guardar Ficha de Docente
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
