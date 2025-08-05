/**
 * 🏗️ TIPOS PRINCIPALES DEL SISTEMA
 *
 * Definiciones de tipos centralizadas siguiendo principios SOLID
 */
export interface IService {
    readonly name: string;
    initialize(): Promise<void> | void;
    destroy?(): Promise<void> | void;
}
export interface IRepository<T> {
    find(id: string | number): Promise<T | null>;
    findAll(): Promise<T[]>;
    create(data: Partial<T>): Promise<T>;
    update(id: string | number, data: Partial<T>): Promise<T>;
    delete(id: string | number): Promise<boolean>;
}
export interface IEventBus {
    emit<T = any>(event: string, data?: T): void;
    on<T = any>(event: string, handler: (data: T) => void): void;
    off(event: string, handler?: Function): void;
}
export interface Product {
    readonly id: number;
    readonly nombre: string;
    readonly precio: number;
    readonly imagen?: string;
    readonly descripcion?: string;
    readonly categoria_id?: number;
    readonly stock?: number;
}
export interface CartItem {
    readonly id: number;
    readonly producto_id: number;
    readonly cantidad: number;
    readonly precio_unitario: number;
    readonly producto: Product;
}
export interface Cart {
    readonly items: CartItem[];
    readonly total_items: number;
    readonly total_precio: number;
}
export interface ApiResponse<T = any> {
    readonly success: boolean;
    readonly data?: T;
    readonly mensaje?: string;
    readonly errors?: Record<string, string[]>;
}
export interface CartResponse extends ApiResponse<Cart> {
    readonly cart: Cart;
}
export interface AppConfig {
    readonly apiBaseUrl: string;
    readonly csrfToken: string;
    readonly debug: boolean;
}
export interface HTMXConfig {
    readonly timeout: number;
    readonly retryCount: number;
    readonly defaultHeaders: Record<string, string>;
}
export interface CartEvent {
    readonly type: 'item_added' | 'item_removed' | 'item_updated' | 'cart_cleared';
    readonly payload: {
        readonly item?: CartItem;
        readonly quantity?: number;
        readonly cart?: Cart;
    };
}
export interface NotificationEvent {
    readonly type: 'success' | 'error' | 'warning' | 'info';
    readonly message: string;
    readonly duration?: number;
}
export interface AlpineComponent {
    readonly name: string;
    readonly data: () => Record<string, any>;
    readonly methods?: Record<string, Function>;
}
export interface CartComponentData {
    items: CartItem[];
    totalItems: number;
    totalPrice: number;
    isLoading: boolean;
    error: string | null;
}
export type Nullable<T> = T | null;
export type Optional<T> = T | undefined;
export type DeepReadonly<T> = {
    readonly [P in keyof T]: T[P] extends object ? DeepReadonly<T[P]> : T[P];
};
export declare enum NotificationType {
    SUCCESS = "success",
    ERROR = "error",
    WARNING = "warning",
    INFO = "info"
}
export declare enum CartActionType {
    ADD_ITEM = "ADD_ITEM",
    REMOVE_ITEM = "REMOVE_ITEM",
    UPDATE_QUANTITY = "UPDATE_QUANTITY",
    CLEAR_CART = "CLEAR_CART"
}
export declare enum LoadingState {
    IDLE = "idle",
    LOADING = "loading",
    SUCCESS = "success",
    ERROR = "error"
}
//# sourceMappingURL=index.d.ts.map