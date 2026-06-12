<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePostulanteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ci' => ['required', 'numeric', 'unique:usuarios,ci'],
            'nombres' => ['required', 'string', 'max:50'],
            'apellidos' => ['required', 'string', 'max:100'],
            'fecha_nacimiento' => ['required', 'date', 'before:today'],
            'sexo' => ['required', 'in:M,F,O'],
            'direccion' => ['required', 'string', 'max:150'],
            'telefono' => ['required', 'numeric', 'digits_between:7,15'],
            'correo_electronico' => ['required', 'email', 'unique:usuarios,correo_electronico'],
            'colegioprocedencia' => ['required', 'string', 'max:100'],
            'ciudad' => ['required', 'string', 'max:50'],
            'carrera_primera_opcion' => ['required', 'exists:carreras,id'],
            'carrera_segunda_opcion' => ['nullable', 'exists:carreras,id', 'different:carrera_primera_opcion'],
            'titulo_bachiller' => ['nullable', 'boolean'],
            'otros_requisitos' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'ci.required' => 'El CI es obligatorio.',
            'ci.numeric' => 'El CI debe contener solo números.',
            'ci.unique' => 'El CI ya se encuentra registrado.',
            'nombres.required' => 'El campo nombres es obligatorio.',
            'apellidos.required' => 'El campo apellidos es obligatorio.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date' => 'Ingrese una fecha válida.',
            'fecha_nacimiento.before' => 'No se permiten fechas futuras.',
            'sexo.required' => 'Debe seleccionar un sexo.',
            'sexo.in' => 'Seleccione una opción válida para sexo.',
            'direccion.required' => 'La dirección es obligatoria.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.numeric' => 'Ingrese un número de teléfono válido.',
            'telefono.digits_between' => 'El teléfono debe tener entre 7 y 15 dígitos.',
            'correo_electronico.required' => 'El correo electrónico es obligatorio.',
            'correo_electronico.email' => 'Ingrese un correo electrónico válido.',
            'correo_electronico.unique' => 'El correo electrónico ya está registrado.',
            'colegioprocedencia.required' => 'El colegio de procedencia es obligatorio.',
            'ciudad.required' => 'La ciudad es obligatoria.',
            'carrera_primera_opcion.required' => 'La carrera es obligatoria.',
            'carrera_primera_opcion.exists' => 'La carrera seleccionada no es válida.',
            'carrera_segunda_opcion.different' => 'La segunda opción debe ser diferente a la primera.',
        ];
    }
}
