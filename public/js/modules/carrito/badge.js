/**
 * 🛒 GESTIÓN DEL BADGE DEL CARRITO
 */

export class CartBadge {
    constructor() {
        this.badgeElement = document.getElementById('cartBadge');
        this.init();
    }

    init() {
        this.updateBadge();
    }

    async updateBadge() {
        try {
            const response = await fetch('/api/carrito/contar');
            const data = await response.json();
            
            if (this.badgeElement) {
                this.setBadgeCount(data.total_items);
            }
        } catch (error) {
            console.error('Error updating cart badge:', error);
        }
    }

    setBadgeCount(count) {
        if (this.badgeElement) {
            this.badgeElement.textContent = count;
            
            // Aplicar clases del sistema Jony Ive
            if (count === 0) {
                this.badgeElement.classList.add('empty');
                this.badgeElement.classList.remove('updated');
            } else {
                this.badgeElement.classList.remove('empty');
                this.badgeElement.classList.add('updated');
                
                // Remover la clase de animación después de la animación
                setTimeout(() => {
                    this.badgeElement.classList.remove('updated');
                }, 600);
            }
        }
    }
}