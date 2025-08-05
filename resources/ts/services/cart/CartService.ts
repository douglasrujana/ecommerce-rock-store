/**
 * 🛒 SERVICIO DEL CARRITO
 * 
 * Maneja la lógica de negocio del carrito siguiendo principios SOLID
 * - Single Responsibility: Solo maneja operaciones del carrito
 * - Dependency Inversion: Depende de abstracciones (IApiService)
 * - Open/Closed: Extensible para nuevas funcionalidades del carrito
 */

import type { 
    IService, 
    Cart, 
    CartItem, 
    CartResponse, 
    ApiResponse,
    IEventBus 
} from '@types/index';
import type { IApiService } from '@services/api/ApiService';

export interface ICartService extends IService {
    getCart(): Promise<Cart | null>;
    addItem(productId: number, quantity: number): Promise<boolean>;
    updateQuantity(itemId: number, quantity: number): Promise<boolean>;
    removeItem(itemId: number): Promise<boolean>;
    clearCart(): Promise<boolean>;
    getItemCount(): Promise<number>;
}

export class CartService implements ICartService {
    public readonly name = 'CartService';
    private cart: Cart | null = null;

    constructor(
        private readonly apiService: IApiService,
        private readonly eventBus: IEventBus
    ) {}

    public async initialize(): Promise<void> {
        await this.loadCart();
        console.log('🛒 CartService initialized');
    }

    /**
     * Obtiene el carrito actual
     */
    public async getCart(): Promise<Cart | null> {
        if (!this.cart) {
            await this.loadCart();
        }
        return this.cart;
    }

    /**
     * Agrega un item al carrito
     */
    public async addItem(productId: number, quantity: number = 1): Promise<boolean> {
        try {
            const response = await this.apiService.post<CartResponse>('/api/carrito/agregar', {
                producto_id: productId,
                cantidad: quantity
            });

            if (response.success && response.data) {
                this.cart = response.data.cart;
                this.emitCartEvent('item_added', { cart: this.cart });
                return true;
            }

            return false;
        } catch (error) {
            console.error('Error adding item to cart:', error);
            return false;
        }
    }

    /**
     * Actualiza la cantidad de un item
     */
    public async updateQuantity(itemId: number, quantity: number): Promise<boolean> {
        if (quantity <= 0) {
            return this.removeItem(itemId);
        }

        try {
            const response = await this.apiService.put<CartResponse>(`/api/carrito/actualizar/${itemId}`, {
                cantidad: quantity
            });

            if (response.success && response.data) {
                this.cart = response.data.cart;
                this.emitCartEvent('item_updated', { cart: this.cart });
                return true;
            }

            return false;
        } catch (error) {
            console.error('Error updating cart item:', error);
            return false;
        }
    }

    /**
     * Elimina un item del carrito
     */
    public async removeItem(itemId: number): Promise<boolean> {
        try {
            const response = await this.apiService.delete<CartResponse>(`/api/carrito/eliminar/${itemId}`);

            if (response.success && response.data) {
                this.cart = response.data.cart;
                this.emitCartEvent('item_removed', { cart: this.cart });
                return true;
            }

            return false;
        } catch (error) {
            console.error('Error removing cart item:', error);
            return false;
        }
    }

    /**
     * Limpia el carrito completamente
     */
    public async clearCart(): Promise<boolean> {
        try {
            const response = await this.apiService.delete<CartResponse>('/api/carrito/limpiar');

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
        } catch (error) {
            console.error('Error clearing cart:', error);
            return false;
        }
    }

    /**
     * Obtiene el número total de items en el carrito
     */
    public async getItemCount(): Promise<number> {
        const cart = await this.getCart();
        return cart?.total_items || 0;
    }

    /**
     * Carga el carrito desde el servidor
     */
    private async loadCart(): Promise<void> {
        try {
            const response = await this.apiService.get<Cart>('/api/carrito');
            
            if (response.success && response.data) {
                this.cart = response.data;
            } else {
                this.cart = {
                    items: [],
                    total_items: 0,
                    total_precio: 0
                };
            }
        } catch (error) {
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
    private emitCartEvent(type: 'item_added' | 'item_removed' | 'item_updated' | 'cart_cleared', payload: any): void {
        this.eventBus.emit('cart:changed', {
            type,
            payload
        });
    }
}