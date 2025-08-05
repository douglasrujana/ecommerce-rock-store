<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Producto, Categoria, Decada, Pais};

class WebController extends Controller
{
    /**
     * 🏠 PÁGINA PRINCIPAL - CATÁLOGO CON FILTROS AVANZADOS
     */
    public function index(Request $request)
    {
        $query = Producto::with(['categoria', 'decada', 'pais']);

        // 🔍 BÚSQUEDA POR NOMBRE
        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        // 🎸 FILTRO POR CATEGORÍA
        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }

        // 📅 FILTRO POR DÉCADA
        if ($request->filled('decada')) {
            $query->where('decada_id', $request->decada);
        }

        // 🌍 FILTRO POR PAÍS
        if ($request->filled('pais')) {
            $query->where('pais_id', $request->pais);
        }

        // 📋 ORDENAMIENTO
        switch ($request->get('sort', 'nombre')) {
            case 'priceAsc':
                $query->orderBy('precio', 'asc');
                break;
            case 'priceDesc':
                $query->orderBy('precio', 'desc');
                break;
            case 'year':
                $query->orderBy('año', 'desc');
                break;
            default:
                $query->orderBy('nombre', 'asc');
        }

        // 📊 PAGINACIÓN
        $productos = $query->paginate(12);

        // 📊 DATOS PARA FILTROS
        $categorias = Categoria::orderBy('nombre')->get();
        $decadas = Decada::orderBy('inicio')->get();
        $paises = Pais::orderBy('nombre')->get();

        return view('web.index', compact('productos', 'categorias', 'decadas', 'paises'));
    }

    /**
     * 📋 CATÁLOGO COMPLETO
     */
    public function catalogo(Request $request)
    {
        $query = Producto::with(['categoria', 'decada', 'pais']);

        if ($request->filled('search')) {
            $query->where('nombre', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('categoria')) {
            $query->where('categoria_id', $request->categoria);
        }

        if ($request->filled('decada')) {
            $query->where('decada_id', $request->decada);
        }

        if ($request->filled('pais')) {
            $query->where('pais_id', $request->pais);
        }

        switch ($request->get('sort', 'nombre')) {
            case 'priceAsc':
                $query->orderBy('precio', 'asc');
                break;
            case 'priceDesc':
                $query->orderBy('precio', 'desc');
                break;
            case 'year':
                $query->orderBy('año', 'desc');
                break;
            default:
                $query->orderBy('nombre', 'asc');
        }

        $productos = $query->paginate(24);
        $categorias = Categoria::orderBy('nombre')->get();
        $decadas = Decada::orderBy('inicio')->get();
        $paises = Pais::orderBy('nombre')->get();

        return view('web.catalogo', compact('productos', 'categorias', 'decadas', 'paises'));
    }

    /**
     * 🎭 FICHA DE MUSEO - PRODUCTO INDIVIDUAL
     */
    public function show($id)
    {
        $producto = Producto::with([
            'categoria', 'decada', 'pais', 
            'fichaMusical.banda', 'cancions'
        ])->findOrFail($id);
        
        // Productos relacionados de la misma categoría
        $relacionados = Producto::where('categoria_id', $producto->categoria_id)
            ->where('id', '!=', $producto->id)
            ->limit(4)
            ->get();
            
        return view('web.item', compact('producto', 'relacionados'));
    }
}
