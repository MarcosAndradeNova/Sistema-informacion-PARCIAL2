<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email', 'exists:users,email'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingrese un correo electrónico válido.',
            'email.exists' => 'El correo no se encuentra registrado.',
            'password.required' => 'La contraseña es obligatoria.',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            // Decay is 10 minutes (600 seconds)
            RateLimiter::hit($this->throttleKey(), 600);

            $retriesLeft = RateLimiter::retriesLeft($this->throttleKey(), 5);

            if ($retriesLeft > 0) {
                throw ValidationException::withMessages([
                    'email' => "Contraseña incorrecta. Intentos restantes: {$retriesLeft}"
                ]);
            } else {
                throw ValidationException::withMessages([
                    'email' => 'Demasiados intentos fallidos. Intente nuevamente en 10 minutos.'
                ]);
            }
        }

        // Validar el rol seleccionado
        if ($this->filled('role')) {
            $requestedRole = strtolower($this->input('role'));
            $usuario = \App\Models\Usuario::where('email', $this->input('email'))->first();
            $tipo = $usuario ? $usuario->tipo : 'P'; // P por defecto si es nuevo o postulante

            $isValidRole = false;
            if ($requestedRole === 'docente' && $tipo === 'D') $isValidRole = true;
            if ($requestedRole === 'administrativo' && $tipo === 'A') $isValidRole = true;
            if ($requestedRole === 'estudiante' && $tipo === 'P') $isValidRole = true;

            if (!$isValidRole) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Acceso denegado: Esta cuenta no pertenece a un ' . ucfirst($requestedRole) . '.'
                ]);
            }
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        throw ValidationException::withMessages([
            'email' => 'Demasiados intentos fallidos. Intente nuevamente en 10 minutos.',
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
