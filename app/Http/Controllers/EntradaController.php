<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entrada;

class EntradaController extends Controller
{
    //
    function index()
    {
        $entradas = Entrada::all();
        return view('entrada.index', compact('entradas'));
    }
}
