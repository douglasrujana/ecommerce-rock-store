<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function login(Request $request)
    {
        // 1. Validar los campos con los nombres estándar
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // 2. Crear el array de credenciales para Auth::attempt
        $credenciales = [
            'email' => $request->email,
            'password' => $request->password
        ];

        // 3. Intentar la autenticación
        if (Auth::attempt($credenciales, $request->boolean('remember'))) // Añadimos el "recordar clave"
        {
            $user = Auth::user();

            if ($user->activo)
            {
                // Es una buena práctica regenerar la sesión para evitar ataques de "session fixation"
                $request->session()->regenerate();
                // redirect()->intended() lleva al usuario a la página que intentaba visitar antes del login, o al dashboard si no había ninguna.
                return redirect()->intended(route('dashboard'));
            }
            else
            {
                Auth::logout();
                // Es mejor usar withErrors para que los errores se manejen con la variable $errors en la vista
                return back()->withErrors(['email' => 'Su cuenta está inactiva. Contacte al Admin.'])->onlyInput('email');
            }
        }
        // Mensaje en el login
        return back()->withErrors(['email' => 'Las credenciales son incorrectas.'])
                    ->onlyInput('email');
    }
}
