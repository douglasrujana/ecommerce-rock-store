/**
 * 🛒 SERVICIO DEL CARRITO
 *
 * Maneja la lógica de negocio del carrito siguiendo principios SOLID
 * - Single Responsibility: Solo maneja operaciones del carrito
 * - Dependency Inversion: Depende de abstracciones (IApiService)
 * - Open/Closed: Extensible para nuevas funcionalidades del carrito
 */
export class CartService {
    constructor(apiService, eventBus) {
        this.apiService = apiService;
        this.eventBus = eventBus;
        this.name = 'CartService';
        this.cart = null;
    }
    async initialize() {
        await this.loadCart();
        console.log('🛒 CartService initialized');
    }
    /**
     * Obtiene el carrito actual
     */
    async getCart() {
        if (!this.cart) {
            await this.loadCart();
        }
        return this.cart;
    }
    /**
     * Agrega un item al carrito
     */
    async addItem(productId, quantity = 1) {
        try {
            const response = await this.apiService.post('/api/carrito/agregar', {
                producto_id: productId,
                cantidad: quantity
            });
            if (response.success && response.data) {
                this.cart = response.data.cart;
                this.emitCartEvent('item_added', { cart: this.cart });
                return true;
            }
            return false;
        }
        catch (error) {
            console.error('Error adding item to cart:', error);
            return false;
        }
    }
    /**
     * Actualiza la cantidad de un item
     */
    async updateQuantity(itemId, quantity) {
        if (quantity <= 0) {
            return this.removeItem(itemId);
        }
        try {
            const response = await this.apiService.put(`/api/carrito/actualizar/${itemId}`, {
                cantidad: quantity
            });
            if (response.success && response.data) {
                this.cart = response.data.cart;
                this.emitCartEvent('item_updated', { cart: this.cart });
                return true;
            }
            return false;
        }
        catch (error) {
            console.error('Error updating cart item:', error);
            return false;
        }
    }
    /**
     * Elimina un item del carrito
     */
    async removeItem(itemId) {
        try {
            const response = await this.apiService.delete(`/api/carrito/eliminar/${itemId}`);
            if (response.success && response.data) {
                this.cart = response.data.cart;
                this.emitCartEvent('item_removed', { cart: this.cart });
                return true;
            }
            return false;
        }
        catch (error) {
            console.error('Error removing cart item:', error);
            return false;
        }
    }
    /**
     * Limpia el carrito completamente
     */
    async clearCart() {
        try {
            const response = await this.apiService.delete('/api/carrito/limpiar');
            if (response.success) {
                this.cart = {
                    items: [],
                    total_items: 0,
                    total_precio: 0
                };
                this.emitCartEvent('cart_cleared', { cart: this.cart });
                return true;
            }
            return false;
        }
        catch (error) {
            console.error('Error clearing cart:', error);
            return false;
        }
    }
    /**
     * Obtiene el número total de items en el carrito
     */
    async getItemCount() {
        const cart = await this.getCart();
        return cart?.total_items || 0;
    }
    /**
     * Carga el carrito desde el servidor
     */
    async loadCart() {
        try {
            const response = await this.apiService.get('/api/carrito');
            if (response.success && response.data) {
                this.cart = response.data;
            }
            else {
                this.cart = {
                    items: [],
                    total_items: 0,
                    total_precio: 0
                };
            }
        }
        catch (error) {
            console.error('Error loading cart:', error);
            this.cart = {
                items: [],
                total_items: 0,
                total_precio: 0
            };
        }
    }
    /**
     * Emite eventos del carrito
     */
    emitCartEvent(type, payload) {
        this.eventBus.emit('cart:changed', {
            type,
            payload
        });
    }
}
//# sourceMappingURL=CartService.js.map