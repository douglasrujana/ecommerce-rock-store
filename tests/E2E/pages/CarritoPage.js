/**
 * 🛒 CARRITO PAGE OBJECT MODEL
 * 
 * Page Object que encapsula todas las interacciones con el carrito de compras.
 * Implementa el patrón Page Object Model para mantener las pruebas mantenibles
 * y reutilizables.
 * 
 * RESPONSABILIDADES:
 * - Encapsular selectores y acciones del carrito
 * - Proporcionar métodos de alto nivel para las pruebas
 * - Manejar esperas y validaciones específicas del carrito
 * - Abstraer la complejidad de la interfaz de usuario
 * 
 * @author Rock Store Testing Team
 * @version 1.0.0
 */

class CarritoPage {
    /**
     * 🏗️ CONSTRUCTOR - Inicializa la página del carrito
     * 
     * @param {import('@playwright/test').Page} page - Instancia de página de Playwright
     */
    constructor(page) {
        this.page = page;
        
        // 🎯 SELECTORES PRINCIPALES
        this.selectors = {
            // Carrito flotante
            carritoFlotante: '[data-testid="carrito-flotante"], .position-fixed .bg-primary',
            badgeCarrito: '#cartBadge, [data-testid="cart-badge"]',
            
            // Página del carrito
            tituloCarrito: 'h2:has-text("Tu Carrito Musical")',
            carritoVacio: 'text=Tu carrito está vacío',
            explorarCatalogo: 'text=Explorar Catálogo Musical',
            
            // Items del carrito
            itemCarrito: '.cart-item',
            nombreProducto: '.cart-item h6',
            precioUnitario: '.cart-item .text-primary',
            inputCantidad: '.qty-input',
            btnIncrementar: '.qty-btn[data-action="increment"]',
            btnDecrementar: '.qty-btn[data-action="decrement"]',
            btnEliminar: '.remove-item',
            subtotalItem: '.subtotal',
            
            // Resumen del pedido
            resumenPedido: '.card:has-text("Resumen del Pedido")',
            subtotalGeneral: '#subtotalAmount',
            impuestos: '#taxAmount',
            totalGeneral: '#totalAmount',
            totalItems: '#totalAlbums',
            productosUnicos: '#uniqueProducts',
            
            // Botones de acción
            btnVaciarCarrito: '#clearCartBtn',
            btnVerificar: '#verifyCartBtn',
            btnCheckout: '#checkoutBtn',
            btnSeguirComprando: 'text=Seguir Comprando',
            
            // Mensajes
            mensajes: '#cartMessages .alert',
            mensajeExito: '.alert-success',
            mensajeError: '.alert-danger',
            mensajeAdvertencia: '.alert-warning'
        };
    }

    /**
     * 🚀 NAVEGAR AL CARRITO
     * 
     * Navega directamente a la página del carrito.
     * Útil para pruebas que necesitan empezar desde el carrito.
     */
    async navegarAlCarrito() {
        await this.page.goto('/carrito');
        await this.page.waitForLoadState('networkidle');
    }

    /**
     * 🖱️ HACER CLIC EN CARRITO FLOTANTE
     * 
     * Hace clic en el botón flotante del carrito para navegar a la página.
     * Simula el comportamiento real del usuario.
     */
    async hacerClicCarritoFlotante() {
        await this.page.click(this.selectors.carritoFlotante);
        await this.page.waitForURL('**/carrito');
    }

    /**
     * 📊 OBTENER BADGE DEL CARRITO
     * 
     * Obtiene el número mostrado en el badge del carrito flotante.
     * 
     * @returns {Promise<number>} Número de items en el badge
     */
    async obtenerBadgeCarrito() {
        const badge = this.page.locator(this.selectors.badgeCarrito);
        const isVisible = await badge.isVisible();
        
        if (!isVisible) return 0;
        
        const texto = await badge.textContent();
        return parseInt(texto) || 0;
    }

    /**
     * ❓ VERIFICAR SI CARRITO ESTÁ VACÍO
     * 
     * Verifica si el carrito muestra el estado vacío.
     * 
     * @returns {Promise<boolean>} True si el carrito está vacío
     */
    async estaCarritoVacio() {
        return await this.page.locator(this.selectors.carritoVacio).isVisible();
    }

    /**
     * 📋 OBTENER ITEMS DEL CARRITO
     * 
     * Obtiene información de todos los items en el carrito.
     * 
     * @returns {Promise<Array>} Array con información de cada item
     */
    async obtenerItemsCarrito() {
        const items = await this.page.locator(this.selectors.itemCarrito).all();
        const itemsInfo = [];

        for (const item of items) {
            const productId = await item.getAttribute('data-product-id');
            const nombre = await item.locator('h6').textContent();
            const precio = await item.locator('.text-primary').textContent();
            const cantidad = await item.locator('.qty-input').inputValue();
            const subtotal = await item.locator('.subtotal').textContent();

            itemsInfo.push({
                id: productId,
                nombre: nombre?.trim(),
                precio: precio?.trim(),
                cantidad: parseInt(cantidad),
                subtotal: subtotal?.trim()
            });
        }

        return itemsInfo;
    }

    /**
     * ➕ INCREMENTAR CANTIDAD
     * 
     * Incrementa la cantidad de un producto específico.
     * 
     * @param {string} productId - ID del producto
     */
    async incrementarCantidad(productId) {
        const btn = this.page.locator(`${this.selectors.btnIncrementar}[data-product-id="${productId}"]`);
        await btn.click();
        await this.page.waitForTimeout(500); // Esperar actualización AJAX
    }

    /**
     * ➖ DECREMENTAR CANTIDAD
     * 
     * Decrementa la cantidad de un producto específico.
     * 
     * @param {string} productId - ID del producto
     */
    async decrementarCantidad(productId) {
        const btn = this.page.locator(`${this.selectors.btnDecrementar}[data-product-id="${productId}"]`);
        await btn.click();
        await this.page.waitForTimeout(500); // Esperar actualización AJAX
    }

    /**
     * 🔢 CAMBIAR CANTIDAD DIRECTAMENTE
     * 
     * Cambia la cantidad escribiendo directamente en el input.
     * 
     * @param {string} productId - ID del producto
     * @param {number} cantidad - Nueva cantidad
     */
    async cambiarCantidad(productId, cantidad) {
        const input = this.page.locator(`${this.selectors.inputCantidad}[data-product-id="${productId}"]`);
        await input.fill(cantidad.toString());
        await input.blur(); // Trigger change event
        await this.page.waitForTimeout(500); // Esperar actualización AJAX
    }

    /**
     * 🗑️ ELIMINAR PRODUCTO
     * 
     * Elimina un producto específico del carrito.
     * 
     * @param {string} productId - ID del producto a eliminar
     */
    async eliminarProducto(productId) {
        const btn = this.page.locator(`${this.selectors.btnEliminar}[data-product-id="${productId}"]`);
        
        // Manejar el diálogo de confirmación
        this.page.on('dialog', async dialog => {
            await dialog.accept();
        });
        
        await btn.click();
        await this.page.waitForTimeout(1000); // Esperar eliminación
    }

    /**
     * 🧹 VACIAR CARRITO COMPLETO
     * 
     * Vacía completamente el carrito usando el botón correspondiente.
     */
    async vaciarCarrito() {
        // Manejar el diálogo de confirmación
        this.page.on('dialog', async dialog => {
            await dialog.accept();
        });
        
        await this.page.click(this.selectors.btnVaciarCarrito);
        await this.page.waitForLoadState('networkidle');
    }

    /**
     * 🔍 VERIFICAR CARRITO
     * 
     * Hace clic en el botón de verificar disponibilidad.
     */
    async verificarCarrito() {
        await this.page.click(this.selectors.btnVerificar);
        await this.page.waitForTimeout(1000); // Esperar verificación
    }

    /**
     * 💰 OBTENER TOTALES DEL CARRITO
     * 
     * Obtiene todos los totales mostrados en el resumen.
     * 
     * @returns {Promise<Object>} Objeto con todos los totales
     */
    async obtenerTotales() {
        const subtotal = await this.page.locator(this.selectors.subtotalGeneral).textContent();
        const impuestos = await this.page.locator(this.selectors.impuestos).textContent();
        const total = await this.page.locator(this.selectors.totalGeneral).textContent();
        const totalItems = await this.page.locator(this.selectors.totalItems).textContent();
        const productosUnicos = await this.page.locator(this.selectors.productosUnicos).textContent();

        return {
            subtotal: subtotal?.trim(),
            impuestos: impuestos?.trim(),
            total: total?.trim(),
            totalItems: parseInt(totalItems) || 0,
            productosUnicos: parseInt(productosUnicos) || 0
        };
    }

    /**
     * 📱 OBTENER ÚLTIMO MENSAJE
     * 
     * Obtiene el último mensaje mostrado al usuario.
     * 
     * @returns {Promise<string|null>} Texto del mensaje o null si no hay
     */
    async obtenerUltimoMensaje() {
        const mensajes = this.page.locator(this.selectors.mensajes);
        const count = await mensajes.count();
        
        if (count === 0) return null;
        
        const ultimoMensaje = mensajes.nth(count - 1);
        return await ultimoMensaje.textContent();
    }

    /**
     * ✅ ESPERAR MENSAJE DE ÉXITO
     * 
     * Espera a que aparezca un mensaje de éxito específico.
     * 
     * @param {string} textoEsperado - Texto esperado en el mensaje
     */
    async esperarMensajeExito(textoEsperado) {
        await this.page.waitForSelector(this.selectors.mensajeExito, { timeout: 5000 });
        const mensaje = await this.page.locator(this.selectors.mensajeExito).textContent();
        return mensaje?.includes(textoEsperado);
    }

    /**
     * 🛍️ IR AL CHECKOUT
     * 
     * Hace clic en el botón de proceder al pago.
     */
    async irAlCheckout() {
        await this.page.click(this.selectors.btnCheckout);
    }

    /**
     * 🔙 SEGUIR COMPRANDO
     * 
     * Hace clic en el botón de seguir comprando.
     */
    async seguirComprando() {
        await this.page.click(this.selectors.btnSeguirComprando);
        await this.page.waitForURL('**/');
    }

    /**
     * 🔄 ESPERAR ACTUALIZACIÓN AJAX
     * 
     * Espera a que se complete una operación AJAX del carrito.
     * 
     * @param {number} timeout - Tiempo máximo de espera en ms
     */
    async esperarActualizacionAjax(timeout = 2000) {
        await this.page.waitForTimeout(timeout);
        await this.page.waitForLoadState('networkidle');
    }
}

module.exports = CarritoPage;