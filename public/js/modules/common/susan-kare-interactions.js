/**
 * 🎨 SUSAN KARE INTERACTION PATTERNS
 * "Los iconos deben responder como objetos físicos familiares"
 */

export class SusanKareInteractions {
    constructor() {
        this.init();
    }

    init() {
        this.setupHeartAnimations();
        this.setupCartIconFeedback();
        this.setupLoadingStates();
        this.setupTooltipBehavior();
    }

    /**
     * Corazón de favoritos - Comportamiento emocional
     * Filosofía: "El corazón debe latir cuando lo tocas"
     */
    setupHeartAnimations() {
        document.addEventListener('click', (e) => {
            if (e.target.closest('[data-action="favorite"]')) {
                const heartIcon = e.target.querySelector('.kare-icon-heart') || 
                                e.target.closest('.kare-icon-heart');
                
                if (heartIcon) {
                    this.animateHeartbeat(heartIcon);
                }
            }
        });
    }

    animateHeartbeat(heartIcon) {
        // Toggle estado activo
        heartIcon.classList.toggle('active');
        
        // Animación de latido
        heartIcon.style.animation = 'none';
        heartIcon.offsetHeight; // Trigger reflow
        heartIcon.style.animation = 'heartbeat 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
        
        // Feedback háptico en dispositivos compatibles
        if (navigator.vibrate) {
            navigator.vibrate([50, 30, 50]);
        }
        
        // Evento personalizado para otros módulos
        const event = new CustomEvent('favoriteToggled', {
            detail: { 
                active: heartIcon.classList.contains('active'),
                element: heartIcon 
            }
        });
        document.dispatchEvent(event);
    }

    /**
     * Carrito - Feedback visual inmediato
     * Filosofía: "El carrito debe moverse como si fuera real"
     */
    setupCartIconFeedback() {
        document.addEventListener('click', (e) => {
            if (e.target.closest('#addToCartBtn')) {
                const cartIcons = document.querySelectorAll('.kare-icon-cart');
                cartIcons.forEach(icon => this.animateCartShake(icon));
            }
        });
    }

    animateCartShake(cartIcon) {
        // Animación de "llenado" del carrito
        cartIcon.style.transform = 'scale(1.1) rotate(5deg)';
        
        setTimeout(() => {
            cartIcon.style.transform = 'scale(1.05) rotate(-3deg)';
        }, 100);
        
        setTimeout(() => {
            cartIcon.style.transform = 'scale(1) rotate(0deg)';
        }, 200);
        
        // Efecto de "peso" - el carrito se vuelve más "pesado"
        cartIcon.style.filter = 'brightness(0.9)';
        setTimeout(() => {
            cartIcon.style.filter = 'brightness(1)';
        }, 300);
    }

    /**
     * Estados de carga - Feedback de progreso
     * Filosofía: "El usuario debe saber que algo está pasando"
     */
    setupLoadingStates() {
        // Interceptar formularios para mostrar loading
        document.addEventListener('submit', (e) => {
            if (e.target.id === 'addToCartForm') {
                const submitBtn = e.target.querySelector('button[type="submit"]');
                this.showLoadingState(submitBtn);
            }
        });
    }

    showLoadingState(button) {
        const originalContent = button.innerHTML;
        const loadingIcon = '<span class="kare-icon-loading me-2"></span>';
        
        // Cambiar a estado de carga
        button.innerHTML = loadingIcon + 'Agregando...';
        button.disabled = true;
        
        // Simular respuesta (en producción esto vendría del servidor)
        setTimeout(() => {
            this.showSuccessState(button, originalContent);
        }, 1500);
    }

    showSuccessState(button, originalContent) {
        const successIcon = '<span class="kare-icon-check me-2"></span>';
        
        // Mostrar éxito
        button.innerHTML = successIcon + '¡Agregado!';
        button.classList.add('btn-success');
        
        // Volver al estado original
        setTimeout(() => {
            button.innerHTML = originalContent;
            button.disabled = false;
            button.classList.remove('btn-success');
        }, 2000);
    }

    /**
     * Tooltips informativos - Ayuda contextual
     * Filosofía: "La ayuda debe aparecer cuando se necesita"
     */
    setupTooltipBehavior() {
        document.addEventListener('mouseenter', (e) => {
            if (e.target.classList.contains('kare-icon-info')) {
                this.showTooltip(e.target);
            }
        });

        document.addEventListener('mouseleave', (e) => {
            if (e.target.classList.contains('kare-icon-info')) {
                this.hideTooltip(e.target);
            }
        });
    }

    showTooltip(infoIcon) {
        const tooltip = document.createElement('div');
        tooltip.className = 'kare-tooltip';
        tooltip.textContent = infoIcon.dataset.tooltip || 'Información adicional';
        
        // Posicionamiento relativo al icono
        const rect = infoIcon.getBoundingClientRect();
        tooltip.style.position = 'absolute';
        tooltip.style.top = (rect.bottom + 5) + 'px';
        tooltip.style.left = (rect.left - 50) + 'px';
        tooltip.style.zIndex = '1000';
        
        document.body.appendChild(tooltip);
        infoIcon._tooltip = tooltip;
        
        // Animación de aparición
        requestAnimationFrame(() => {
            tooltip.style.opacity = '1';
            tooltip.style.transform = 'translateY(0)';
        });
    }

    hideTooltip(infoIcon) {
        if (infoIcon._tooltip) {
            infoIcon._tooltip.remove();
            infoIcon._tooltip = null;
        }
    }

    /**
     * Método público para crear iconos dinámicamente
     * Filosofía: "Los iconos deben ser fáciles de usar programáticamente"
     */
    createIcon(type, options = {}) {
        const icon = document.createElement('span');
        icon.className = `kare-icon kare-icon-${type}`;
        
        // Aplicar opciones
        if (options.size) {
            icon.style.width = options.size + 'px';
            icon.style.height = options.size + 'px';
        }
        
        if (options.color) {
            icon.style.color = options.color;
        }
        
        if (options.tooltip) {
            icon.dataset.tooltip = options.tooltip;
        }
        
        return icon;
    }

    /**
     * Método para animar iconos existentes
     * Filosofía: "Las animaciones deben tener propósito comunicativo"
     */
    animateIcon(icon, animation = 'bounce') {
        const animations = {
            bounce: 'transform: scale(1.2); transition: transform 0.15s ease;',
            pulse: 'animation: pulse 0.6s ease-in-out;',
            shake: 'animation: shake 0.5s ease-in-out;',
            glow: 'box-shadow: 0 0 10px rgba(0, 122, 255, 0.5); transition: box-shadow 0.3s ease;'
        };
        
        if (animations[animation]) {
            icon.style.cssText += animations[animation];
            
            // Limpiar animación después de completarse
            setTimeout(() => {
                icon.style.transform = '';
                icon.style.animation = '';
                icon.style.boxShadow = '';
            }, 600);
        }
    }
}

// CSS adicional para tooltips (se inyecta dinámicamente)
const tooltipStyles = `
.kare-tooltip {
    background: var(--ive-gray-900);
    color: var(--ive-white);
    padding: 8px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    opacity: 0;
    transform: translateY(-5px);
    transition: all 0.2s ease;
    pointer-events: none;
    white-space: nowrap;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.kare-tooltip::before {
    content: '';
    position: absolute;
    top: -4px;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border-left: 4px solid transparent;
    border-right: 4px solid transparent;
    border-bottom: 4px solid var(--ive-gray-900);
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.1); }
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-2px); }
    75% { transform: translateX(2px); }
}
`;

// Inyectar estilos si no existen
if (!document.querySelector('#kare-tooltip-styles')) {
    const styleSheet = document.createElement('style');
    styleSheet.id = 'kare-tooltip-styles';
    styleSheet.textContent = tooltipStyles;
    document.head.appendChild(styleSheet);
}