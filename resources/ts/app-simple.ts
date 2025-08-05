/**
 * 🎸 ROCK STORE - APLICACIÓN SIMPLE
 */

// Clase simple para detalle de producto
class ProductoDetalle {
    private quantityInput: HTMLInputElement | null;
    private addToCartForm: HTMLFormElement | null;

    constructor() {
        this.quantityInput = document.querySelector('#cantidad');
        this.addToCartForm = document.querySelector('#addToCartForm');
        this.init();
    }

    private init(): void {
        this.bindEvents();
    }

    private bindEvents(): void {
        // Botones de cantidad
        const decrementBtn = document.querySelector<HTMLButtonElement>('button[data-action="decrement"]');
        const incrementBtn = document.querySelector<HTMLButtonElement>('button[data-action="increment"]');

        if (decrementBtn) {
            decrementBtn.addEventListener('click', () => this.decrementQuantity());
        }

        if (incrementBtn) {
            incrementBtn.addEventListener('click', () => this.incrementQuantity());
        }

        // Botón de favoritos
        const favoriteBtn = document.querySelector<HTMLButtonElement>('button[data-action="favorite"]');
        if (favoriteBtn) {
            favoriteBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.addToFavorites();
            });
        }

        // Formulario de agregar al carrito
        if (this.addToCartForm) {
            this.addToCartForm.addEventListener('submit', async (e) => {
                e.preventDefault();
                await this.addToCart();
            });
        }
    }

    private decrementQuantity(): void {
        if (!this.quantityInput) return;

        const current = parseInt(this.quantityInput.value) || 1;
        const min = parseInt(this.quantityInput.min) || 1;

        if (current > min) {
            this.quantityInput.value = (current - 1).toString();
        }
    }

    private incrementQuantity(): void {
        if (!this.quantityInput) return;

        const current = parseInt(this.quantityInput.value) || 1;
        const max = parseInt(this.quantityInput.max) || 99;

        if (current < max) {
            this.quantityInput.value = (current + 1).toString();
        }
    }

    private async addToCart(): Promise<void> {
        if (!this.addToCartForm) return;

        const formData = new FormData(this.addToCartForm);
        const addToCartBtn = document.querySelector<HTMLButtonElement>('#addToCartBtn');

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

            if (response.ok) {
                this.showNotification('✅ Producto agregado al carrito', 'success');
            } else {
                throw new Error('Error al agregar al carrito');
            }

        } catch (error) {
            console.error('Error:', error);
            this.showNotification('❌ Error al agregar al carrito', 'error');
        } finally {
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

    private addToFavorites(): void {
        this.showNotification('❤️ Agregado a favoritos', 'success');
    }

    private showNotification(message: string, type: 'success' | 'error'): void {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${
            type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    private getCSRFToken(): string {
        const token = document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]');
        return token ? token.content : '';
    }
}

// Inicializar cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', () => {
    const path = window.location.pathname;
    
    if (path.includes('/album/') || path.includes('/producto/')) {
        new ProductoDetalle();
    }
});