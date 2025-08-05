<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $this->authorize('user-list');
        $texto = $request->input('texto');
        // Eloquent
        // Eager load roles para evitar el problema N+1 en la vista
        $registros = User::with('roles')->where('name', 'like', "%{$texto}%")
            ->orWhere('email', 'like', "%{$texto}%")
            ->orderBy('id', 'desc')
            ->paginate(5);
        return view('usuario.index', compact('registros', 'texto'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('user-create');

        //
        $roles = Role::all();
        return view('usuario.action', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $this->authorize('user-create');
        //
        $registro = new User();
        $registro->name = $request->input('name');
        $registro->email = $request->input('email');
        // Usamos Hash::make() para consistencia con el método update.
        $registro->password = Hash::make($request->input('password'));
        $registro->activo = $request->input('activo');
        $registro->Save();
        $registro->assignRole($request->input('role'));
        return redirect()->route('usuarios.index')->with('mensaje',
            'Registro: ' . $registro->name . ' Agregado Correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Actulizar un usuario
        $this->authorize('user-edit');
        $roles = Role::all();
        $registro = User::findOrFail($id);
        return view('usuario.action', compact('registro', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, $id)
    {
        // Update
        $this->authorize('user-edit');
        $registro = User::findOrFail($id);
        $registro->name = $request->input('name');
        $registro->email = $request->input('email');
        // Solo actualizamos el password si se ha ingresado uno nuevo
        if ($request->filled('password')) {
            // Es mejor usar Hash::make() que es el helper actual de Laravel
            $registro->password = Hash::make($request->input('password'));
        }
        $registro->activo = $request->input('activo');
        $registro->Save();
        $registro->syncRoles([$request->input('role')]);
        return redirect()->route('usuarios.index')->with(
            'mensaje',
            'Registro: ' . $registro->name . ' Actualizado Correctamente'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $usuario)
    {
        // Usando Route-Model Binding, Laravel inyecta automáticamente la instancia del User.
        $this->authorize('user-delete');
        $nombreUsuario = $usuario->name;
        $usuario->delete();
        return redirect()->route('usuarios.index')
            ->with('mensaje', $nombreUsuario . ' Eliminado correctamente');
    }

    // Desactiva o activar usuarios
    public function toggleStatus($id)
    {
        // La ruta está definida con {id}, por lo que no se puede usar Route-Model Binding directamente.
        // Buscamos el usuario manualmente.
        $this->authorize('user-activate');
        $usuario = User::findOrFail($id);

        $usuario->activo = !$usuario->activo;
        $usuario->save();

        $accion = $usuario->activo ? 'activado' : 'desactivado';

        return redirect()->route('usuarios.index')
                        ->with('mensaje', "Estado del usuario {$usuario->name} actualizado a {$accion} correctamente.");
    }
}
