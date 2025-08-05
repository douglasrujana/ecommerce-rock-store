/**
 * 🛒 COMPONENTE ALPINE DEL CARRITO
 *
 * Componente reactivo para el carrito usando Alpine.js + HTMX
 * - Single Responsibility: Solo maneja la UI del carrito
 * - Dependency Inversion: Depende de ICartService
 * - Open/Closed: Extensible para nuevas funcionalidades UI
 */
import type { AlpineComponent, CartComponentData } from '@types/index';
import type { ICartService } from '@services/cart/CartService';
import type { INotificationService } from '@services/notification/NotificationService';
export declare class CartComponent implements AlpineComponent {
    private readonly cartService;
    private readonly notificationService;
    readonly name = "cart";
    constructor(cartService: ICartService, notificationService: INotificationService);
    /**
     * Datos reactivos del componente
     */
    data(): CartComponentData & Record<string, any>;
}
//# sourceMappingURL=CartComponent.d.ts.map