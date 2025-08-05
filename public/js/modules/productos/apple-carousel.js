/**
 * 🎠 APPLE STORE CAROUSEL
 * Carousel horizontal con controles y scroll suave
 */

export class AppleCarousel {
    constructor(selector = '.related-products-carousel') {
        this.carousel = document.querySelector(selector);
        if (!this.carousel) return;
        
        this.track = this.carousel.querySelector('.related-products-track');
        this.items = this.carousel.querySelectorAll('.related-product-item');
        this.prevBtn = document.querySelector('.carousel-btn-prev');
        this.nextBtn = document.querySelector('.carousel-btn-next');
        
        this.currentIndex = 0;
        this.itemWidth = 280 + 16; // width + gap
        this.visibleItems = this.getVisibleItems();
        
        this.init();
    }

    init() {
        this.setupControls();
        this.setupQuickAdd();
        this.setupScrollIndicators();
        this.updateControls();
        
        // Responsive
        window.addEventListener('resize', () => {
            this.visibleItems = this.getVisibleItems();
            this.updateControls();
        });
    }

    getVisibleItems() {
        const containerWidth = this.carousel.offsetWidth;
        return Math.floor(containerWidth / this.itemWidth);
    }

    setupControls() {
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => this.prev());
        }
        
        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => this.next());
        }
        
        // Touch/swipe support
        let startX = 0;
        let scrollLeft = 0;
        
        this.track.addEventListener('touchstart', (e) => {
            startX = e.touches[0].pageX;
            scrollLeft = this.track.scrollLeft;
        });
        
        this.track.addEventListener('touchmove', (e) => {
            if (!startX) return;
            
            const x = e.touches[0].pageX;
            const walk = (startX - x) * 2;
            this.track.scrollLeft = scrollLeft + walk;
        });
        
        this.track.addEventListener('touchend', () => {
            startX = 0;
            this.updateScrollIndicators();
        });
    }

    prev() {
        if (this.currentIndex > 0) {
            this.currentIndex--;
            this.updatePosition();
        }
    }

    next() {
        const maxIndex = Math.max(0, this.items.length - this.visibleItems);
        if (this.currentIndex < maxIndex) {
            this.currentIndex++;
            this.updatePosition();
        }
    }

    updatePosition() {
        const translateX = -this.currentIndex * this.itemWidth;
        this.track.style.transform = `translateX(${translateX}px)`;
        this.updateControls();
        this.updateScrollIndicators();
    }

    updateControls() {
        if (!this.prevBtn || !this.nextBtn) return;
        
        const maxIndex = Math.max(0, this.items.length - this.visibleItems);
        
        this.prevBtn.disabled = this.currentIndex === 0;
        this.nextBtn.disabled = this.currentIndex >= maxIndex;
    }

    setupQuickAdd() {
        const quickAddBtns = this.carousel.querySelectorAll('[data-action="quick-add"]');
        
        quickAddBtns.forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                const productId = btn.dataset.productId;
                await this.quickAddToCart(btn, productId);
            });
        });
    }

    async quickAddToCart(button, productId) {
        const originalText = button.innerHTML;
        
        // Estado de carga
        button.classList.add('loading');
        button.innerHTML = '<span class="visually-hidden">Agregando...</span>';
        
        try {
            const formData = new FormData();
            formData.append('id', productId);
            formData.append('cantidad', '1');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            
            const response = await fetch('/carrito/agregar', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Estado de éxito
                button.classList.remove('loading');
                button.classList.add('success');
                button.innerHTML = '<i class="bi-check me-1"></i>¡Agregado!';
                
                // Actualizar badge del carrito
                this.updateCartBadge();
                
                // Mostrar notificación
                this.showNotification('Producto agregado al carrito', 'success');
                
                // Volver al estado original
                setTimeout(() => {
                    button.classList.remove('success');
                    button.innerHTML = originalText;
                }, 2000);
                
            } else {
                throw new Error(data.mensaje || 'Error al agregar producto');
            }
            
        } catch (error) {
            console.error('Error:', error);
            
            // Estado de error
            button.classList.remove('loading');
            button.innerHTML = '<i class="bi-exclamation-triangle me-1"></i>Error';
            
            this.showNotification('Error al agregar producto', 'error');
            
            // Volver al estado original
            setTimeout(() => {
                button.innerHTML = originalText;
            }, 2000);
        }
    }

    async updateCartBadge() {
        try {
            const response = await fetch('/api/carrito/contar');
            const data = await response.json();
            
            // Actualizar badges
            const badges = document.querySelectorAll('#cartBadge, #navCartBadge');
            badges.forEach(badge => {
                badge.textContent = data.total_items;
                
                if (data.total_items === 0) {
                    badge.classList.add('empty');
                } else {
                    badge.classList.remove('empty');
                    badge.classList.add('updated');
                    
                    setTimeout(() => {
                        badge.classList.remove('updated');
                    }, 600);
                }
            });
            
        } catch (error) {
            console.error('Error updating cart badge:', error);
        }
    }

    showNotification(message, type = 'info') {
        // Crear notificación temporal
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
        notification.style.cssText = `
            top: 20px;
            right: 20px;
            z-index: 1060;
            min-width: 300px;
            animation: slideIn 0.3s ease-out;
        `;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Remover después de 3 segundos
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease-in';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }

    setupScrollIndicators() {
        const indicatorsContainer = document.getElementById('scrollIndicators');
        if (!indicatorsContainer || window.innerWidth >= 768) return;
        
        // Crear indicadores
        const indicatorCount = Math.ceil(this.items.length / this.visibleItems);
        indicatorsContainer.innerHTML = '';
        
        for (let i = 0; i < indicatorCount; i++) {
            const indicator = document.createElement('div');
            indicator.className = 'scroll-indicator';
            indicator.addEventListener('click', () => {
                this.currentIndex = i;
                this.updatePosition();
            });
            indicatorsContainer.appendChild(indicator);
        }
        
        this.updateScrollIndicators();
    }

    updateScrollIndicators() {
        const indicators = document.querySelectorAll('.scroll-indicator');
        const activeIndex = Math.floor(this.currentIndex / this.visibleItems);
        
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === activeIndex);
        });
    }
}

// CSS para animaciones de notificación
const notificationStyles = `
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(100%);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideOut {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(100%);
    }
}
`;

// Inyectar estilos si no existen
if (!document.querySelector('#carousel-notification-styles')) {
    const styleSheet = document.createElement('style');
    styleSheet.id = 'carousel-notification-styles';
    styleSheet.textContent = notificationStyles;
    document.head.appendChild(styleSheet);
}