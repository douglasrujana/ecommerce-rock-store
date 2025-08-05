/**
 * 🛒 MÓDULO DEL CARRITO EN TYPESCRIPT
 */
export class Carrito {
    constructor() {
        this.isAddingToCart = false;
        this.badgeElement = document.getElementById('cartBadge');
        this.init();
    }
    init() {
        this.bindEvents();
        this.updateCartBadge();
    }
    bindEvents() {
        const addToCartForm = document.getElementById('addToCartForm');
        if (addToCartForm) {
            addToCartForm.addEventListener('submit', (e) => this.handleAddToCart(e));
        }
        this.bindQuantityControls();
        this.bindFloatingCart();
    }
    bindQuantityControls() {
        const incrementBtn = document.querySelector('button[onclick="incrementQuantity()"]');
        const decrementBtn = document.querySelector('button[onclick="decrementQuantity()"]');
        if (incrementBtn) {
            incrementBtn.onclick = null;
            incrementBtn.addEventListener('click', () => this.incrementQuantity());
        }
        if (decrementBtn) {
            decrementBtn.onclick = null;
            decrementBtn.addEventListener('click', () => this.decrementQuantity());
        }
    }
    bindFloatingCart() {
        const carritoFlotante = document.querySelector('.position-fixed .bg-primary');
        if (carritoFlotante) {
            carritoFlotante.onclick = null;
            carritoFlotante.addEventListener('click', () => {
                window.location.href = '/carrito';
            });
        }
    }
    async handleAddToCart(e) {
        e.preventDefault();
        if (this.isAddingToCart)
            return;
        this.isAddingToCart = true;
        const form = e.target;
        const btn = document.getElementById('addToCartBtn');
        const btnText = btn.querySelector('.btn-text');
        const originalText = btnText.textContent || '';
        this.setButtonState(btn, btnText, '🔄 Agregando...', true);
        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            if (data.success) {
                this.showMessage('success', data.mensaje);
                await this.updateCartBadge();
                this.showSuccessState(btn, btnText, originalText);
            }
            else {
                this.showMessage('warning', data.mensaje || 'Error al agregar al carrito');
            }
        }
        catch (error) {
            console.error('Error:', error);
            this.showMessage('danger', '❌ Error de conexión');
        }
        finally {
            this.isAddingToCart = false;
            this.resetButtonState(btn, btnText, originalText);
        }
    }
    async updateCartBadge() {
        try {
            const response = await fetch('/api/carrito/contar');
            const data = await response.json();
            if (this.badgeElement) {
                this.badgeElement.textContent = data.total_items.toString();
                this.badgeElement.style.display = data.total_items > 0 ? 'block' : 'none';
            }
        }
        catch (error) {
            console.error('Error updating cart badge:', error);
        }
    }
    setButtonState(btn, btnText, text, disabled) {
        btn.disabled = disabled;
        btnText.textContent = text;
    }
    showSuccessState(btn, btnText, originalText) {
        btn.classList.add('btn-success');
        btnText.textContent = '✓ ¡Agregado!';
        setTimeout(() => {
            btn.classList.remove('btn-success');
            btnText.textContent = originalText;
        }, 2000);
    }
    resetButtonState(btn, btnText, originalText) {
        btn.disabled = false;
        if (btnText.textContent?.includes('Agregando')) {
            btnText.textContent = originalText;
        }
    }
    showMessage(type, message) {
        const messageDiv = document.getElementById('cartMessage');
        if (!messageDiv)
            return;
        messageDiv.className = `alert alert-${type}`;
        messageDiv.textContent = message;
        messageDiv.classList.remove('d-none');
        setTimeout(() => {
            messageDiv.classList.add('d-none');
        }, 3000);
    }
    incrementQuantity() {
        const input = document.getElementById('cantidad');
        if (input) {
            const currentValue = parseInt(input.value);
            if (currentValue < 99) {
                input.value = (currentValue + 1).toString();
            }
        }
    }
    decrementQuantity() {
        const input = document.getElementById('cantidad');
        if (input) {
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = (currentValue - 1).toString();
            }
        }
    }
}
//# sourceMappingURL=carrito.js.map