<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use Illuminate\Http\Request;
use App\Http\Requests\EntradaRequest;

class Entradamodelcontroller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        //return $request->query();
        //return $request->path();
        // return $request->url();
        return $request->input('titulo', 'sin titulo');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view("entrada.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EntradaRequest $request)
    {
        // Validar Backend
//         $request->validate(
//  [
//             'titulo' => 'required|string|max:50',
//             'tag' => 'required|string|max:20',
//             'contenido' => 'required|string'
//         ],
// [
//         'titulo.required' => 'El campo título es obligatorio',
//         'titulo.string' => 'El título debe ser una cadena de texto',
//         'titulo.max' => 'El título no puede superar 50 caracteres',

//         'tag.required' => 'El campo tag es obligatorio',
//         'tag.string' => 'El tag debe ser una cadena de texto',
//         'tag.max' => 'El tag no puede superar los 20 caracteres',

//         'contenido.required' => 'El campo contenido es obligatorio',
//         'contenido.string' => 'El campo contenido debe ser una cadena de texto'
//         ]);

        // Guardar en BD
        $entrada = new Entrada();
        $entrada->user_id = 1;
        $entrada->titulo = $request->input('titulo');
        $entrada->tag = $request->input('tag');
        $entrada->contenido = $request->input('contenido');
        $entrada->imagen = null;
        $entrada->save();
        return redirect()->route('entrada.create')->with('success', "Datos registrados correctamente");
    }

    /**
     * Display the specified resource.
     */
    public function show(Entrada $entrada)
    {
        //
        return "Show";
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entrada $entrada)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Entrada $entrada)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entrada $entrada)
    {
        //
    }
}
