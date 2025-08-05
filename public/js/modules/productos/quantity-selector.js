/**
 * 🔢 QUANTITY SELECTOR APPLE STYLE
 * Patrón Apple Store: Controles de cantidad elegantes y táctiles
 */

export class QuantitySelector {
    constructor(selector = '.quantity-selector') {
        this.selector = selector;
        this.init();
    }

    init() {
        const selectors = document.querySelectorAll(this.selector);
        selectors.forEach(selector => this.setupSelector(selector));
    }

    setupSelector(container) {
        const decrementBtn = container.querySelector('.quantity-btn[data-action="decrement"]');
        const incrementBtn = container.querySelector('.quantity-btn[data-action="increment"]');
        const input = container.querySelector('.quantity-input');

        if (!decrementBtn || !incrementBtn || !input) return;

        // Event listeners
        decrementBtn.addEventListener('click', () => this.decrement(input));
        incrementBtn.addEventListener('click', () => this.increment(input));
        
        // Input validation
        input.addEventListener('input', (e) => this.validateInput(e.target));
        input.addEventListener('blur', (e) => this.normalizeInput(e.target));

        // Keyboard support
        input.addEventListener('keydown', (e) => this.handleKeydown(e, input));
    }

    decrement(input) {
        const current = parseInt(input.value) || 1;
        const min = parseInt(input.min) || 1;
        
        if (current > min) {
            input.value = current - 1;
            this.triggerChange(input);
            this.animateButton(input.parentElement.querySelector('[data-action="decrement"]'));
        }
    }

    increment(input) {
        const current = parseInt(input.value) || 1;
        const max = parseInt(input.max) || 99;
        
        if (current < max) {
            input.value = current + 1;
            this.triggerChange(input);
            this.animateButton(input.parentElement.querySelector('[data-action="increment"]'));
        }
    }

    validateInput(input) {
        // Solo permitir números
        input.value = input.value.replace(/[^0-9]/g, '');
    }

    normalizeInput(input) {
        const value = parseInt(input.value) || 1;
        const min = parseInt(input.min) || 1;
        const max = parseInt(input.max) || 99;
        
        // Asegurar que esté en rango
        input.value = Math.max(min, Math.min(max, value));
        this.triggerChange(input);
    }

    handleKeydown(e, input) {
        // Arrow up/down para incrementar/decrementar
        if (e.key === 'ArrowUp') {
            e.preventDefault();
            this.increment(input);
        } else if (e.key === 'ArrowDown') {
            e.preventDefault();
            this.decrement(input);
        }
    }

    animateButton(button) {
        if (!button) return;
        
        // Feedback táctil visual
        button.style.transform = 'scale(0.9)';
        setTimeout(() => {
            button.style.transform = '';
        }, 100);
    }

    triggerChange(input) {
        // Disparar evento personalizado para que otros módulos puedan escuchar
        const event = new CustomEvent('quantityChanged', {
            detail: { 
                value: parseInt(input.value),
                input: input 
            }
        });
        input.dispatchEvent(event);
    }

    // Método público para obtener cantidad actual
    getQuantity(selector = '.quantity-input') {
        const input = document.querySelector(selector);
        return input ? parseInt(input.value) || 1 : 1;
    }

    // Método público para establecer cantidad
    setQuantity(value, selector = '.quantity-input') {
        const input = document.querySelector(selector);
        if (input) {
            input.value = value;
            this.normalizeInput(input);
        }
    }
}