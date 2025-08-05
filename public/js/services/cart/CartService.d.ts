/**
 * 🛒 SERVICIO DEL CARRITO
 *
 * Maneja la lógica de negocio del carrito siguiendo principios SOLID
 * - Single Responsibility: Solo maneja operaciones del carrito
 * - Dependency Inversion: Depende de abstracciones (IApiService)
 * - Open/Closed: Extensible para nuevas funcionalidades del carrito
 */
import type { IService, Cart, IEventBus } from '@types/index';
import type { IApiService } from '@services/api/ApiService';
export interface ICartService extends IService {
    getCart(): Promise<Cart | null>;
    addItem(productId: number, quantity: number): Promise<boolean>;
    updateQuantity(itemId: number, quantity: number): Promise<boolean>;
    removeItem(itemId: number): Promise<boolean>;
    clearCart(): Promise<boolean>;
    getItemCount(): Promise<number>;
}
export declare class CartService implements ICartService {
    private readonly apiService;
    private readonly eventBus;
    readonly name = "CartService";
    private cart;
    constructor(apiService: IApiService, eventBus: IEventBus);
    initialize(): Promise<void>;
    /**
     * Obtiene el carrito actual
     */
    getCart(): Promise<Cart | null>;
    /**
     * Agrega un item al carrito
     */
    addItem(productId: number, quantity?: number): Promise<boolean>;
    /**
     * Actualiza la cantidad de un item
     */
    updateQuantity(itemId: number, quantity: number): Promise<boolean>;
    /**
     * Elimina un item del carrito
     */
    removeItem(itemId: number): Promise<boolean>;
    /**
     * Limpia el carrito completamente
     */
    clearCart(): Promise<boolean>;
    /**
     * Obtiene el número total de items en el carrito
     */
    getItemCount(): Promise<number>;
    /**
     * Carga el carrito desde el servidor
     */
    private loadCart;
    /**
     * Emite eventos del carrito
     */
    private emitCartEvent;
}
//# sourceMappingURL=CartService.d.ts.map