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
export declare class ProductComponent implements AlpineComponent {
    private readonly cartService;
    private readonly notificationService;
    readonly name = "product";
    constructor(cartService: ICartService, notificationService: INotificationService);
    /**
     * Datos reactivos del componente
     */
    data(): {
        product: Product | null;
        selectedQuantity: number;
        isAddingToCart: boolean;
        isFavorite: boolean;
        activeImageIndex: number;
        showFullDescription: boolean;
        activeTab: string;
        relatedProductsIndex: number;
        relatedProducts: never[];
        init(): void;
        /**
         * Carga los datos del producto desde el DOM
         */
        loadProductData(): void;
        /**
         * Agrega el producto al carrito
         */
        addToCart(): Promise<void>;
        /**
         * Incrementa la cantidad seleccionada
         */
        incrementQuantity(): void;
        /**
         * Decrementa la cantidad seleccionada
         */
        decrementQuantity(): void;
        /**
         * Toggle favorito
         */
        toggleFavorite(): void;
        /**
         * Cambia la imagen activa
         */
        setActiveImage(index: number): void;
        /**
         * Toggle descripción completa
         */
        toggleDescription(): void;
        /**
         * Cambia la pestaña activa
         */
        setActiveTab(tab: string): void;
        /**
         * Configura el carousel de productos relacionados
         */
        setupCarousel(): void;
        /**
         * Navega en el carousel de relacionados
         */
        navigateRelated(direction: "prev" | "next"): void;
        /**
         * Actualiza la posición del carousel
         */
        updateCarouselPosition(): void;
        /**
         * Configura la revelación progresiva
         */
        setupProgressiveDisclosure(): void;
        /**
         * Muestra feedback de éxito al agregar al carrito
         */
        showAddToCartSuccess(): void;
        /**
         * Formatea el precio
         */
        formatPrice(price: number): string;
        /**
         * Calcula el precio total según la cantidad
         */
        readonly totalPrice: number;
        /**
         * Verifica si se puede agregar al carrito
         */
        readonly canAddToCart: boolean;
    };
}
//# sourceMappingURL=ProductComponent.d.ts.map