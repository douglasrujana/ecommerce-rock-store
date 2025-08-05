<?php
// Función: Validador de usuarios

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Generalmente se deja en true si el control de acceso se hace en otro lado (p.e. middleware)
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // El método $this->route('usuario') obtiene el ID del usuario desde la URL en la ruta de edición.
        $userId = $this->route('usuario') ?? Auth::id();
        
        // Determinar si estamos en la ruta de perfil
        $isProfileUpdate = $this->routeIs('perfil.update');

        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                // La regla unique se asegura que el email no exista en otro usuario.
                // ignore($userId) es crucial para que no falle al actualizar el mismo usuario.
                Rule::unique('users')->ignore($userId),
            ],
        ];
        
        // El campo activo solo es requerido cuando no estamos actualizando el perfil
        if (!$isProfileUpdate) {
            $rules['activo'] = 'required|boolean';
        }

        // Si es el método de creación (POST), la contraseña es obligatoria.
        if ($this->isMethod('post')) {
            $rules['password'] = ['required', 'confirmed', Password::min(8)];
            // Agregamos regla para password_confirmation
            $rules['password_confirmation'] = ['required'];
        } else { // Si es el método de actualización (PUT/PATCH), la contraseña es opcional.
            // Si el campo password está vacío, no aplicamos ninguna validación
            if ($this->filled('password')) {
                $rules['password'] = ['required', 'confirmed', Password::min(8)];
                $rules['password_confirmation'] = ['required'];
            } else {
                // Si no se proporciona password, no validamos nada relacionado con contraseñas
                $rules['password'] = ['nullable'];
                $rules['password_confirmation'] = ['nullable'];
            }
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password_confirmation.required' => 'La confirmación de contraseña es obligatoria.',
        ];
    }
}
