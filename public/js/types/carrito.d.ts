/**
 * 🛒 TIPOS PARA EL CARRITO
 */
export interface CartItem {
    id: number;
    nombre: string;
    precio: number;
    cantidad: number;
    imagen?: string;
    categoria?: string;
    decada?: string;
    año?: number;
    subtotal?: number;
}
export interface CartResponse {
    success: boolean;
    mensaje: string;
    total_items?: number;
    total_precio?: string;
    producto?: CartItem;
}
export interface CartTotals {
    subtotal: string;
    impuestos: string;
    total: string;
    totalItems: number;
    productosUnicos: number;
    subtotal_raw: number;
    impuestos_raw: number;
    total_raw: number;
}
export interface CartBadgeData {
    total_items: number;
    total_productos: number;
    carrito_vacio: boolean;
}
//# sourceMappingURL=carrito.d.ts.map