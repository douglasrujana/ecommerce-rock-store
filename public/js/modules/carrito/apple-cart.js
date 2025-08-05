/**
 * 🛒 APPLE STORE CART MODULE
 * Gestión completa del carrito con UX Apple Store
 */

import { QuantitySelector } from '../productos/quantity-selector.js';

export class AppleCart {
    constructor() {
        this.isUpdating = false;
        this.quantitySelector = new QuantitySelector('.quantity-selector');
        this.init();
    }

    init() {
        this.bindEvents();
        this.setupQuantityListeners();
    }

    bindEvents() {
        // Remove item buttons
        document.querySelectorAll('.cart-item-remove').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const productId = e.target.closest('.cart-item-remove').dataset.productId;
                this.removeItem(productId);
            });
        });

        // Clear cart button
        const clearBtn = document.getElementById('clearCartBtn');
        if (clearBtn) {
            clearBtn.addEventListener('click', () => this.clearCart());
        }

        // Checkout button
        const checkoutBtn = document.getElementById('checkoutBtn');
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', () => this.proceedToCheckout());
        }
    }

    setupQuantityListeners() {
        // Listen for quantity changes from QuantitySelector
        document.addEventListener('quantityChanged', (e) => {
            const { value, input } = e.detail;
            const productId = input.dataset.productId;
            this.updateQuantity(productId, value);
        });
    }

    async updateQuantity(productId, newQuantity) {
        if (this.isUpdating) return;
        
        this.isUpdating = true;
        const cartItem = document.querySelector(`.cart-item[data-product-id="${productId}"]`);
        
        // Visual feedback
        cartItem.classList.add('updating');
        
        try {
            const response = await fetch('/carrito/actualizar', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    id: productId,
                    cantidad: newQuantity,
                    accion: 'set'
                })
            });

            const data = await response.json();

            if (data.success) {
                // Update subtotal
                const subtotalElement = cartItem.querySelector('.cart-item-subtotal');
                if (subtotalElement) {
                    subtotalElement.textContent = `$${data.total_item}`;
                }

                // Update totals
                this.updateTotals();
                this.showNotification('Cantidad actualizada', 'success');
            } else {
                this.showNotification(data.mensaje || 'Error al actualizar cantidad', 'error');
                // Revert quantity
                const input = cartItem.querySelector('.quantity-input');
                if (input) {
                    input.value = input.dataset.originalValue || 1;
                }
            }
        } catch (error) {
            console.error('Error updating quantity:', error);
            this.showNotification('Error de conexión', 'error');
        } finally {
            cartItem.classList.remove('updating');
            this.isUpdating = false;
        }
    }

    async removeItem(productId) {
        const cartItem = document.querySelector(`.cart-item[data-product-id="${productId}"]`);
        if (!cartItem) return;

        // Confirmation with Apple-style dialog
        if (!confirm('¿Quitar este álbum de tu carrito?')) return;

        // Visual feedback
        cartItem.classList.add('removing');

        try {
            const response = await fetch('/carrito/eliminar', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ id: productId })
            });

            const data = await response.json();

            if (data.success) {
                // Remove from DOM after animation
                setTimeout(() => {
                    cartItem.remove();
                    
                    // Check if cart is empty
                    if (data.carrito_vacio) {
                        location.reload();
                    } else {
                        this.updateTotals();
                        this.updateCartSummary();
                    }
                }, 300);

                this.showNotification('Álbum eliminado del carrito', 'success');
            } else {
                cartItem.classList.remove('removing');
                this.showNotification(data.mensaje || 'Error al eliminar producto', 'error');
            }
        } catch (error) {
            console.error('Error removing item:', error);
            cartItem.classList.remove('removing');
            this.showNotification('Error de conexión', 'error');
        }
    }

    async clearCart() {
        if (!confirm('¿Estás seguro de que quieres vaciar tu carrito?')) return;

        try {
            const response = await fetch('/carrito/vaciar', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                location.reload();
            } else {
                this.showNotification('Error al vaciar carrito', 'error');
            }
        } catch (error) {
            console.error('Error clearing cart:', error);
            this.showNotification('Error de conexión', 'error');
        }
    }

    async updateTotals() {
        try {
            const response = await fetch('/api/carrito/total');
            const data = await response.json();

            // Update summary
            const subtotalEl = document.getElementById('subtotalAmount');
            const taxEl = document.getElementById('taxAmount');
            const totalEl = document.getElementById('totalAmount');
            const previewEl = document.getElementById('cartTotalPreview');

            if (subtotalEl) subtotalEl.textContent = `$${data.subtotal}`;
            if (taxEl) taxEl.textContent = `$${data.impuestos}`;
            if (totalEl) totalEl.textContent = `$${data.total}`;
            if (previewEl) previewEl.textContent = `$${data.total}`;

            // Update cart badges
            this.updateCartBadges(data.total_items);
        } catch (error) {
            console.error('Error updating totals:', error);
        }
    }

    updateCartSummary() {
        const cartItems = document.querySelectorAll('.cart-item');
        const totalItems = Array.from(cartItems).reduce((sum, item) => {
            const input = item.querySelector('.quantity-input');
            return sum + (parseInt(input.value) || 0);
        }, 0);

        const summaryText = document.getElementById('cartSummaryText');
        if (summaryText) {
            summaryText.textContent = `${totalItems} álbum${totalItems !== 1 ? 'es' : ''} en tu colección`;
        }
    }

    async updateCartBadges(totalItems) {
        const badges = document.querySelectorAll('#cartBadge, #navCartBadge');
        badges.forEach(badge => {
            badge.textContent = totalItems;
            
            if (totalItems === 0) {
                badge.classList.add('empty');
            } else {
                badge.classList.remove('empty');
                badge.classList.add('updated');
                
                setTimeout(() => {
                    badge.classList.remove('updated');
                }, 600);
            }
        });
    }

    proceedToCheckout() {
        // Apple-style checkout flow
        const checkoutBtn = document.getElementById('checkoutBtn');
        const originalText = checkoutBtn.textContent;
        
        checkoutBtn.textContent = 'Preparando...';
        checkoutBtn.disabled = true;
        
        // Simulate checkout preparation
        setTimeout(() => {
            // In a real app, this would redirect to checkout
            alert('🚧 Función de checkout en desarrollo\n\nEn una implementación real, esto redirigiría al proceso de pago seguro.');
            
            checkoutBtn.textContent = originalText;
            checkoutBtn.disabled = false;
        }, 1500);
    }

    showNotification(message, type = 'info') {
        const container = document.getElementById('cartNotifications');
        if (!container) return;

        const notification = document.createElement('div');
        notification.className = `cart-notification ${type}`;
        
        // Icon based on type
        const icons = {
            success: 'bi-check-circle',
            error: 'bi-exclamation-triangle',
            warning: 'bi-exclamation-circle',
            info: 'bi-info-circle'
        };
        
        notification.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="${icons[type] || icons.info} me-2"></i>
                <span>${message}</span>
            </div>
        `;

        container.appendChild(notification);

        // Auto remove after 4 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease-in-out forwards';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 4000);
    }

    // Method to handle external cart updates (from other modules)
    handleExternalUpdate() {
        this.updateTotals();
        this.updateCartSummary();
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Only initialize if we're on the cart page
    if (document.querySelector('.cart-content')) {
        window.appleCart = new AppleCart();
    }
});

// Export for use in other modules
export default AppleCart;