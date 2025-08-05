/**
 * 🛒 MÓDULO DEL CARRITO EN TYPESCRIPT
 */

import { CartResponse, CartBadgeData } from '../../types/carrito.js';

export class Carrito {
    private isAddingToCart: boolean = false;
    private badgeElement: HTMLElement | null;

    constructor() {
        this.badgeElement = document.getElementById('cartBadge');
        this.init();
    }

    private init(): void {
        this.bindEvents();
        this.updateCartBadge();
    }

    private bindEvents(): void {
        const addToCartForm = document.getElementById('addToCartForm') as HTMLFormElement;
        if (addToCartForm) {
            addToCartForm.addEventListener('submit', (e) => this.handleAddToCart(e));
        }

        this.bindQuantityControls();
        this.bindFloatingCart();
    }

    private bindQuantityControls(): void {
        const incrementBtn = document.querySelector('button[onclick="incrementQuantity()"]') as HTMLButtonElement;
        const decrementBtn = document.querySelector('button[onclick="decrementQuantity()"]') as HTMLButtonElement;
        
        if (incrementBtn) {
            incrementBtn.onclick = null;
            incrementBtn.addEventListener('click', () => this.incrementQuantity());
        }
        
        if (decrementBtn) {
            decrementBtn.onclick = null;
            decrementBtn.addEventListener('click', () => this.decrementQuantity());
        }
    }

    private bindFloatingCart(): void {
        const carritoFlotante = document.querySelector('.position-fixed .bg-primary') as HTMLElement;
        if (carritoFlotante) {
            carritoFlotante.onclick = null;
            carritoFlotante.addEventListener('click', () => {
                window.location.href = '/carrito';
            });
        }
    }

    private async handleAddToCart(e: Event): Promise<void> {
        e.preventDefault();

        if (this.isAddingToCart) return;

        this.isAddingToCart = true;
        const form = e.target as HTMLFormElement;
        const btn = document.getElementById('addToCartBtn') as HTMLButtonElement;
        const btnText = btn.querySelector('.btn-text') as HTMLElement;
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

            const data: CartResponse = await response.json();

            if (data.success) {
                this.showMessage('success', data.mensaje);
                await this.updateCartBadge();
                this.showSuccessState(btn, btnText, originalText);
            } else {
                this.showMessage('warning', data.mensaje || 'Error al agregar al carrito');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showMessage('danger', '❌ Error de conexión');
        } finally {
            this.isAddingToCart = false;
            this.resetButtonState(btn, btnText, originalText);
        }
    }

    private async updateCartBadge(): Promise<void> {
        try {
            const response = await fetch('/api/carrito/contar');
            const data: CartBadgeData = await response.json();
            
            if (this.badgeElement) {
                this.badgeElement.textContent = data.total_items.toString();
                this.badgeElement.style.display = data.total_items > 0 ? 'block' : 'none';
            }
        } catch (error) {
            console.error('Error updating cart badge:', error);
        }
    }

    private setButtonState(btn: HTMLButtonElement, btnText: HTMLElement, text: string, disabled: boolean): void {
        btn.disabled = disabled;
        btnText.textContent = text;
    }

    private showSuccessState(btn: HTMLButtonElement, btnText: HTMLElement, originalText: string): void {
        btn.classList.add('btn-success');
        btnText.textContent = '✓ ¡Agregado!';

        setTimeout(() => {
            btn.classList.remove('btn-success');
            btnText.textContent = originalText;
        }, 2000);
    }

    private resetButtonState(btn: HTMLButtonElement, btnText: HTMLElement, originalText: string): void {
        btn.disabled = false;
        if (btnText.textContent?.includes('Agregando')) {
            btnText.textContent = originalText;
        }
    }

    private showMessage(type: string, message: string): void {
        const messageDiv = document.getElementById('cartMessage');
        if (!messageDiv) return;

        messageDiv.className = `alert alert-${type}`;
        messageDiv.textContent = message;
        messageDiv.classList.remove('d-none');

        setTimeout(() => {
            messageDiv.classList.add('d-none');
        }, 3000);
    }

    private incrementQuantity(): void {
        const input = document.getElementById('cantidad') as HTMLInputElement;
        if (input) {
            const currentValue = parseInt(input.value);
            if (currentValue < 99) {
                input.value = (currentValue + 1).toString();
            }
        }
    }

    private decrementQuantity(): void {
        const input = document.getElementById('cantidad') as HTMLInputElement;
        if (input) {
            const currentValue = parseInt(input.value);
            if (currentValue > 1) {
                input.value = (currentValue - 1).toString();
            }
        }
    }
}