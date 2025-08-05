/**
 * 🎸 PRODUCTO PAGE OBJECT MODEL
 * 
 * Page Object para la página de detalle de producto (álbum).
 * Maneja todas las interacciones relacionadas con agregar productos al carrito.
 * 
 * RESPONSABILIDADES:
 * - Interactuar con el formulario de agregar al carrito
 * - Manejar controles de cantidad
 * - Validar información del producto
 * - Gestionar mensajes de respuesta
 * 
 * @author Rock Store Testing Team
 * @version 1.0.0
 */

class ProductoPage {
    /**
     * 🏗️ CONSTRUCTOR
     * 
     * @param {import('@playwright/test').Page} page - Instancia de página de Playwright
     */
    constructor(page) {
        this.page = page;
        
        // 🎯 SELECTORES
        this.selectors = {
            // Información del producto
            nombreProducto: 'h1.display-5',
            precio: '.fs-5 .text-primary',
            descripcion: '.lead',
            codigo: '.small:has-text("Código")',
            badges: '.badge',
            
            // Formulario del carrito
            formularioCarrito: '#addToCartForm',
            inputCantidad: '#cantidad',
            btnAgregar: '#addToCartBtn',
            btnText: '.btn-text',
            
            // Controles de cantidad
            btnIncrementar: 'button[onclick="incrementQuantity()"]',
            btnDecrementar: 'button[onclick="decrementQuantity()"]',
            btnFavoritos: 'button[onclick*="addToFavorites"]',
            
            // Mensajes
            mensajeCarrito: '#cartMessage',
            
            // Carrito flotante
            carritoFlotante: '.position-fixed .bg-primary',
            badgeCarrito: '#cartBadge'
        };
    }

    /**
     * 🚀 NAVEGAR A PRODUCTO
     * 
     * Navega a la página de un producto específico.
     * 
     * @param {number} productId - ID del producto
     */
    async navegarAProducto(productId) {
        await this.page.goto(`/album/${productId}`);
        await this.page.waitForLoadState('networkidle');
    }

    /**
     * 📋 OBTENER INFORMACIÓN DEL PRODUCTO
     * 
     * Extrae toda la información visible del producto.
     * 
     * @returns {Promise<Object>} Información del producto
     */
    async obtenerInformacionProducto() {
        const nombre = await this.page.locator(this.selectors.nombreProducto).textContent();
        const precio = await this.page.locator(this.selectors.precio).textContent();
        const descripcion = await this.page.locator(this.selectors.descripcion).textContent();
        
        return {
            nombre: nombre?.trim(),
            precio: precio?.trim(),
            descripcion: descripcion?.trim()
        };
    }

    /**
     * 🔢 ESTABLECER CANTIDAD
     * 
     * Establece la cantidad deseada en el input.
     * 
     * @param {number} cantidad - Cantidad a establecer
     */
    async establecerCantidad(cantidad) {
        await this.page.fill(this.selectors.inputCantidad, cantidad.toString());
    }

    /**
     * ➕ INCREMENTAR CANTIDAD
     * 
     * Usa el botón + para incrementar la cantidad.
     */
    async incrementarCantidad() {
        await this.page.click(this.selectors.btnIncrementar);
    }

    /**
     * ➖ DECREMENTAR CANTIDAD
     * 
     * Usa el botón - para decrementar la cantidad.
     */
    async decrementarCantidad() {
        await this.page.click(this.selectors.btnDecrementar);
    }

    /**
     * 🛒 AGREGAR AL CARRITO
     * 
     * Hace clic en el botón de agregar al carrito y espera la respuesta.
     * 
     * @param {number} cantidad - Cantidad opcional a establecer antes de agregar
     */
    async agregarAlCarrito(cantidad = null) {
        if (cantidad !== null) {
            await this.establecerCantidad(cantidad);
        }
        
        await this.page.click(this.selectors.btnAgregar);
        
        // Esperar a que el botón cambie de estado
        await this.page.waitForFunction(() => {
            const btn = document.querySelector('#addToCartBtn .btn-text');
            return btn && (btn.textContent.includes('Agregando') || btn.textContent.includes('Agregado'));
        }, { timeout: 5000 });
        
        // Esperar a que termine la operación
        await this.page.waitForTimeout(1000);
    }

    /**
     * 📱 OBTENER MENSAJE DEL CARRITO
     * 
     * Obtiene el mensaje mostrado después de agregar al carrito.
     * 
     * @returns {Promise<string|null>} Texto del mensaje
     */
    async obtenerMensajeCarrito() {
        const mensaje = this.page.locator(this.selectors.mensajeCarrito);
        const isVisible = await mensaje.isVisible();
        
        if (!isVisible) return null;
        
        return await mensaje.textContent();
    }

    /**
     * 🔢 OBTENER CANTIDAD ACTUAL
     * 
     * Obtiene la cantidad actualmente establecida en el input.
     * 
     * @returns {Promise<number>} Cantidad actual
     */
    async obtenerCantidadActual() {
        const valor = await this.page.inputValue(this.selectors.inputCantidad);
        return parseInt(valor) || 1;
    }

    /**
     * ❤️ AGREGAR A FAVORITOS
     * 
     * Hace clic en el botón de favoritos.
     */
    async agregarAFavoritos() {
        await this.page.click(this.selectors.btnFavoritos);
    }

    /**
     * 🛒 OBTENER BADGE DEL CARRITO
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
     * ✅ ESPERAR CONFIRMACIÓN DE AGREGADO
     * 
     * Espera a que aparezca la confirmación de que el producto fue agregado.
     * 
     * @returns {Promise<boolean>} True si se confirmó el agregado
     */
    async esperarConfirmacionAgregado() {
        try {
            await this.page.waitForFunction(() => {
                const btn = document.querySelector('#addToCartBtn .btn-text');
                return btn && btn.textContent.includes('¡Agregado!');
            }, { timeout: 5000 });
            return true;
        } catch (error) {
            return false;
        }
    }
}

module.exports = ProductoPage;