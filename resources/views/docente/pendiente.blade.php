<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Validación de Credenciales') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl p-10 text-center border-t-4 border-yellow-500">
                
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-yellow-100 mb-6">
                    <svg class="h-12 w-12 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>

                <h3 class="text-3xl font-extrabold text-gray-900 mb-4">
                    Cuenta en Proceso de Aprobación
                </h3>
                
                <div class="bg-gray-50 rounded-xl p-6 text-left mb-6 inline-block">
                    <p class="text-lg text-gray-600 mb-3">
                        Hola, <span class="font-bold text-gray-800">{{ Auth::user()->name }}</span>.
                    </p>
                    <p class="text-gray-600 mb-2">
                        Tu cuenta ha sido registrada con el rol de <span class="font-bold text-indigo-600">Docente</span>. Sin embargo, por políticas de seguridad académica, el acceso a los módulos de calificación y gestión de grupos requiere la validación manual por parte de un Administrador.
                    </p>
                    <p class="text-gray-600 font-medium text-red-700 bg-red-50 p-3 rounded border border-red-100 mb-2">
                        IMPORTANTE: Recuerda que debes dejar tus papeles físicos (Currículum y respaldos) en la secretaría de la facultad para completar el proceso de aprobación.
                    </p>
                    <p class="text-gray-600">
                        Actualmente tu estado es: <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-yellow-100 text-yellow-800 border border-yellow-200 mt-2">PENDIENTE</span>
                    </p>
                </div>

                <p class="text-sm text-gray-500 max-w-lg mx-auto">
                    Por favor, ponte en contacto con la administración de la facultad si necesitas agilizar este proceso. Una vez aprobado, tendrás acceso completo a tu panel docente.
                </p>

                <div class="mt-8 pt-6 border-t border-gray-100">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-gray-800 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                        Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
