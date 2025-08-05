/**
 * 🎵 COMPONENTE ALPINE DEL PRODUCTO
 * 
 * Componente reactivo para la página de producto usando Alpine.js + HTMX
 * - Single Responsibility: Solo maneja la UI del producto
 * - Dependency Inversion: Depende de servicios abstractos
 * - Open/Closed: Extensible para nuevas funcionalidades
 */

import type { AlpineComponent, Product } from '@types/index';
import type { ICartService } from '@services/cart/CartService';
import type { INotificationService } from '@services/notification/NotificationService';

export class ProductComponent implements AlpineComponent {
    public readonly name = 'product';

    constructor(
        private readonly cartService: ICartService,
        private readonly notificationService: INotificationService
    ) {}

    /**
     * Datos reactivos del componente
     */
    public data() {
        return {
            // Estado del producto
            product: null as Product | null,
            selectedQuantity: 1,
            isAddingToCart: false,
            isFavorite: false,

            // Estado UI
            activeImageIndex: 0,
            showFullDescription: false,
            activeTab: 'description',

            // Carousel relacionados
            relatedProductsIndex: 0,
            relatedProducts: [],

            // Métodos del componente
            init() {
                this.loadProductData();
                this.setupCarousel();
                this.setupProgressiveDisclosure();
            },

            /**
             * Carga los datos del producto desde el DOM
             */
            loadProductData() {
                const productElement = document.querySelector('[data-product-id]');
                if (productElement) {
                    const productId = productElement.getAttribute('data-product-id');
                    const productName = productElement.getAttribute('data-product-name');
                    const productPrice = parseFloat(productElement.getAttribute('data-product-price') || '0');
                    
                    this.product = {
                        id: parseInt(productId || '0'),
                        nombre: productName || '',
                        precio: productPrice,
                        imagen: productElement.getAttribute('data-product-image') || undefined
                    };
                }
            },

            /**
             * Agrega el producto al carrito
             */
            async addToCart() {
                if (!this.product || this.isAddingToCart) return;

                this.isAddingToCart = true;

                try {
                    const success = await this.cartService.addItem(
                        this.product.id, 
                        this.selectedQuantity
                    );

                    if (success) {
                        // Mostrar feedback visual
                        this.showAddToCartSuccess();
                        
                        // Emitir evento para actualizar otros componentes
                        document.dispatchEvent(new CustomEvent('cart:updated'));
                    } else {
                        this.notificationService.error('Error al agregar al carrito');
                    }
                } catch (error) {
                    this.notificationService.error('Error de conexión');
                    console.error('Error adding to cart:', error);
                } finally {
                    this.isAddingToCart = false;
                }
            },

            /**
             * Incrementa la cantidad seleccionada
             */
            incrementQuantity() {
                if (this.selectedQuantity < 10) {
                    this.selectedQuantity++;
                }
            },

            /**
             * Decrementa la cantidad seleccionada
             */
            decrementQuantity() {
                if (this.selectedQuantity > 1) {
                    this.selectedQuantity--;
                }
            },

            /**
             * Toggle favorito
             */
            toggleFavorite() {
                this.isFavorite = !this.isFavorite;
                
                const message = this.isFavorite 
                    ? '❤️ Agregado a favoritos' 
                    : '💔 Eliminado de favoritos';
                    
                this.notificationService.info(message);
            },

            /**
             * Cambia la imagen activa
             */
            setActiveImage(index: number) {
                this.activeImageIndex = index;
            },

            /**
             * Toggle descripción completa
             */
            toggleDescription() {
                this.showFullDescription = !this.showFullDescription;
            },

            /**
             * Cambia la pestaña activa
             */
            setActiveTab(tab: string) {
                this.activeTab = tab;
            },

            /**
             * Configura el carousel de productos relacionados
             */
            setupCarousel() {
                const relatedContainer = document.querySelector('.related-products-track');
                if (relatedContainer) {
                    const items = relatedContainer.querySelectorAll('.related-product-item');
                    this.relatedProducts = Array.from(items);
                }
            },

            /**
             * Navega en el carousel de relacionados
             */
            navigateRelated(direction: 'prev' | 'next') {
                const maxIndex = Math.max(0, this.relatedProducts.length - 4);
                
                if (direction === 'next' && this.relatedProductsIndex < maxIndex) {
                    this.relatedProductsIndex++;
                } else if (direction === 'prev' && this.relatedProductsIndex > 0) {
                    this.relatedProductsIndex--;
                }

                this.updateCarouselPosition();
            },

            /**
             * Actualiza la posición del carousel
             */
            updateCarouselPosition() {
                const track = document.querySelector('.related-products-track') as HTMLElement;
                if (track) {
                    const translateX = -this.relatedProductsIndex * 25; // 25% por item
                    track.style.transform = `translateX(${translateX}%)`;
                }
            },

            /**
             * Configura la revelación progresiva
             */
            setupProgressiveDisclosure() {
                const sections = document.querySelectorAll('.info-section');
                
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('section-visible');
                        }
                    });
                }, {
                    threshold: 0.1,
                    rootMargin: '50px'
                });

                sections.forEach(section => observer.observe(section));
            },

            /**
             * Muestra feedback de éxito al agregar al carrito
             */
            showAddToCartSuccess() {
                const button = document.getElementById('addToCartBtn');
                if (button) {
                    const originalText = button.textContent;
                    button.textContent = '✓ ¡Agregado!';
                    button.classList.add('btn-success');

                    setTimeout(() => {
                        button.textContent = originalText;
                        button.classList.remove('btn-success');
                    }, 2000);
                }
            },

            /**
             * Formatea el precio
             */
            formatPrice(price: number): string {
                return new Intl.NumberFormat('es-ES', {
                    style: 'currency',
                    currency: 'EUR'
                }).format(price);
            },

            /**
             * Calcula el precio total según la cantidad
             */
            get totalPrice(): number {
                return this.product ? this.product.precio * this.selectedQuantity : 0;
            },

            /**
             * Verifica si se puede agregar al carrito
             */
            get canAddToCart(): boolean {
                return this.product !== null && 
                       this.selectedQuantity > 0 && 
                       !this.isAddingToCart;
            }
        };
    }
}