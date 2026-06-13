<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Coordinador Académico') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg leading-6 font-bold text-gray-900">Actualizar Información</h3>
                    <p class="mt-1 text-sm text-gray-500">Modifique los datos personales o de acceso del coordinador.</p>
                </div>

                <div class="p-6">
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-md">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" /></svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Hay errores en el formulario:</h3>
                                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('admin.coordinadores.update', $coordinador->ci) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <div class="md:col-span-2">
                                <h4 class="text-md font-bold text-gray-700 border-b pb-2 mb-4">Datos Personales</h4>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Carnet de Identidad (CI)</label>
                                <input type="text" value="{{ $coordinador->ci }}" disabled class="mt-1 bg-gray-100 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md cursor-not-allowed">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Nombres *</label>
                                <input type="text" name="nombre" value="{{ old('nombre', $coordinador->nombre) }}" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Apellido Paterno *</label>
                                <input type="text" name="apellidopat" value="{{ old('apellidopat', $coordinador->apellidopat) }}" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Apellido Materno</label>
                                <input type="text" name="apellidomat" value="{{ old('apellidomat', $coordinador->apellidomat) }}" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <div class="md:col-span-2 mt-4">
                                <h4 class="text-md font-bold text-gray-700 border-b pb-2 mb-4">Datos de Cuenta</h4>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Correo Electrónico *</label>
                                <input type="email" name="email" value="{{ old('email', $coordinador->email) }}" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700">Nueva Contraseña (Opcional)</label>
                                <input type="password" name="password" minlength="6" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                <span class="text-xs text-gray-500 mt-1 block">Dejar en blanco si no desea cambiarla.</span>
                            </div>

                        </div>

                        <div class="mt-8 flex justify-end space-x-3">
                            <a href="{{ route('admin.coordinadores.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                                Cancelar
                            </a>
                            <button type="submit" class="bg-indigo-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
