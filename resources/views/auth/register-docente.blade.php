<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear Cuenta - FICCT</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    <!-- Tailwind CSS (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-['Inter'] text-slate-800 bg-slate-50 relative min-h-screen flex flex-col justify-center overflow-hidden py-10">

    <!-- Background Decoration -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-blue-100 via-white to-white opacity-80"></div>
    </div>

    <!-- Main Container -->
    <div class="relative z-10 w-full max-w-md mx-auto p-6">
        
        <!-- Logo Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-red-700 to-red-900 text-white flex items-center justify-center rounded-2xl font-bold text-3xl shadow-xl shadow-red-200 mx-auto mb-4">
                D
            </div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Registro de Docentes</h2>
            <p class="text-sm text-slate-500 mt-1 uppercase tracking-wider font-semibold">Portal Académico FICCT</p>
        </div>

        <!-- Register Card -->
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 p-8 border border-slate-100/50 backdrop-blur-xl">
            
            <form method="POST" action="{{ route('docente.registro.store') }}" id="registerForm">
                @csrf

                <!-- Name -->
                <div class="mb-5">
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Nombre Completo</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                               class="w-full pl-10 pr-4 py-3 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors @error('name') border-red-500 ring-1 ring-red-500 @enderror" 
                               placeholder="Ej: Juan Pérez" />
                    </div>
                    @error('name')
                        <p class="text-sm text-red-500 mt-2 flex items-center gap-1 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Email Address -->
                <div class="mb-5">
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Correo Electrónico</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                               class="w-full pl-10 pr-4 py-3 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors @error('email') border-red-500 ring-1 ring-red-500 @enderror" 
                               placeholder="tu@correo.edu" />
                    </div>
                    @error('email')
                        <p class="text-sm text-red-500 mt-2 flex items-center gap-1 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-5">
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input id="password" type="password" name="password" required
                               class="w-full pl-10 pr-12 py-3 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors @error('password') border-red-500 ring-1 ring-red-500 @enderror" 
                               placeholder="••••••••" />
                        <!-- Toggle Password Button -->
                        <button type="button" id="togglePassword1" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-blue-600 focus:outline-none transition-colors">
                            <svg id="eyeIcon1" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-sm text-red-500 mt-2 flex items-center gap-1 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-2">Confirmar Contraseña</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input id="password_confirmation" type="password" name="password_confirmation" required
                               class="w-full pl-10 pr-12 py-3 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition-colors @error('password_confirmation') border-red-500 ring-1 ring-red-500 @enderror" 
                               placeholder="••••••••" />
                        <!-- Toggle Password Confirm Button -->
                        <button type="button" id="togglePassword2" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-blue-600 focus:outline-none transition-colors">
                            <svg id="eyeIcon2" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="text-sm text-red-500 mt-2 flex items-center gap-1 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Already registered & Submit Button -->
                <div class="flex flex-col gap-4">
                    <button type="submit" class="w-full bg-red-700 hover:bg-red-800 text-white font-bold py-3 px-4 rounded-xl shadow-lg shadow-red-200 hover:shadow-red-300 hover:-translate-y-0.5 transition-all duration-200 flex justify-center items-center gap-2">
                        Continuar Registro
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                    
                    <a href="{{ route('login') }}" class="text-center text-sm font-semibold text-slate-600 hover:text-red-700 hover:underline transition-colors">
                        ¿Ya tienes una cuenta? Iniciar sesión
                    </a>
                    
                    <div class="mt-2 pt-4 border-t border-slate-100 text-center">
                        <span class="text-sm text-slate-500">¿Eres estudiante o postulante?</span>
                        <a href="{{ route('register') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700 hover:underline transition-colors ml-1">
                            Crear cuenta de estudiante
                        </a>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Footer Info -->
        <p class="text-center text-slate-500 text-xs mt-8 font-medium">
            &copy; {{ date('Y') }} Facultad de Ingeniería en Ciencias de la Computación y Telecomunicaciones.
        </p>
    </div>

    <!-- Toggle Password Scripts -->
    <script>
        function setupToggle(buttonId, inputId, iconId) {
            document.getElementById(buttonId).addEventListener('click', function (e) {
                const passwordInput = document.getElementById(inputId);
                const eyeIcon = document.getElementById(iconId);
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>';
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
                }
            });
        }
        setupToggle('togglePassword1', 'password', 'eyeIcon1');
        setupToggle('togglePassword2', 'password_confirmation', 'eyeIcon2');
    </script>
</body>
</html>
