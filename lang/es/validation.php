<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'name' => [
            'required' => 'El campo nombre es obligatorio',
            'string' => 'El campo debe ser una cadena de Texto',
            'max' => 'El nombre no puede tener mas de 255 caracteres',
        ],
        'email' => [
            'required' => 'El campo email es obligatorio',
            'email' => 'Debe ingresar un correo electrónico',
            'unique' => 'El email ya esta registrado',
        ],
        'password' => [
            'required' => 'El campo contraseña es obligatorio',
            'min' => 'La contraseña debe tener almenos 8 caracteres',
        ],
        'activo' => [
            'required' => 'El campo estado es obligatorio.',
            'boolean' => 'El campo estado no es válido.',
        ],
    ],

    'attributes' => [
        //
    ],

];
