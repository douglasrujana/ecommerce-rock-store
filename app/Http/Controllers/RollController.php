<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RollController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $this->authorize('rol-list');
        $texto = $request->input('texto');
        // Eager load permissions para evitar el problema N+1 en la vista
        $registros = Role::with('permissions')
            ->where('name', 'like', "%{$texto}%")
            ->orderBy('id', 'desc')
            ->paginate(2);
        return view('role.index', compact('registros', 'texto'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $this->authorize('rol-create');
        $permissions = Permission::all();
        return view('role.action', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $this->authorize('rol-create');
        $validatedData = $request->validate([
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        // Solución definitiva: Asegurar que los IDs de los permisos sean enteros.
        $permissionIds = array_map('intval', $validatedData['permissions']);

        $registro = Role::create(['name' => $validatedData['name']]);
        $registro->syncPermissions($permissionIds);
        return redirect()->route('roles.index')
                        ->with('mensaje', 'Rol ' . $registro->name . ' creado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $this->authorize('rol-edit');
        $registro = Role::findOrFail($id);
        $permissions = Permission::all();
        return view('role.action', compact('registro', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $this->authorize('rol-edit');
        $registro = Role::findOrFail($id);
        $validatedData = $request->validate([
            'name' => 'required|unique:roles,name,' . $registro->id,
            'permissions' => 'required|array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        // Solución definitiva: Asegurar que los IDs de los permisos sean enteros.
        $permissionIds = array_map('intval', $validatedData['permissions']);

        $registro->update(['name' => $validatedData['name']]);
        $registro->syncPermissions($permissionIds);
        return redirect()->route('roles.index')
            ->with('mensaje', 'Rol ' . $registro->name . ' actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $this->authorize('rol-delete');
        $registro = Role::findOrFail($id);
        $registro->delete();
        return redirect()->route('roles.index')
            ->with('mensaje', 'Rol ' . $registro->name . ' eliminado correctamente.');
    }
}
