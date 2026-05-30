<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-2xl sm:rounded-2xl">
                
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 p-8 text-center sm:text-left">
                    <h2 class="text-3xl font-extrabold text-white tracking-tight">
                        Fase 2: Datos del Estudiante
                    </h2>
                    <p class="mt-2 text-blue-100 text-lg">
                        Completa tu información personal y académica para continuar con tu proceso de admisión al CUP.
                    </p>
                </div>

                <div class="p-8">
                    @if ($errors->any())
                        <div class="mb-6 p-4 rounded-xl bg-red-50 border-l-4 border-red-500">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Se encontraron errores en tu solicitud:</h3>
                                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('inscripcion.store') }}" method="POST" class="space-y-8">
                        @csrf

                        <!-- Sección: Datos Personales -->
                        <div>
                            <div class="flex items-center space-x-2 mb-4">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">1</div>
                                <h3 class="text-xl font-semibold text-gray-900 border-b pb-2 w-full">Datos Personales</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <div>
                                    <label for="ci" class="block text-sm font-medium text-gray-700">Carnet de Identidad (CI) *</label>
                                    <input type="text" name="ci" id="ci" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('ci') }}" placeholder="Ej. 1234567">
                                </div>

                                <div>
                                    <label for="fechanac" class="block text-sm font-medium text-gray-700">Fecha de Nacimiento *</label>
                                    <input type="date" name="fechanac" id="fechanac" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('fechanac') }}">
                                </div>

                                <div>
                                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombres *</label>
                                    <input type="text" name="nombre" id="nombre" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('nombre') }}">
                                </div>

                                <div>
                                    <label for="apellido_pat" class="block text-sm font-medium text-gray-700">Apellido Paterno *</label>
                                    <input type="text" name="apellido_pat" id="apellido_pat" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('apellido_pat') }}">
                                </div>

                                <div>
                                    <label for="apellido_mat" class="block text-sm font-medium text-gray-700">Apellido Materno</label>
                                    <input type="text" name="apellido_mat" id="apellido_mat" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('apellido_mat') }}">
                                </div>

                                <div>
                                    <label for="sexo" class="block text-sm font-medium text-gray-700">Sexo *</label>
                                    <select name="sexo" id="sexo" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Seleccione...</option>
                                        <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                        <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono / Celular</label>
                                    <input type="text" name="telefono" id="telefono" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('telefono') }}">
                                </div>

                                <div>
                                    <label for="nacionalidad" class="block text-sm font-medium text-gray-700">Nacionalidad *</label>
                                    <input type="text" name="nacionalidad" id="nacionalidad" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('nacionalidad') ?? 'Boliviana' }}">
                                </div>

                                <div class="md:col-span-2">
                                    <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección Actual *</label>
                                    <input type="text" name="direccion" id="direccion" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('direccion') }}" placeholder="Zona, Avenida, Calle, Número">
                                </div>
                            </div>
                        </div>

                        <!-- Sección: Datos Académicos -->
                        <div class="pt-4">
                            <div class="flex items-center space-x-2 mb-4">
                                <div class="flex-shrink-0 w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">2</div>
                                <h3 class="text-xl font-semibold text-gray-900 border-b pb-2 w-full">Datos Académicos</h3>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                <div>
                                    <label for="colegio_proc" class="block text-sm font-medium text-gray-700">Colegio de Procedencia *</label>
                                    <input type="text" name="colegio_proc" id="colegio_proc" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('colegio_proc') }}">
                                </div>

                                <div>
                                    <label for="ciudad" class="block text-sm font-medium text-gray-700">Ciudad de Procedencia *</label>
                                    <input type="text" name="ciudad" id="ciudad" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('ciudad') }}">
                                </div>

                                <div>
                                    <label for="rude" class="block text-sm font-medium text-gray-700">Código RUDE</label>
                                    <input type="text" name="rude" id="rude" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('rude') }}" placeholder="Opcional">
                                </div>

                                <div>
                                    <label for="carrera_primera_opcion" class="block text-sm font-medium text-gray-700">Carrera (Primera Opción) *</label>
                                    <select name="carrera_primera_opcion" id="carrera_primera_opcion" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Seleccione una carrera...</option>
                                        @foreach($carreras as $carrera)
                                            <option value="{{ $carrera->codigo }}" {{ old('carrera_primera_opcion') == $carrera->codigo ? 'selected' : '' }}>
                                                {{ $carrera->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="carrera_segunda_opcion" class="block text-sm font-medium text-gray-700">Carrera (Segunda Opción) *</label>
                                    <select name="carrera_segunda_opcion" id="carrera_segunda_opcion" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                        <option value="">Seleccione una carrera distinta...</option>
                                        @foreach($carreras as $carrera)
                                            <option value="{{ $carrera->codigo }}" {{ old('carrera_segunda_opcion') == $carrera->codigo ? 'selected' : '' }}>
                                                {{ $carrera->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="md:col-span-2">
                                    <label for="tit_bachiller_nro" class="block text-sm font-medium text-gray-700">Número de Título de Bachiller y Serie *</label>
                                    <input type="text" name="tit_bachiller_nro" id="tit_bachiller_nro" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('tit_bachiller_nro') }}" placeholder="Ej. 12345 - Serie A">
                                </div>

                                </div>

                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-6 border-t mt-8">
                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-lg hover:shadow-xl">
                                    Guardar y Continuar a Verificación
                                    <svg class="ml-2 -mr-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                      <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
