/**
 * 🛒 PRUEBAS E2E - AGREGAR PRODUCTOS AL CARRITO
 * 
 * Suite de pruebas que valida la funcionalidad de agregar productos al carrito
 * desde la página de detalle del producto. Implementa metodología TDD con
 * casos de prueba exhaustivos.
 * 
 * CASOS DE PRUEBA CUBIERTOS:
 * ✅ Agregar producto con cantidad por defecto
 * ✅ Agregar producto con cantidad personalizada
 * ✅ Validar actualización del badge del carrito
 * ✅ Validar mensajes de confirmación
 * ✅ Validar límites de cantidad (min/max)
 * ✅ Validar comportamiento con productos duplicados
 * ✅ Validar respuesta AJAX y estados de UI
 * 
 * METODOLOGÍA TDD:
 * 1. RED: Escribir prueba que falle
 * 2. GREEN: Implementar código mínimo para pasar
 * 3. REFACTOR: Mejorar código manteniendo pruebas verdes
 * 
 * @author Rock Store Testing Team
 * @version 1.0.0
 */

const { test, expect } = require('@playwright/test');
const ProductoPage = require('../pages/ProductoPage');
const CarritoPage = require('../pages/CarritoPage');
const testData = require('../fixtures/carrito-test-data.json');

test.describe('🛒 Carrito - Agregar Productos', () => {
    let productoPage;
    let carritoPage;

    /**
     * 🏗️ SETUP - Configuración antes de cada prueba
     * 
     * Inicializa las páginas y limpia el estado del carrito.
     * Garantiza que cada prueba empiece con un estado limpio.
     */
    test.beforeEach(async ({ page }) => {
        productoPage = new ProductoPage(page);
        carritoPage = new CarritoPage(page);
        
        // Limpiar carrito (si existe)
        await page.goto('/carrito');
        const carritoVacio = await carritoPage.estaCarritoVacio();
        if (!carritoVacio) {
            await carritoPage.vaciarCarrito();
        }
    });

    /**
     * 🧪 TEST: Agregar producto con cantidad por defecto
     * 
     * OBJETIVO: Verificar que se puede agregar un producto al carrito
     * con la cantidad por defecto (1 unidad).
     * 
     * PASOS:
     * 1. Navegar a página de producto
     * 2. Hacer clic en "Agregar al carrito"
     * 3. Verificar mensaje de confirmación
     * 4. Verificar actualización del badge
     * 5. Verificar producto en carrito
     * 
     * CRITERIOS DE ACEPTACIÓN:
     * - El producto se agrega con cantidad 1
     * - El badge muestra 1 item
     * - Aparece mensaje de confirmación
     * - El carrito contiene el producto correcto
     */
    test('✅ Debe agregar producto con cantidad por defecto', async () => {
        // ARRANGE - Preparar datos de prueba
        const producto = testData.productos.album1;
        
        // ACT - Ejecutar acción
        await productoPage.navegarAProducto(producto.id);
        const badgeAntes = await productoPage.obtenerBadgeCarrito();
        
        await productoPage.agregarAlCarrito();
        
        // ASSERT - Verificar resultados
        // Verificar confirmación visual
        const confirmado = await productoPage.esperarConfirmacionAgregado();
        expect(confirmado).toBe(true);
        
        // Verificar badge actualizado
        const badgeDespues = await productoPage.obtenerBadgeCarrito();
        expect(badgeDespues).toBe(badgeAntes + 1);
        
        // Verificar producto en carrito
        await carritoPage.navegarAlCarrito();
        const items = await carritoPage.obtenerItemsCarrito();
        
        expect(items).toHaveLength(1);
        expect(items[0].id).toBe(producto.id.toString());
        expect(items[0].cantidad).toBe(1);
        expect(items[0].nombre).toContain(producto.nombre);
    });

    /**
     * 🧪 TEST: Agregar producto con cantidad personalizada
     * 
     * OBJETIVO: Verificar que se puede especificar una cantidad
     * personalizada al agregar un producto.
     * 
     * CRITERIOS DE ACEPTACIÓN:
     * - Se respeta la cantidad especificada
     * - El badge refleja la cantidad correcta
     * - Los totales se calculan correctamente
     */
    test('✅ Debe agregar producto con cantidad personalizada', async () => {
        // ARRANGE
        const producto = testData.productos.album2;
        const cantidadDeseada = 3;
        
        // ACT
        await productoPage.navegarAProducto(producto.id);
        await productoPage.agregarAlCarrito(cantidadDeseada);
        
        // ASSERT
        const badge = await productoPage.obtenerBadgeCarrito();
        expect(badge).toBe(cantidadDeseada);
        
        await carritoPage.navegarAlCarrito();
        const items = await carritoPage.obtenerItemsCarrito();
        
        expect(items[0].cantidad).toBe(cantidadDeseada);
        
        const totales = await carritoPage.obtenerTotales();
        expect(totales.totalItems).toBe(cantidadDeseada);
    });

    /**
     * 🧪 TEST: Validar límites de cantidad
     * 
     * OBJETIVO: Verificar que se respetan los límites mínimos
     * y máximos de cantidad (1-99).
     * 
     * CRITERIOS DE ACEPTACIÓN:
     * - No se permite cantidad menor a 1
     * - No se permite cantidad mayor a 99
     * - Se mantiene la cantidad válida más cercana
     */
    test('✅ Debe respetar límites de cantidad (1-99)', async () => {
        const producto = testData.productos.album1;
        await productoPage.navegarAProducto(producto.id);
        
        // Probar cantidad mínima
        await productoPage.establecerCantidad(0);
        let cantidadActual = await productoPage.obtenerCantidadActual();
        expect(cantidadActual).toBeGreaterThanOrEqual(1);
        
        // Probar cantidad máxima
        await productoPage.establecerCantidad(100);
        cantidadActual = await productoPage.obtenerCantidadActual();
        expect(cantidadActual).toBeLessThanOrEqual(99);
    });

    /**
     * 🧪 TEST: Agregar producto duplicado
     * 
     * OBJETIVO: Verificar que agregar el mismo producto
     * incrementa la cantidad en lugar de crear entrada duplicada.
     * 
     * CRITERIOS DE ACEPTACIÓN:
     * - Solo existe una entrada del producto en el carrito
     * - La cantidad se suma correctamente
     * - Los totales se actualizan apropiadamente
     */
    test('✅ Debe incrementar cantidad al agregar producto duplicado', async () => {
        // ARRANGE
        const producto = testData.productos.album1;
        const primeraAgregada = 2;
        const segundaAgregada = 3;
        const totalEsperado = primeraAgregada + segundaAgregada;
        
        // ACT - Agregar primera vez
        await productoPage.navegarAProducto(producto.id);
        await productoPage.agregarAlCarrito(primeraAgregada);
        
        // ACT - Agregar segunda vez
        await productoPage.agregarAlCarrito(segundaAgregada);
        
        // ASSERT
        const badge = await productoPage.obtenerBadgeCarrito();
        expect(badge).toBe(totalEsperado);
        
        await carritoPage.navegarAlCarrito();
        const items = await carritoPage.obtenerItemsCarrito();
        
        // Debe haber solo un item con cantidad sumada
        expect(items).toHaveLength(1);
        expect(items[0].cantidad).toBe(totalEsperado);
    });

    /**
     * 🧪 TEST: Validar controles de cantidad
     * 
     * OBJETIVO: Verificar que los botones +/- funcionan
     * correctamente para ajustar la cantidad.
     * 
     * CRITERIOS DE ACEPTACIÓN:
     * - El botón + incrementa la cantidad
     * - El botón - decrementa la cantidad
     * - No se puede decrementar por debajo de 1
     */
    test('✅ Debe funcionar controles de incrementar/decrementar', async () => {
        const producto = testData.productos.album1;
        await productoPage.navegarAProducto(producto.id);
        
        // Verificar incremento
        const cantidadInicial = await productoPage.obtenerCantidadActual();
        await productoPage.incrementarCantidad();
        
        let cantidadActual = await productoPage.obtenerCantidadActual();
        expect(cantidadActual).toBe(cantidadInicial + 1);
        
        // Verificar decremento
        await productoPage.decrementarCantidad();
        cantidadActual = await productoPage.obtenerCantidadActual();
        expect(cantidadActual).toBe(cantidadInicial);
        
        // Verificar que no baja de 1
        await productoPage.establecerCantidad(1);
        await productoPage.decrementarCantidad();
        cantidadActual = await productoPage.obtenerCantidadActual();
        expect(cantidadActual).toBe(1);
    });

    /**
     * 🧪 TEST: Validar mensajes de confirmación
     * 
     * OBJETIVO: Verificar que se muestran los mensajes
     * apropiados al agregar productos al carrito.
     * 
     * CRITERIOS DE ACEPTACIÓN:
     * - Aparece mensaje de éxito al agregar
     * - El mensaje contiene información relevante
     * - El mensaje desaparece después de un tiempo
     */
    test('✅ Debe mostrar mensajes de confirmación apropiados', async () => {
        const producto = testData.productos.album1;
        await productoPage.navegarAProducto(producto.id);
        
        await productoPage.agregarAlCarrito();
        
        // Verificar que aparece mensaje de éxito
        const mensaje = await productoPage.obtenerMensajeCarrito();
        expect(mensaje).toContain(testData.mensajes.agregadoExito);
        
        // Verificar confirmación visual en botón
        const confirmado = await productoPage.esperarConfirmacionAgregado();
        expect(confirmado).toBe(true);
    });

    /**
     * 🧪 TEST: Validar comportamiento AJAX
     * 
     * OBJETIVO: Verificar que las operaciones AJAX
     * funcionan correctamente sin recargar la página.
     * 
     * CRITERIOS DE ACEPTACIÓN:
     * - La página no se recarga al agregar al carrito
     * - El badge se actualiza dinámicamente
     * - Los estados de UI cambian apropiadamente
     */
    test('✅ Debe funcionar correctamente vía AJAX', async ({ page }) => {
        const producto = testData.productos.album1;
        await productoPage.navegarAProducto(producto.id);
        
        // Interceptar requests para verificar AJAX
        let ajaxCalled = false;
        page.on('request', request => {
            if (request.url().includes('/carrito/agregar')) {
                ajaxCalled = true;
                expect(request.method()).toBe('POST');
                expect(request.headers()['x-requested-with']).toBe('XMLHttpRequest');
            }
        });
        
        const urlAntes = page.url();
        await productoPage.agregarAlCarrito();
        
        // Verificar que no hubo recarga de página
        expect(page.url()).toBe(urlAntes);
        expect(ajaxCalled).toBe(true);
        
        // Verificar actualización dinámica del badge
        const badge = await productoPage.obtenerBadgeCarrito();
        expect(badge).toBe(1);
    });

    /**
     * 🧪 TEST: Validar múltiples productos diferentes
     * 
     * OBJETIVO: Verificar que se pueden agregar múltiples
     * productos diferentes al carrito.
     * 
     * CRITERIOS DE ACEPTACIÓN:
     * - Cada producto se agrega como entrada separada
     * - Los totales se calculan correctamente
     * - El badge refleja la suma total de items
     */
    test('✅ Debe agregar múltiples productos diferentes', async () => {
        const productos = [
            { data: testData.productos.album1, cantidad: 1 },
            { data: testData.productos.album2, cantidad: 2 },
            { data: testData.productos.album3, cantidad: 1 }
        ];
        
        let totalItemsEsperado = 0;
        
        // Agregar cada producto
        for (const { data, cantidad } of productos) {
            await productoPage.navegarAProducto(data.id);
            await productoPage.agregarAlCarrito(cantidad);
            totalItemsEsperado += cantidad;
        }
        
        // Verificar badge total
        const badge = await productoPage.obtenerBadgeCarrito();
        expect(badge).toBe(totalItemsEsperado);
        
        // Verificar carrito
        await carritoPage.navegarAlCarrito();
        const items = await carritoPage.obtenerItemsCarrito();
        
        expect(items).toHaveLength(productos.length);
        
        const totales = await carritoPage.obtenerTotales();
        expect(totales.totalItems).toBe(totalItemsEsperado);
        expect(totales.productosUnicos).toBe(productos.length);
    });
});