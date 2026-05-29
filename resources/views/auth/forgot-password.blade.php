<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar Contraseña - FICCT</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased font-['Inter'] text-slate-800 bg-slate-50 relative min-h-screen flex flex-col justify-center overflow-hidden">

    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-indigo-100 via-white to-white opacity-80"></div>
    </div>

    <div class="relative z-10 w-full max-w-md mx-auto p-6">
        
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-indigo-600 text-white flex items-center justify-center rounded-2xl font-bold text-3xl shadow-xl shadow-indigo-200 mx-auto mb-4">
                F
            </div>
            <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Recuperar Contraseña</h2>
        </div>

        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/50 p-8 border border-slate-100/50 backdrop-blur-xl">
            
            <div class="mb-6 text-sm text-slate-600 leading-relaxed text-center">
                ¿Olvidaste tu contraseña? Ingresa tu correo electrónico registrado y te enviaremos un enlace para que puedas restablecerla de forma segura.
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 text-sm font-medium p-4 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-2">Correo Electrónico</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="w-full pl-10 pr-4 py-3 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 transition-colors @error('email') border-red-500 ring-1 ring-red-500 @enderror" 
                               placeholder="tu@correo.edu" />
                    </div>
                    @error('email')
                        <p class="text-sm text-red-500 mt-2 flex items-center gap-1 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mt-4">
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">Volver al login</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-5 rounded-xl shadow-lg shadow-indigo-200 hover:shadow-indigo-300 transition-all duration-200">
                        Enviar Enlace
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
