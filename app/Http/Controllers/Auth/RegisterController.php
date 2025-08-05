<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RegisterController extends Controller
{
    // Formulario de registro
    public function showRegistroForm()
    {
        return view('auth.registro');

    }

    public function registrar(UserRequest $request)
    {
        $usuario = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'activo' => 1, // Activar automáticamente.
        ]);

        $clienteRol = Role::where('name', 'cliente')->first();
        if($clienteRol)
        {
            $usuario->assignRole($clienteRol);
        }
        Auth::login($usuario);
        return redirect()->route('dashboard')->with('mensaje', 'Registro Exitoso. Bienvenido.');
    }
}
