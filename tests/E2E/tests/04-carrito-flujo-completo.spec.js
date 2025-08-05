/**
 * 🛒 PRUEBAS E2E - FLUJO COMPLETO DEL CARRITO
 * 
 * Suite de pruebas que valida el flujo completo de compra desde
 * la perspectiva del usuario final. Implementa user journeys
 * realistas y casos de uso end-to-end.
 * 
 * FLUJOS DE USUARIO CUBIERTOS:
 * ✅ Flujo básico: Explorar → Agregar → Comprar
 * ✅ Flujo de comparación: Múltiples productos → Decidir → Comprar
 * ✅ Flujo de modificación: Agregar → Modificar → Finalizar
 * ✅ Flujo de abandono: Agregar → Salir → Volver → Continuar
 * ✅ Flujo de error: Manejar productos no disponibles
 * ✅ Flujo móvil: Experiencia responsive
 * 
 * METODOLOGÍA USER-CENTRIC:
 * - Cada test simula un comportamiento real de usuario
 * - Se valida la experiencia completa, no solo funcionalidad
 * - Se incluyen aspectos de UX y performance
 * 
 * @author Rock Store Testing Team
 * @version 1.0.0
 */

const { test, expect } = require('@playwright/test');
const ProductoPage = require('../pages/ProductoPage');
const CarritoPage = require('../pages/CarritoPage');
const testData = require('../fixtures/carrito-test-data.json');

test.describe('🛒 Carrito - Flujos Completos de Usuario', () => {
    let productoPage;
    let carritoPage;

    test.beforeEach(async ({ page }) => {
        productoPage = new ProductoPage(page);
        carritoPage = new CarritoPage(page);
        
        // Limpiar carrito para cada test
        await carritoPage.navegarAlCarrito();
        const carritoVacio = await carritoPage.estaCarritoVacio();
        if (!carritoVacio) {
            await carritoPage.vaciarCarrito();
        }
    });

    /**
     * 🧪 USER JOURNEY: Comprador Decidido
     * 
     * ESCENARIO: Un usuario que sabe exactamente qué quiere comprar
     * 
     * FLUJO:
     * 1. Llega al sitio
     * 2. Busca producto específico
     * 3. Ve detalles del producto
     * 4. Agrega al carrito
     * 5. Va directo al checkout
     * 
     * CRITERIOS DE ÉXITO:
     * - Flujo rápido y sin fricciones
     * - Información clara en cada paso
     * - Confirmaciones apropiadas
     * - Totales correctos
     */
    test('🎯 Flujo: Comprador decidido - Producto específico', async ({ page }) => {
        // ARRANGE - Usuario llega al sitio
        await page.goto('/');
        
        // ACT & ASSERT - Flujo completo
        
        // 1. Navegar a producto específico (Abbey Road)
        const productoDeseado = testData.productos.album1;
        await productoPage.navegarAProducto(productoDeseado.id);
        
        // Verificar información del producto
        const infoProducto = await productoPage.obtenerInformacionProducto();
        expect(infoProducto.nombre).toContain(productoDeseado.nombre);
        
        // 2. Agregar al carrito con cantidad específica
        const cantidadDeseada = 2;
        await productoPage.agregarAlCarrito(cantidadDeseada);
        
        // Verificar confirmación inmediata
        const confirmado = await productoPage.esperarConfirmacionAgregado();
        expect(confirmado).toBe(true);
        
        // Verificar badge actualizado
        const badge = await productoPage.obtenerBadgeCarrito();
        expect(badge).toBe(cantidadDeseada);
        
        // 3. Ir al carrito para revisar
        await carritoPage.hacerClicCarritoFlotante();
        
        // Verificar producto en carrito
        const items = await carritoPage.obtenerItemsCarrito();
        expect(items).toHaveLength(1);
        expect(items[0].cantidad).toBe(cantidadDeseada);
        
        // Verificar totales
        const totales = await carritoPage.obtenerTotales();
        expect(totales.totalItems).toBe(cantidadDeseada);
        expect(totales.productosUnicos).toBe(1);
        
        // 4. Proceder al checkout (simulado)
        await carritoPage.irAlCheckout();
        
        // En un flujo real, aquí continuaría el proceso de pago
        // Por ahora verificamos que se intenta proceder
        expect(page.url()).toContain('/carrito');
    });

    /**
     * 🧪 USER JOURNEY: Comprador Explorador
     * 
     * ESCENARIO: Usuario que explora múltiples productos antes de decidir
     * 
     * FLUJO:
     * 1. Explora catálogo
     * 2. Ve varios productos
     * 3. Agrega múltiples items
     * 4. Compara en carrito
     * 5. Modifica cantidades
     * 6. Finaliza compra
     */
    test('🔍 Flujo: Comprador explorador - Múltiples productos', async ({ page }) => {
        // 1. Explorar múltiples productos
        const productosInteres = [
            { data: testData.productos.album1, cantidad: 1 },
            { data: testData.productos.album2, cantidad: 2 },
            { data: testData.productos.album3, cantidad: 1 }
        ];
        
        // Agregar productos uno por uno (simulando exploración)
        for (const { data, cantidad } of productosInteres) {
            await productoPage.navegarAProducto(data.id);
            
            // Simular tiempo de lectura/decisión
            await page.waitForTimeout(500);
            
            await productoPage.agregarAlCarrito(cantidad);
            
            // Verificar que se agregó
            const confirmado = await productoPage.esperarConfirmacionAgregado();
            expect(confirmado).toBe(true);
        }
        
        // 2. Revisar carrito completo
        await carritoPage.navegarAlCarrito();
        
        const items = await carritoPage.obtenerItemsCarrito();
        expect(items).toHaveLength(productosInteres.length);
        
        // 3. Modificar cantidades (cambio de opinión)
        const primerItem = items[0];
        await carritoPage.incrementarCantidad(primerItem.id);
        await carritoPage.esperarActualizacionAjax();
        
        // 4. Eliminar un producto (decisión final)
        const ultimoItem = items[items.length - 1];
        await carritoPage.eliminarProducto(ultimoItem.id);
        await carritoPage.esperarActualizacionAjax();
        
        // 5. Verificar estado final
        const itemsFinales = await carritoPage.obtenerItemsCarrito();
        expect(itemsFinales).toHaveLength(productosInteres.length - 1);
        
        // 6. Proceder al checkout
        const totalesFinales = await carritoPage.obtenerTotales();
        expect(totalesFinales.totalItems).toBeGreaterThan(0);
        
        await carritoPage.irAlCheckout();
    });

    /**
     * 🧪 USER JOURNEY: Comprador Indeciso
     * 
     * ESCENARIO: Usuario que abandona y vuelve múltiples veces
     * 
     * FLUJO:
     * 1. Agrega productos
     * 2. Sale del sitio (abandona)
     * 3. Vuelve más tarde
     * 4. Encuentra carrito preservado
     * 5. Continúa con la compra
     */
    test('🤔 Flujo: Comprador indeciso - Abandono y retorno', async ({ page }) => {
        // 1. Agregar productos inicialmente
        const producto = testData.productos.album1;
        await productoPage.navegarAProducto(producto.id);
        await productoPage.agregarAlCarrito(2);
        
        // Verificar que se agregó
        const badgeInicial = await productoPage.obtenerBadgeCarrito();
        expect(badgeInicial).toBe(2);
        
        // 2. Simular abandono - navegar a otra página
        await page.goto('https://www.google.com');
        await page.waitForTimeout(1000);
        
        // 3. Volver al sitio (simulando retorno después de tiempo)
        await page.goto('/');
        
        // 4. Verificar que el carrito se preservó
        const badgeDespuesRetorno = await carritoPage.obtenerBadgeCarrito();
        expect(badgeDespuesRetorno).toBe(badgeInicial);
        
        // 5. Ir al carrito y verificar productos
        await carritoPage.navegarAlCarrito();
        const items = await carritoPage.obtenerItemsCarrito();
        expect(items).toHaveLength(1);
        expect(items[0].cantidad).toBe(2);
        
        // 6. Agregar más productos (decisión final)
        await productoPage.navegarAProducto(testData.productos.album2.id);
        await productoPage.agregarAlCarrito(1);
        
        // 7. Finalizar compra
        await carritoPage.navegarAlCarrito();
        const itemsFinales = await carritoPage.obtenerItemsCarrito();
        expect(itemsFinales).toHaveLength(2);
        
        await carritoPage.irAlCheckout();
    });

    /**
     * 🧪 USER JOURNEY: Comprador Móvil
     * 
     * ESCENARIO: Usuario en dispositivo móvil con limitaciones de pantalla
     * 
     * FLUJO:
     * 1. Navegar en viewport móvil
     * 2. Usar controles táctiles
     * 3. Verificar responsividad
     * 4. Completar compra en móvil
     */
    test('📱 Flujo: Comprador móvil - Experiencia responsive', async ({ page }) => {
        // Configurar viewport móvil
        await page.setViewportSize({ width: 375, height: 667 });
        
        // 1. Navegar a producto en móvil
        const producto = testData.productos.album1;
        await productoPage.navegarAProducto(producto.id);
        
        // Verificar que elementos son visibles en móvil
        const nombreVisible = await page.locator('h1.display-5').isVisible();
        expect(nombreVisible).toBe(true);
        
        // 2. Agregar al carrito usando controles móviles
        await productoPage.agregarAlCarrito(1);
        
        // 3. Verificar carrito flotante en móvil
        const carritoFlotante = page.locator('.position-fixed .bg-primary');
        await expect(carritoFlotante).toBeVisible();
        
        // 4. Navegar al carrito
        await carritoPage.hacerClicCarritoFlotante();
        
        // 5. Verificar que la tabla del carrito es responsive
        const tablaCarrito = page.locator('.card-body');
        await expect(tablaCarrito).toBeVisible();
        
        // 6. Probar controles de cantidad en móvil
        const items = await carritoPage.obtenerItemsCarrito();
        const primerItem = items[0];
        
        await carritoPage.incrementarCantidad(primerItem.id);
        await carritoPage.esperarActualizacionAjax();
        
        // Verificar que funcionó
        const itemsActualizados = await carritoPage.obtenerItemsCarrito();
        expect(itemsActualizados[0].cantidad).toBe(2);
        
        // 7. Proceder al checkout en móvil
        const btnCheckout = page.locator('#checkoutBtn');
        await expect(btnCheckout).toBeVisible();
        await btnCheckout.click();
    });

    /**
     * 🧪 USER JOURNEY: Manejo de Errores
     * 
     * ESCENARIO: Usuario encuentra productos no disponibles o errores
     * 
     * FLUJO:
     * 1. Agregar productos normalmente
     * 2. Simular productos no disponibles
     * 3. Verificar manejo de errores
     * 4. Recuperar y continuar
     */
    test('⚠️ Flujo: Manejo de errores y productos no disponibles', async ({ page }) => {
        // 1. Agregar productos normalmente
        await productoPage.navegarAProducto(testData.productos.album1.id);
        await productoPage.agregarAlCarrito(2);
        
        await productoPage.navegarAProducto(testData.productos.album2.id);
        await productoPage.agregarAlCarrito(1);
        
        // 2. Ir al carrito
        await carritoPage.navegarAlCarrito();
        
        // 3. Simular verificación que encuentra problemas
        // (En un test real, esto podría simular productos agotados)
        await carritoPage.verificarCarrito();
        
        // 4. Verificar que el sistema maneja la situación
        const items = await carritoPage.obtenerItemsCarrito();
        expect(items.length).toBeGreaterThan(0); // Productos disponibles permanecen
        
        // 5. Continuar con productos disponibles
        const totales = await carritoPage.obtenerTotales();
        expect(totales.totalItems).toBeGreaterThan(0);
        
        // 6. Proceder al checkout con productos válidos
        await carritoPage.irAlCheckout();
    });

    /**
     * 🧪 USER JOURNEY: Comprador Bulk
     * 
     * ESCENARIO: Usuario que compra grandes cantidades
     * 
     * FLUJO:
     * 1. Agregar productos con cantidades altas
     * 2. Verificar límites del sistema
     * 3. Manejar cantidades máximas
     * 4. Calcular totales grandes
     */
    test('📦 Flujo: Comprador bulk - Cantidades grandes', async () => {
        // 1. Agregar producto con cantidad alta
        const producto = testData.productos.album1;
        await productoPage.navegarAProducto(producto.id);
        
        // Probar cantidad máxima permitida
        const cantidadMaxima = testData.validaciones.cantidadMaxima;
        await productoPage.agregarAlCarrito(cantidadMaxima);
        
        // 2. Verificar que se respeta el límite
        const badge = await productoPage.obtenerBadgeCarrito();
        expect(badge).toBeLessThanOrEqual(cantidadMaxima);
        
        // 3. Ir al carrito y verificar totales
        await carritoPage.navegarAlCarrito();
        
        const items = await carritoPage.obtenerItemsCarrito();
        expect(items[0].cantidad).toBeLessThanOrEqual(cantidadMaxima);
        
        const totales = await carritoPage.obtenerTotales();
        expect(totales.totalItems).toBeLessThanOrEqual(cantidadMaxima);
        
        // 4. Verificar que los cálculos son correctos para cantidades grandes
        const subtotalNumerico = parseFloat(totales.subtotal.replace('$', '').replace(',', ''));
        expect(subtotalNumerico).toBeGreaterThan(0);
        
        // 5. Proceder al checkout
        await carritoPage.irAlCheckout();
    });

    /**
     * 🧪 USER JOURNEY: Performance y Carga
     * 
     * ESCENARIO: Verificar que el carrito funciona bien bajo carga
     * 
     * FLUJO:
     * 1. Realizar múltiples operaciones rápidas
     * 2. Verificar que no hay race conditions
     * 3. Validar consistencia de datos
     */
    test('⚡ Flujo: Performance - Operaciones rápidas', async ({ page }) => {
        // 1. Agregar múltiples productos rápidamente
        const productos = [
            testData.productos.album1.id,
            testData.productos.album2.id,
            testData.productos.album3.id
        ];
        
        for (const productId of productos) {
            await productoPage.navegarAProducto(productId);
            await productoPage.agregarAlCarrito(1);
            // Sin espera adicional - operaciones rápidas
        }
        
        // 2. Ir al carrito y realizar operaciones rápidas
        await carritoPage.navegarAlCarrito();
        
        const items = await carritoPage.obtenerItemsCarrito();
        expect(items).toHaveLength(productos.length);
        
        // 3. Realizar múltiples cambios de cantidad rápidamente
        for (const item of items) {
            await carritoPage.incrementarCantidad(item.id);
            // Sin espera - probar race conditions
        }
        
        // 4. Esperar que se estabilicen las operaciones
        await carritoPage.esperarActualizacionAjax(3000);
        
        // 5. Verificar consistencia final
        const itemsFinales = await carritoPage.obtenerItemsCarrito();
        expect(itemsFinales).toHaveLength(productos.length);
        
        const totales = await carritoPage.obtenerTotales();
        expect(totales.totalItems).toBeGreaterThan(productos.length); // Incrementos aplicados
    });
});