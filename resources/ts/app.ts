/**
 * 🎸 ROCK STORE - APLICACIÓN PRINCIPAL
 */

import { ProductoDetalle } from './modules/productos/detalle';

// Inicializar aplicación cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    // Detectar página actual y cargar módulos correspondientes
    const currentPage = getCurrentPage();
    
    switch (currentPage) {
        case 'product-detail':
            new ProductoDetalle();
            break;
        default:
            console.log('Página no reconocida:', currentPage);
    }
});

function getCurrentPage(): string {
    const path = window.location.pathname;
    
    if (path.includes('/album/') || path.includes('/producto/')) {
        return 'product-detail';
    }
    
    if (path.includes('/carrito')) {
        return 'cart';
    }
    
    if (path === '/' || path.includes('/tienda')) {
        return 'catalog';
    }
    
    return 'unknown';
}