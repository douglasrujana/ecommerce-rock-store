<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EntradaRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'titulo' => 'required|string|min:5|max:50',
            'tag' => 'required|string|min:3|max:20',
            'contenido' => 'required|string|min:5|max:255'
        ];
    }


    //
    public function messages()
    {
        return [
            'titulo.required' => 'El campo título es obligatorio',
            'titulo.string' => 'El título debe ser una cadena de texto',
            'titulo.max' => 'El título no puede superar 50 caracteres',

            'tag.required' => 'El campo tag es obligatorio',
            'tag.string' => 'El tag debe ser una cadena de texto',
            'tag.max' => 'El tag no puede superar los 20 caracteres',

            'contenido.required' => 'El campo contenido es obligatorio',
            'contenido.string' => 'El campo contenido debe ser una cadena de texto'
        ];
    }
}
