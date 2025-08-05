<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

/**
 * 🛒 CONTROLADOR DEL CARRITO DE COMPRAS - ECOMMERCE MUSICAL
 *
 * Este controlador maneja todas las operaciones del carrito de compras
 * para el ecommerce musical Rock Store. Utiliza sesiones de Laravel
 * para almacenar temporalmente los productos seleccionados.
 *
 * FUNCIONALIDADES:
 * - ✅ Agregar productos al carrito con validaciones
 * - ✅ Actualizar cantidades (incrementar/decrementar/establecer)
 * - ✅ Eliminar productos individuales del carrito
 * - ✅ Vaciar completamente el carrito
 * - ✅ Mostrar contenido del carrito con totales
 * - ✅ Contar items y calcular totales
 * - ✅ Soporte AJAX para operaciones dinámicas
 *
 * @author Rock Store Team
 * @version 2.0.0
 */
class CarritoController extends Controller
{
    /**
     * 🎸 AGREGAR PRODUCTO AL CARRITO
     *
     * Agrega un álbum musical al carrito de compras. Si el producto ya existe,
     * incrementa la cantidad. Incluye validaciones de seguridad y verificación
     * de stock disponible.
     *
     * PROCESO:
     * 1. Valida los datos de entrada (ID válido, cantidad positiva)
     * 2. Busca el producto en la base de datos
     * 3. Verifica disponibilidad de stock
     * 4. Agrega o incrementa cantidad en el carrito (sesión)
     * 5. Retorna respuesta (AJAX o redirect)
     *
     * @param Request $request - Contiene 'id' del producto y 'cantidad' opcional
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function agregar(Request $request)
    {
        // 🔒 VALIDACIONES DE ENTRADA
        $request->validate([
            'id' => 'required|exists:productos,id', // Producto debe existir en BD
            'cantidad' => 'nullable|integer|min:1|max:99' // Cantidad entre 1-99
        ]);

        // 🎵 OBTENER PRODUCTO DE LA BASE DE DATOS
        $producto = Producto::findOrFail($request->id);
        $cantidad = $request->cantidad ?? 1; // Cantidad por defecto: 1

        // 🛒 OBTENER CARRITO ACTUAL DE LA SESIÓN
        $carrito = session('carrito', []); // Array vacío si no existe

        // 🔍 VERIFICAR SI EL PRODUCTO YA ESTÁ EN EL CARRITO
        if (isset($carrito[$producto->id])) {
            // ➕ INCREMENTAR CANTIDAD EXISTENTE
            $nuevaCantidad = $carrito[$producto->id]['cantidad'] + $cantidad;

            // 📦 VERIFICAR LÍMITE DE STOCK (si existe campo stock)
            if (isset($producto->stock) && $nuevaCantidad > $producto->stock) {
                $carrito[$producto->id]['cantidad'] = $producto->stock;
                session(['carrito' => $carrito]);

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'mensaje' => 'Cantidad ajustada al stock disponible',
                        'stock_disponible' => $producto->stock
                    ], 400);
                }

                return redirect()->back()->with('warning', 'Cantidad ajustada al stock disponible');
            }

            $carrito[$producto->id]['cantidad'] = $nuevaCantidad;
        } else {
            // 🆕 AGREGAR NUEVO PRODUCTO AL CARRITO
            $carrito[$producto->id] = [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'precio' => (float) $producto->precio, // Asegurar tipo numérico
                'cantidad' => $cantidad,
                'imagen' => $producto->imagen,
                // 🎸 DATOS ADICIONALES PARA EL ECOMMERCE MUSICAL
                'categoria' => $producto->categoria?->nombre,
                'decada' => $producto->decada?->nombre,
                'año' => $producto->año
            ];
        }

        // 💾 GUARDAR CARRITO ACTUALIZADO EN SESIÓN
        session(['carrito' => $carrito]);

        // 📊 CALCULAR TOTALES PARA RESPUESTA
        $totalItems = array_sum(array_column($carrito, 'cantidad'));
        $totalPrecio = array_sum(array_map(function($item) {
            return $item['precio'] * $item['cantidad'];
        }, $carrito));

        // 🔄 RESPUESTA AJAX O REDIRECT TRADICIONAL
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'mensaje' => 'Álbum agregado al carrito',
                'total_items' => $totalItems,
                'total_precio' => number_format($totalPrecio, 2),
                'producto' => $carrito[$producto->id]
            ]);
        }

        return redirect()->back()->with('mensaje', '🎸 Álbum agregado al carrito');
    }

    /**
     * 📋 MOSTRAR CONTENIDO DEL CARRITO
     *
     * Muestra la página del carrito con todos los productos agregados,
     * incluyendo cálculos de subtotales, impuestos y total final.
     *
     * INFORMACIÓN CALCULADA:
     * - Subtotal por producto (precio × cantidad)
     * - Subtotal general
     * - Impuestos (si aplican)
     * - Total final
     * - Cantidad total de items
     *
     * @return \Illuminate\View\View
     */
    public function mostrar()
    {
        // 🛒 OBTENER CARRITO DE LA SESIÓN
        $carrito = session('carrito', []);

        // 🧮 CALCULAR TOTALES Y ESTADÍSTICAS
        $subtotal = 0;
        $totalItems = 0;
        $carritoConTotales = [];

        foreach ($carrito as $id => $item) {
            // 🔍 VERIFICAR QUE EL PRODUCTO SIGUE EXISTIENDO EN BD
            $producto = Producto::find($id);

            if (!$producto) {
                // 🗑️ ELIMINAR PRODUCTOS QUE YA NO EXISTEN
                unset($carrito[$id]);
                continue;
            }

            // 💰 CALCULAR SUBTOTAL DEL ITEM
            $subtotalItem = $item['precio'] * $item['cantidad'];
            $subtotal += $subtotalItem;
            $totalItems += $item['cantidad'];

            // 📊 AGREGAR DATOS CALCULADOS AL ITEM
            $carritoConTotales[$id] = array_merge($item, [
                'subtotal' => $subtotalItem,
                'precio_formateado' => '$' . number_format($item['precio'], 2),
                'subtotal_formateado' => '$' . number_format($subtotalItem, 2)
            ]);
        }

        // 💾 ACTUALIZAR SESIÓN SI SE ELIMINARON PRODUCTOS
        if (count($carrito) !== count($carritoConTotales)) {
            session(['carrito' => array_intersect_key($carrito, $carritoConTotales)]);
        }

        // 🧾 CALCULAR IMPUESTOS Y TOTAL FINAL
        $impuestos = $subtotal * 0.16; // 16% IVA (ajustar según país)
        $total = $subtotal + $impuestos;

        // 📦 DATOS PARA LA VISTA
        $datosCarrito = [
            'carrito' => $carritoConTotales,
            'subtotal' => $subtotal,
            'impuestos' => $impuestos,
            'total' => $total,
            'total_items' => $totalItems,
            'subtotal_formateado' => '$' . number_format($subtotal, 2),
            'impuestos_formateado' => '$' . number_format($impuestos, 2),
            'total_formateado' => '$' . number_format($total, 2),
            'carrito_vacio' => empty($carritoConTotales)
        ];

        return view('web.pedido', $datosCarrito);
    }

    /**
     * 🔄 ACTUALIZAR CANTIDAD DE PRODUCTO EN CARRITO
     *
     * Función versátil que permite diferentes tipos de actualización:
     * - 'set': Establecer cantidad exacta
     * - 'increment': Incrementar cantidad
     * - 'decrement': Decrementar cantidad (mínimo 1)
     *
     * VALIDACIONES:
     * - Producto existe en BD y en carrito
     * - Cantidad dentro de límites permitidos
     * - Stock disponible (si aplica)
     *
     * @param Request $request - 'id', 'cantidad', 'accion' opcional
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function actualizar(Request $request)
    {
        // 🔒 VALIDACIONES ESTRICTAS
        $request->validate([
            'id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1|max:99',
            'accion' => 'nullable|in:set,increment,decrement'
        ]);

        // 🛒 OBTENER CARRITO ACTUAL
        $carrito = session('carrito', []);

        // ❌ VERIFICAR QUE EL PRODUCTO ESTÉ EN EL CARRITO
        if (!isset($carrito[$request->id])) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'Producto no encontrado en el carrito'
                ], 404);
            }
            return redirect()->back()->with('error', 'Producto no encontrado en el carrito');
        }

        // 🎯 DETERMINAR TIPO DE ACTUALIZACIÓN
        $accion = $request->accion ?? 'set';
        $cantidad = $request->cantidad;

        // 🔢 CALCULAR NUEVA CANTIDAD SEGÚN ACCIÓN
        switch ($accion) {
            case 'increment':
                $carrito[$request->id]['cantidad'] += $cantidad;
                break;
            case 'decrement':
                $nuevaCantidad = $carrito[$request->id]['cantidad'] - $cantidad;
                $carrito[$request->id]['cantidad'] = max(1, $nuevaCantidad); // Mínimo 1
                break;
            default: // 'set'
                $carrito[$request->id]['cantidad'] = $cantidad;
        }

        // 📦 VERIFICAR STOCK DISPONIBLE
        $producto = Producto::find($request->id);
        if ($producto && isset($producto->stock) && $carrito[$request->id]['cantidad'] > $producto->stock) {
            $carrito[$request->id]['cantidad'] = $producto->stock;
            session(['carrito' => $carrito]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'Cantidad ajustada al stock disponible',
                    'nueva_cantidad' => $producto->stock,
                    'stock_disponible' => $producto->stock
                ], 400);
            }

            return redirect()->back()->with('warning', 'Cantidad ajustada al stock disponible');
        }

        // 💾 GUARDAR CAMBIOS EN SESIÓN
        session(['carrito' => $carrito]);

        // 📊 CALCULAR DATOS ACTUALIZADOS
        $totalItem = $carrito[$request->id]['cantidad'] * $carrito[$request->id]['precio'];
        $totalItems = array_sum(array_column($carrito, 'cantidad'));

        // 🔄 RESPUESTA SEGÚN TIPO DE REQUEST
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'mensaje' => 'Cantidad actualizada',
                'nueva_cantidad' => $carrito[$request->id]['cantidad'],
                'total_item' => number_format($totalItem, 2),
                'total_items' => $totalItems
            ]);
        }

        return redirect()->back()->with('mensaje', '✅ Cantidad actualizada');
    }

    /**
     * 🗑️ ELIMINAR PRODUCTO DEL CARRITO
     *
     * Elimina completamente un producto específico del carrito de compras.
     * Incluye validaciones de seguridad y soporte para AJAX.
     *
     * @param Request $request - Contiene 'id' del producto a eliminar
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function eliminar(Request $request)
    {
        // 🔒 VALIDAR ID DEL PRODUCTO
        $request->validate([
            'id' => 'required|integer'
        ]);

        // 🛒 OBTENER CARRITO ACTUAL
        $carrito = session('carrito', []);

        // ❌ VERIFICAR QUE EL PRODUCTO EXISTE EN EL CARRITO
        if (!isset($carrito[$request->id])) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'mensaje' => 'Producto no encontrado en el carrito'
                ], 404);
            }
            return redirect()->back()->with('error', 'Producto no encontrado en el carrito');
        }

        // 📝 GUARDAR INFORMACIÓN DEL PRODUCTO ELIMINADO
        $productoEliminado = $carrito[$request->id];

        // 🗑️ ELIMINAR PRODUCTO DEL CARRITO
        unset($carrito[$request->id]);

        // 💾 ACTUALIZAR SESIÓN
        session(['carrito' => $carrito]);

        // 📊 CALCULAR NUEVOS TOTALES
        $totalItems = array_sum(array_column($carrito, 'cantidad'));

        // 🔄 RESPUESTA SEGÚN TIPO DE REQUEST
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'mensaje' => "🎸 {$productoEliminado['nombre']} eliminado del carrito",
                'total_items' => $totalItems,
                'carrito_vacio' => empty($carrito)
            ]);
        }

        return redirect()->back()->with('mensaje', '🗑️ Producto eliminado del carrito');
    }

    /**
     * 🧹 VACIAR COMPLETAMENTE EL CARRITO
     *
     * Elimina todos los productos del carrito de compras.
     * Útil para empezar de nuevo o cancelar una compra.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function vaciar(Request $request)
    {
        // 🛒 OBTENER INFORMACIÓN ACTUAL PARA ESTADÍSTICAS
        $carrito = session('carrito', []);
        $totalItemsEliminados = array_sum(array_column($carrito, 'cantidad'));

        // 🧹 LIMPIAR CARRITO COMPLETAMENTE
        session()->forget('carrito');

        // 🔄 RESPUESTA SEGÚN TIPO DE REQUEST
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'mensaje' => '🧹 Carrito vaciado completamente',
                'items_eliminados' => $totalItemsEliminados
            ]);
        }

        return redirect()->back()->with('mensaje', '🧹 Carrito vaciado completamente');
    }

    /**
     * 🔢 CONTAR ITEMS EN EL CARRITO
     *
     * Retorna la cantidad total de productos en el carrito.
     * Útil para mostrar badges/contadores en la interfaz.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function contar()
    {
        $carrito = session('carrito', []);
        $totalItems = array_sum(array_column($carrito, 'cantidad'));
        $totalProductos = count($carrito);

        return response()->json([
            'total_items' => $totalItems,        // Total de unidades
            'total_productos' => $totalProductos, // Total de productos únicos
            'carrito_vacio' => empty($carrito)
        ]);
    }

    /**
     * 💰 CALCULAR TOTAL DEL CARRITO
     *
     * Retorna información completa de totales del carrito:
     * subtotal, impuestos, descuentos y total final.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function total()
    {
        $carrito = session('carrito', []);

        // 🧮 CALCULAR SUBTOTAL
        $subtotal = array_sum(array_map(function($item) {
            return $item['precio'] * $item['cantidad'];
        }, $carrito));

        // 🧾 CALCULAR IMPUESTOS (16% IVA)
        $impuestos = $subtotal * 0.16;

        // 💸 CALCULAR DESCUENTOS (si aplican)
        $descuentos = 0; // Implementar lógica de descuentos aquí

        // 💰 TOTAL FINAL
        $total = $subtotal + $impuestos - $descuentos;

        return response()->json([
            'subtotal' => number_format($subtotal, 2),
            'impuestos' => number_format($impuestos, 2),
            'descuentos' => number_format($descuentos, 2),
            'total' => number_format($total, 2),
            'subtotal_raw' => $subtotal,
            'impuestos_raw' => $impuestos,
            'descuentos_raw' => $descuentos,
            'total_raw' => $total
        ]);
    }

    /**
     * 🔍 VERIFICAR DISPONIBILIDAD DE PRODUCTOS
     *
     * Verifica que todos los productos en el carrito sigan disponibles
     * y actualiza precios si han cambiado. Útil antes del checkout.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verificar()
    {
        $carrito = session('carrito', []);
        $productosActualizados = [];
        $productosNoDisponibles = [];
        $preciosActualizados = [];

        foreach ($carrito as $id => $item) {
            $producto = Producto::find($id);

            if (!$producto) {
                // ❌ PRODUCTO YA NO EXISTE
                $productosNoDisponibles[] = $item['nombre'];
                unset($carrito[$id]);
                continue;
            }

            // 💰 VERIFICAR SI EL PRECIO CAMBIÓ
            if ($producto->precio != $item['precio']) {
                $preciosActualizados[] = [
                    'nombre' => $producto->nombre,
                    'precio_anterior' => $item['precio'],
                    'precio_nuevo' => $producto->precio
                ];

                $carrito[$id]['precio'] = $producto->precio;
            }

            // 📦 VERIFICAR STOCK DISPONIBLE
            if (isset($producto->stock) && $item['cantidad'] > $producto->stock) {
                $carrito[$id]['cantidad'] = $producto->stock;
                $productosActualizados[] = [
                    'nombre' => $producto->nombre,
                    'cantidad_anterior' => $item['cantidad'],
                    'cantidad_nueva' => $producto->stock
                ];
            }
        }

        // 💾 GUARDAR CAMBIOS
        session(['carrito' => $carrito]);

        return response()->json([
            'success' => true,
            'productos_no_disponibles' => $productosNoDisponibles,
            'productos_actualizados' => $productosActualizados,
            'precios_actualizados' => $preciosActualizados,
            'requiere_revision' => !empty($productosNoDisponibles) || !empty($productosActualizados) || !empty($preciosActualizados)
        ]);
    }
}
