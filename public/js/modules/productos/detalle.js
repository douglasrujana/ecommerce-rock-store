/**
 * 🎸 MÓDULO DE DETALLE DE PRODUCTO - TypeScript
 */
export class ProductoDetalle {
    constructor() {
        this.productId = this.getProductId();
        this.quantityInput = document.querySelector('#cantidad');
        this.addToCartForm = document.querySelector('#addToCartForm');
        this.init();
    }
    init() {
        this.bindEvents();
        this.setupQuantityControls();
        this.setupFormSubmission();
    }
    getProductId() {
        const hiddenInput = document.querySelector('input[name="id"]');
        return hiddenInput ? parseInt(hiddenInput.value) : 0;
    }
    bindEvents() {
        // Botón de favoritos
        const favoriteBtn = document.querySelector('button[data-action="favorite"]');
        if (favoriteBtn) {
            favoriteBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const productId = parseInt(favoriteBtn.dataset.productId || '0');
                this.addToFavorites(productId);
            });
        }
        // Botones de cantidad
        const decrementBtn = document.querySelector('button[data-action="decrement"]');
        const incrementBtn = document.querySelector('button[data-action="increment"]');
        if (decrementBtn) {
            decrementBtn.addEventListener('click', () => this.decrementQuantity());
        }
        if (incrementBtn) {
            incrementBtn.addEventListener('click', () => this.incrementQuantity());
        }
    }
    setupQuantityControls() {
        if (!this.quantityInput)
            return;
        this.quantityInput.addEventListener('input', (e) => {
            const target = e.target;
            this.validateQuantityInput(target);
        });
        this.quantityInput.addEventListener('blur', (e) => {
            const target = e.target;
            this.normalizeQuantity(target);
        });
        this.quantityInput.addEventListener('keydown', (e) => {
            this.handleQuantityKeydown(e);
        });
    }
    setupFormSubmission() {
        if (!this.addToCartForm)
            return;
        this.addToCartForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            await this.addToCart();
        });
    }
    decrementQuantity() {
        if (!this.quantityInput)
            return;
        const current = parseInt(this.quantityInput.value) || 1;
        const min = parseInt(this.quantityInput.min) || 1;
        if (current > min) {
            this.quantityInput.value = (current - 1).toString();
            this.animateQuantityButton('decrement');
        }
    }
    incrementQuantity() {
        if (!this.quantityInput)
            return;
        const current = parseInt(this.quantityInput.value) || 1;
        const max = parseInt(this.quantityInput.max) || 99;
        if (current < max) {
            this.quantityInput.value = (current + 1).toString();
            this.animateQuantityButton('increment');
        }
    }
    validateQuantityInput(input) {
        input.value = input.value.replace(/[^0-9]/g, '');
    }
    normalizeQuantity(input) {
        const value = parseInt(input.value) || 1;
        const min = parseInt(input.min) || 1;
        const max = parseInt(input.max) || 99;
        input.value = Math.max(min, Math.min(max, value)).toString();
    }
    handleQuantityKeydown(e) {
        if (e.key === 'ArrowUp') {
            e.preventDefault();
            this.incrementQuantity();
        }
        else if (e.key === 'ArrowDown') {
            e.preventDefault();
            this.decrementQuantity();
        }
    }
    animateQuantityButton(action) {
        const button = document.querySelector(`button[data-action="${action}"]`);
        if (!button)
            return;
        button.style.transform = 'scale(0.95)';
        setTimeout(() => {
            button.style.transform = '';
        }, 150);
    }
    async addToCart() {
        if (!this.addToCartForm || !this.quantityInput)
            return;
        const formData = new FormData(this.addToCartForm);
        const addToCartBtn = document.querySelector('#addToCartBtn');
        try {
            if (addToCartBtn) {
                addToCartBtn.disabled = true;
                addToCartBtn.textContent = 'Agregando...';
            }
            const response = await fetch(this.addToCartForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                }
            });
            const result = await response.json();
            if (response.ok && result.success) {
                this.showNotification('✅ Producto agregado al carrito', 'success');
                this.updateCartBadge(result.cartCount);
                this.showSuccessAnimation();
            }
            else {
                throw new Error(result.message || 'Error al agregar al carrito');
            }
        }
        catch (error) {
            console.error('Error:', error);
            this.showNotification('❌ Error al agregar al carrito', 'error');
        }
        finally {
            if (addToCartBtn) {
                addToCartBtn.disabled = false;
                addToCartBtn.innerHTML = `
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                        </svg>
                        <span>Agregar al carrito</span>
                    </div>
                `;
            }
        }
    }
    addToFavorites(productId) {
        this.showNotification('❤️ Agregado a favoritos', 'success');
        const favoriteBtn = document.querySelector('button[data-action="favorite"]');
        if (favoriteBtn) {
            favoriteBtn.style.transform = 'scale(1.1)';
            setTimeout(() => {
                favoriteBtn.style.transform = '';
            }, 200);
        }
    }
    updateCartBadge(count) {
        const cartBadge = document.querySelector('#cartBadge span');
        if (cartBadge) {
            cartBadge.textContent = count.toString();
        }
    }
    showSuccessAnimation() {
        const addToCartBtn = document.querySelector('#addToCartBtn');
        if (addToCartBtn) {
            addToCartBtn.classList.add('animate-pulse');
            setTimeout(() => {
                addToCartBtn.classList.remove('animate-pulse');
            }, 1000);
        }
    }
    showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${type === 'success' ? 'bg-ive-green text-white' : 'bg-ive-red text-white'}`;
        notification.textContent = message;
        document.body.appendChild(notification);
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }
    getCSRFToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.content : '';
    }
    getQuantity() {
        return this.quantityInput ? parseInt(this.quantityInput.value) || 1 : 1;
    }
    setQuantity(value) {
        if (this.quantityInput) {
            this.quantityInput.value = value.toString();
            this.normalizeQuantity(this.quantityInput);
        }
    }
}
//# sourceMappingURL=detalle.js.map