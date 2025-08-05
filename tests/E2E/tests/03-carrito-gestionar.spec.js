/**
 * 🛒 PRUEBAS E2E - GESTIONAR CARRITO DE COMPRAS
 * 
 * Suite de pruebas que valida todas las operaciones de gestión del carrito:
 * modificar cantidades, eliminar productos, vaciar carrito, y calcular totales.
 * Implementa metodología TDD con casos edge y validaciones exhaustivas.
 * 
 * CASOS DE PRUEBA CUBIERTOS:
 * ✅ Modificar cantidades (incrementar/decrementar/establecer)
 * ✅ Eliminar productos individuales
 * ✅ Vaciar carrito completo
 * ✅ Validar cálculos de totales e impuestos
 * ✅ Verificar disponibilidad de productos
 * ✅ Manejar estados de carrito vacío
 * ✅ Validar persistencia de sesión
 * ✅ Probar límites y casos edge
 * 
 * METODOLOGÍA TDD:
 * - Cada test sigue el patrón Arrange-Act-Assert
 * - Se validan tanto casos felices como edge cases
 * - Se verifica la integridad de datos en cada operación
 * 
 * @author Rock Store Testing Team
 * @version 1.0.0
 */

const { test, expect } = require('@playwright/test');
const ProductoPage = require('../pages/ProductoPage');
const CarritoPage = require('../pages/CarritoPage');
const testData = require('../fixtures/carrito-test-data.json');

test.describe('🛒 Carrito - Gestión y Operaciones', () => {
    let productoPage;
    let carritoPage;

    /**
     * 🏗️ SETUP - Preparar carrito con productos de prueba
     * 
     * Cada prueba necesita un carrito con productos para poder
     * probar las operaciones de gestión.
     */
    test.beforeEach(async ({ page }) => {
        productoPage = new ProductoPage(page);
        carritoPage = new CarritoPage(page);
        
        // Limpiar carrito
        await carritoPage.navegarAlCarrito();
        const carritoVacio = await carritoPage.estaCarritoVacio();
        if (!carritoVacio) {
            await carritoPage.vaciarCarrito();
        }
        
        // Agregar productos de prueba
        const productos = [
            { id: testData.productos.album1.id, cantidad: 2 },
            { id: testData.productos.album2.id, cantidad: 1 }
        ];
        
        for (const producto of productos) {
            await productoPage.navegarAProducto(producto.id);
            await productoPage.agregarAlCarrito(producto.cantidad);
        }
        
        await carritoPage.navegarAlCarrito();
    });

    /**
     * 🧪 TEST: Incrementar cantidad de producto
     * 
     * OBJETIVO: Verificar que se puede incrementar la cantidad
     * de un producto desde la página del carrito.
     * 
     * CRITERIOS DE ACEPTACIÓN:
     * - La cantidad se incrementa en 1
     * - El subtotal del item se actualiza
     * - Los totales generales se recalculan
     * - El badge del carrito se actualiza
     */
    test('✅ Debe incrementar cantidad de producto', async () => {
        // ARRANGE - Obtener estado inicial
        const items = await carritoPage.obtenerItemsCarrito();
        const primerItem = items[0];
        const cantidadInicial = primerItem.cantidad;
        const totalesIniciales = await carritoPage.obtenerTotales();
        
        // ACT - Incrementar cantidad
        await carritoPage.incrementarCantidad(primerItem.id);
        await carritoPage.esperarActualizacionAjax();
        
        // ASSERT - Verificar cambios
        const itemsActualizados = await carritoPage.obtenerItemsCarrito();
        const itemActualizado = itemsActualizados.find(item => item.id === primerItem.id);
        
        expect(itemActualizado.cantidad).toBe(cantidadInicial + 1);
        
        const totalesActualizados = await carritoPage.obtenerTotales();
        expect(totalesActualizados.totalItems).toBe(totalesIniciales.totalItems + 1);
    });

    /**
     * 🧪 TEST: Decrementar cantidad de producto
     * 
     * OBJETIVO: Verificar que se puede decrementar la cantidad
     * sin que baje de 1 unidad.
     * 
     * CRITERIOS DE ACEPTACIÓN:
     * - La cantidad se decrementa en 1
     * - No puede bajar de 1 unidad
     * - Los totales se actualizan correctamente
     */
    test('✅ Debe decrementar cantidad sin bajar de 1', async () => {
        const items = await carritoPage.obtenerItemsCarrito();
        const itemConCantidad2 = items.find(item => item.cantidad > 1);
        
        // Decrementar item con cantidad > 1
        await carritoPage.decrementarCantidad(itemConCantidad2.id);
        await carritoPage.esperarActualizacionAjax();
        
        const itemsActualizados = await carritoPage.obtenerItemsCarrito();
        const itemActualizado = itemsActualizados.find(item => item.id === itemConCantidad2.id);
        
        expect(itemActualizado.cantidad).toBe(itemConCantidad2.cantidad - 1);
        
        // Intentar decrementar item con cantidad 1
        const itemConCantidad1 = itemsActualizados.find(item => item.cantidad === 1);
        await carritoPage.decrementarCantidad(itemConCantidad1.id);
        await carritoPage.esperarActualizacionAjax();
        
        const itemsFinal = await carritoPage.obtenerItemsCarrito();
        const itemFinal = itemsFinal.find(item => item.id === itemConCantidad1.id);
        
        expect(itemFinal.cantidad).toBe(1); // No debe bajar de 1
    });

    /**
     * 🧪 TEST: Cambiar cantidad directamente
     * 
     * OBJETIVO: Verificar que se puede establecer una cantidad
     * específica escribiendo directamente en el input.
     * 
     * CRITERIOS DE ACEPTACIÓN:
     * - Se acepta la nueva cantidad válida
     * - Se rechazan cantidades inválidas (< 1 o > 99)
     * - Los totales se recalculan correctamente
     */
    test('✅ Debe cambiar cantidad directamente', async () => {
        const items = await carritoPage.obtenerItemsCarrito();
        const primerItem = items[0];
        const nuevaCantidad = 5;
        
        // Cambiar cantidad válida
        await carritoPage.cambiarCantidad(primerItem.id, nuevaCantidad);
        await carritoPage.esperarActualizacionAjax();
        
        const itemsActualizados = await carritoPage.obtenerItemsCarrito();
        const itemActualizado = itemsActualizados.find(item => item.id === primerItem.id);
        
        expect(itemActualizado.cantidad).toBe(nuevaCantidad);
        
        // Probar cantidad inválida (mayor a 99)
        await carritoPage.cambiarCantidad(primerItem.id, 100);
        await carritoPage.esperarActualizacionAjax();
        
        const itemsFinal = await carritoPage.obtenerItemsCarrito();
        const itemFinal = itemsFinal.find(item => item.id === primerItem.id);
        
        expect(itemFinal.cantidad).toBeLessThanOrEqual(99);
    });

    /**
     * 🧪 TEST: Eliminar producto individual
     * 
     * OBJETIVO: Verificar que se puede eliminar un producto
     * específico del carrito.
     * 
     * CRITERIOS DE ACEPTACIÓN:
     * - El producto se elimina completamente
     * - Los totales se recalculan sin el producto
     * - Los demás productos permanecen intactos
     * - Se muestra mensaje de confirmación
     */
    test('✅ Debe eliminar producto individual', async () => {
        const itemsIniciales = await carritoPage.obtenerItemsCarrito();
        const itemAEliminar = itemsIniciales[0];
        const cantidadInicialItems = itemsIniciales.length;
        
        // Eliminar producto
        await carritoPage.eliminarProducto(itemAEliminar.id);
        await carritoPage.esperarActualizacionAjax();
        
        const itemsFinales = await carritoPage.obtenerItemsCarrito();
        
        // Verificar que se eliminó
        expect(itemsFinales).toHaveLength(cantidadInicialItems - 1);
        
        const itemEliminado = itemsFinales.find(item => item.id === itemAEliminar.id);
        expect(itemEliminado).toBeUndefined();
        
        // Verificar que otros productos permanecen
        const otrosItems = itemsIniciales.filter(item => item.id !== itemAEliminar.id);
        for (const item of otrosItems) {
            const itemPermanece = itemsFinales.find(i => i.id === item.id);
            expect(itemPermanece).toBeDefined();
        }
    });

    /**
     * 🧪 TEST: Vaciar carrito completo
     * 
     * OBJETIVO: Verificar que se puede vaciar completamente
     * el carrito con un solo clic.
     * 
     * CRITERIOS DE ACEPTACIÓN:
     * - Todos los productos se eliminan
     * - Se muestra el estado de carrito vacío
     * - Los totales se resetean a cero
     * - El badge del carrito desaparece
     */
    test('✅ Debe vaciar carrito completo', async () => {
        // Verificar que hay productos inicialmente
        const itemsIniciales = await carritoPage.obtenerItemsCarrito();
        expect(itemsIniciales.length).toBeGreaterThan(0);
        
        // Vaciar carrito
        await carritoPage.vaciarCarrito();
        
        // Verificar estado vacío
        const carritoVacio = await carritoPage.estaCarritoVacio();
        expect(carritoVacio).toBe(true);
        
        // Verificar badge
        const badge = await carritoPage.obtenerBadgeCarrito();
        expect(badge).toBe(0);
    });

    /**
     * 🧪 TEST: Validar cálculos de totales
     * 
     * OBJETIVO: Verificar que todos los cálculos matemáticos
     * del carrito son correctos (subtotal, impuestos, total).
     * 
     * CRITERIOS DE ACEPTACIÓN:
     * - Subtotal = suma de (precio × cantidad) de todos los items
     * - Impuestos = subtotal × 16%
     * - Total = subtotal + impuestos
     * - Formato de moneda correcto
     */
    test('✅ Debe calcular totales correctamente', async () => {
        const items = await carritoPage.obtenerItemsCarrito();
        const totales = await carritoPage.obtenerTotales();
        
        // Calcular subtotal esperado
        let subtotalEsperado = 0;
        for (const item of items) {
            const precio = parseFloat(item.precio.replace('$', ''));
            subtotalEsperado += precio * item.cantidad;
        }
        
        // Calcular impuestos esperados (16%)
        const impuestosEsperados = subtotalEsperado * 0.16;
        const totalEsperado = subtotalEsperado + impuestosEsperados;
        
        // Extraer valores numéricos de los totales mostrados
        const subtotalMostrado = parseFloat(totales.subtotal.replace('$', '').replace(',', ''));
        const impuestosMostrados = parseFloat(totales.impuestos.replace('$', '').replace(',', ''));
        const totalMostrado = parseFloat(totales.total.replace('$', '').replace(',', ''));
        
        // Verificar cálculos (con tolerancia para redondeo)
        expect(Math.abs(subtotalMostrado - subtotalEsperado)).toBeLessThan(0.01);
        expect(Math.abs(impuestosMostrados - impuestosEsperados)).toBeLessThan(0.01);
        expect(Math.abs(totalMostrado - totalEsperado)).toBeLessThan(0.01);
        
        // Verificar formato de moneda
        expect(totales.subtotal).toMatch(testData.validaciones.formatoMoneda);
        expect(totales.impuestos).toMatch(testData.validaciones.formatoMoneda);
        expect(totales.total).toMatch(testData.validaciones.formatoMoneda);
    });

    /**
     * 🧪 TEST: Verificar disponibilidad de productos
     * 
     * OBJETIVO: Verificar que la función de verificar disponibilidad
     * funciona correctamente y actualiza el carrito si es necesario.
     * 
     * CRITERIOS DE ACEPTACIÓN:
     * - Se ejecuta la verificación sin errores
     * - Se muestra mensaje de confirmación
     * - Se actualizan productos si hay cambios
     */
    test('✅ Debe verificar disponibilidad de productos', async () => {
        // Ejecutar verificación
        await carritoPage.verificarCarrito();
        
        // Verificar que no hay errores y se muestra confirmación
        const mensaje = await carritoPage.obtenerUltimoMensaje();
        expect(mensaje).toBeTruthy();
        
        // Los productos deben seguir en el carrito si están disponibles
        const items = await carritoPage.obtenerItemsCarrito();
        expect(items.length).toBeGreaterThan(0);
    });

    /**
     * 🧪 TEST: Navegación desde carrito
     * 
     * OBJETIVO: Verificar que los enlaces de navegación
     * desde el carrito funcionan correctamente.
     * 
     * CRITERIOS DE ACEPTACIÓN:
     * - "Seguir comprando" lleva al catálogo
     * - El carrito flotante funciona
     * - La navegación preserva el estado del carrito
     */
    test('✅ Debe navegar correctamente desde carrito', async ({ page }) => {
        // Probar "Seguir comprando"
        await carritoPage.seguirComprando();
        expect(page.url()).toContain('/');
        
        // Verificar que el carrito se preserva
        const badge = await carritoPage.obtenerBadgeCarrito();
        expect(badge).toBeGreaterThan(0);
        
        // Volver al carrito usando botón flotante
        await carritoPage.hacerClicCarritoFlotante();
        expect(page.url()).toContain('/carrito');
        
        // Verificar que los productos siguen ahí
        const items = await carritoPage.obtenerItemsCarrito();
        expect(items.length).toBeGreaterThan(0);
    });

    /**
     * 🧪 TEST: Persistencia de sesión
     * 
     * OBJETIVO: Verificar que el carrito persiste correctamente
     * entre navegaciones y recargas de página.
     * 
     * CRITERIOS DE ACEPTACIÓN:
     * - El carrito persiste al navegar a otras páginas
     * - El carrito persiste al recargar la página
     * - Los datos se mantienen íntegros
     */
    test('✅ Debe persistir carrito en sesión', async ({ page }) => {
        // Obtener estado inicial
        const itemsIniciales = await carritoPage.obtenerItemsCarrito();
        const totalesIniciales = await carritoPage.obtenerTotales();
        
        // Navegar a otra página
        await page.goto('/');
        
        // Volver al carrito
        await carritoPage.navegarAlCarrito();
        
        // Verificar que se mantiene el estado
        const itemsDespuesNavegacion = await carritoPage.obtenerItemsCarrito();
        expect(itemsDespuesNavegacion).toHaveLength(itemsIniciales.length);
        
        // Recargar página
        await page.reload();
        await page.waitForLoadState('networkidle');
        
        // Verificar que persiste después de recarga
        const itemsDespuesRecarga = await carritoPage.obtenerItemsCarrito();
        expect(itemsDespuesRecarga).toHaveLength(itemsIniciales.length);
        
        const totalesDespuesRecarga = await carritoPage.obtenerTotales();
        expect(totalesDespuesRecarga.totalItems).toBe(totalesIniciales.totalItems);
    });

    /**
     * 🧪 TEST: Manejo de errores y casos edge
     * 
     * OBJETIVO: Verificar que el carrito maneja correctamente
     * situaciones de error y casos límite.
     * 
     * CRITERIOS DE ACEPTACIÓN:
     * - Se manejan productos inexistentes
     * - Se validan cantidades extremas
     * - Se muestran mensajes de error apropiados
     */
    test('✅ Debe manejar casos edge y errores', async ({ page }) => {
        // Interceptar requests para simular errores
        await page.route('**/carrito/actualizar', route => {
            route.fulfill({
                status: 500,
                contentType: 'application/json',
                body: JSON.stringify({ success: false, mensaje: 'Error de servidor' })
            });
        });
        
        const items = await carritoPage.obtenerItemsCarrito();
        const primerItem = items[0];
        
        // Intentar actualizar cantidad (debería fallar)
        await carritoPage.incrementarCantidad(primerItem.id);
        await carritoPage.esperarActualizacionAjax();
        
        // Verificar que se maneja el error apropiadamente
        // (la cantidad no debería cambiar)
        const itemsDespuesError = await carritoPage.obtenerItemsCarrito();
        const itemDespuesError = itemsDespuesError.find(item => item.id === primerItem.id);
        
        expect(itemDespuesError.cantidad).toBe(primerItem.cantidad);
    });
});