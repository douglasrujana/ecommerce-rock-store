/**
 * 🛒 COMPONENTE ALPINE DEL CARRITO
 * 
 * Componente reactivo para el carrito usando Alpine.js + HTMX
 * - Single Responsibility: Solo maneja la UI del carrito
 * - Dependency Inversion: Depende de ICartService
 * - Open/Closed: Extensible para nuevas funcionalidades UI
 */

import type { AlpineComponent, CartComponentData, Cart, CartItem } from '@types/index';
import type { ICartService } from '@services/cart/CartService';
import type { INotificationService } from '@services/notification/NotificationService';

export class CartComponent implements AlpineComponent {
    public readonly name = 'cart';

    constructor(
        private readonly cartService: ICartService,
        private readonly notificationService: INotificationService
    ) {}

    /**
     * Datos reactivos del componente
     */
    public data(): CartComponentData & Record<string, any> {
        return {
            // Estado del carrito
            items: [],
            totalItems: 0,
            totalPrice: 0,
            isLoading: false,
            error: null,

            // Estado UI
            isFloatingCartOpen: false,
            isCheckingOut: false,

            // Métodos del componente
            async init() {
                await this.loadCart();
                this.setupEventListeners();
            },

            /**
             * Carga el carrito inicial
             */
            async loadCart() {
                this.isLoading = true;
                this.error = null;

                try {
                    const cart = await this.cartService.getCart();
                    if (cart) {
                        this.updateCartData(cart);
                    }
                } catch (error) {
                    this.error = 'Error al cargar el carrito';
                    console.error('Error loading cart:', error);
                } finally {
                    this.isLoading = false;
                }
            },

            /**
             * Agrega un producto al carrito
             */
            async addToCart(productId: number, quantity: number = 1) {
                if (this.isLoading) return;

                this.isLoading = true;
                
                try {
                    const success = await this.cartService.addItem(productId, quantity);
                    
                    if (success) {
                        // El evento se maneja automáticamente por el servicio
                        // Solo necesitamos actualizar la UI si es necesario
                        await this.loadCart();
                    } else {
                        this.notificationService.error('Error al agregar el producto');
                    }
                } catch (error) {
                    this.notificationService.error('Error de conexión');
                    console.error('Error adding to cart:', error);
                } finally {
                    this.isLoading = false;
                }
            },

            /**
             * Actualiza la cantidad de un item
             */
            async updateQuantity(itemId: number, quantity: number) {
                if (this.isLoading || quantity < 0) return;

                this.isLoading = true;

                try {
                    const success = await this.cartService.updateQuantity(itemId, quantity);
                    
                    if (success) {
                        await this.loadCart();
                    } else {
                        this.notificationService.error('Error al actualizar la cantidad');
                    }
                } catch (error) {
                    this.notificationService.error('Error de conexión');
                    console.error('Error updating quantity:', error);
                } finally {
                    this.isLoading = false;
                }
            },

            /**
             * Elimina un item del carrito
             */
            async removeItem(itemId: number) {
                if (this.isLoading) return;

                this.isLoading = true;

                try {
                    const success = await this.cartService.removeItem(itemId);
                    
                    if (success) {
                        await this.loadCart();
                    } else {
                        this.notificationService.error('Error al eliminar el producto');
                    }
                } catch (error) {
                    this.notificationService.error('Error de conexión');
                    console.error('Error removing item:', error);
                } finally {
                    this.isLoading = false;
                }
            },

            /**
             * Limpia todo el carrito
             */
            async clearCart() {
                if (this.isLoading || this.items.length === 0) return;

                if (!confirm('¿Estás seguro de que quieres vaciar el carrito?')) {
                    return;
                }

                this.isLoading = true;

                try {
                    const success = await this.cartService.clearCart();
                    
                    if (success) {
                        await this.loadCart();
                    } else {
                        this.notificationService.error('Error al vaciar el carrito');
                    }
                } catch (error) {
                    this.notificationService.error('Error de conexión');
                    console.error('Error clearing cart:', error);
                } finally {
                    this.isLoading = false;
                }
            },

            /**
             * Toggle del carrito flotante
             */
            toggleFloatingCart() {
                this.isFloatingCartOpen = !this.isFloatingCartOpen;
            },

            /**
             * Inicia el proceso de checkout
             */
            async startCheckout() {
                if (this.items.length === 0) {
                    this.notificationService.warning('El carrito está vacío');
                    return;
                }

                this.isCheckingOut = true;
                
                // Redirigir a la página de checkout
                window.location.href = '/checkout';
            },

            /**
             * Actualiza los datos del carrito
             */
            updateCartData(cart: Cart) {
                this.items = cart.items;
                this.totalItems = cart.total_items;
                this.totalPrice = cart.total_precio;
            },

            /**
             * Configura los event listeners
             */
            setupEventListeners() {
                // Escuchar cambios del carrito desde otros componentes
                document.addEventListener('cart:updated', async () => {
                    await this.loadCart();
                });

                // Escuchar eventos HTMX
                document.addEventListener('htmx:afterRequest', (event: any) => {
                    if (event.detail.xhr.responseURL.includes('/carrito')) {
                        this.loadCart();
                    }
                });
            },

            /**
             * Formatea el precio para mostrar
             */
            formatPrice(price: number): string {
                return new Intl.NumberFormat('es-ES', {
                    style: 'currency',
                    currency: 'EUR'
                }).format(price);
            },

            /**
             * Obtiene la imagen del producto
             */
            getProductImage(item: CartItem): string {
                return item.producto.imagen 
                    ? `/uploads/productos/${item.producto.imagen}`
                    : '/images/placeholder-product.jpg';
            },

            /**
             * Calcula el subtotal de un item
             */
            getItemSubtotal(item: CartItem): number {
                return item.cantidad * item.precio_unitario;
            }
        };
    }
}