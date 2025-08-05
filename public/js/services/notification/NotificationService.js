/**
 * 🔔 SERVICIO DE NOTIFICACIONES
 *
 * Maneja todas las notificaciones del sistema siguiendo principios SOLID
 * - Single Responsibility: Solo maneja notificaciones
 * - Open/Closed: Extensible para nuevos tipos de notificación
 * - Interface Segregation: Métodos específicos por tipo
 */
export class NotificationService {
    constructor(eventBus) {
        this.eventBus = eventBus;
        this.name = 'NotificationService';
        this.defaultDuration = 5000;
    }
    initialize() {
        this.setupEventListeners();
        console.log('🔔 NotificationService initialized');
    }
    /**
     * Muestra notificación de éxito
     */
    success(message, duration = this.defaultDuration) {
        this.show(NotificationType.SUCCESS, message, duration);
    }
    /**
     * Muestra notificación de error
     */
    error(message, duration = this.defaultDuration) {
        this.show(NotificationType.ERROR, message, duration);
    }
    /**
     * Muestra notificación de advertencia
     */
    warning(message, duration = this.defaultDuration) {
        this.show(NotificationType.WARNING, message, duration);
    }
    /**
     * Muestra notificación informativa
     */
    info(message, duration = this.defaultDuration) {
        this.show(NotificationType.INFO, message, duration);
    }
    /**
     * Muestra notificación de tipo específico
     */
    show(type, message, duration = this.defaultDuration) {
        const notification = {
            type,
            message,
            duration
        };
        this.eventBus.emit('notification:show', notification);
        this.createNotificationElement(notification);
    }
    /**
     * Configura los event listeners
     */
    setupEventListeners() {
        // Escuchar eventos de carrito para mostrar notificaciones automáticas
        this.eventBus.on('cart:changed', (event) => {
            switch (event.type) {
                case 'item_added':
                    this.success('✅ Producto agregado al carrito');
                    break;
                case 'item_removed':
                    this.info('🗑️ Producto eliminado del carrito');
                    break;
                case 'item_updated':
                    this.info('📝 Cantidad actualizada');
                    break;
                case 'cart_cleared':
                    this.warning('🧹 Carrito vaciado');
                    break;
            }
        });
        // Escuchar errores de API
        this.eventBus.on('api:error', (error) => {
            this.error(`❌ ${error}`);
        });
    }
    /**
     * Crea el elemento DOM de la notificación
     */
    createNotificationElement(notification) {
        const container = this.getOrCreateContainer();
        const element = document.createElement('div');
        element.className = `notification notification-${notification.type}`;
        element.innerHTML = `
            <div class="notification-content">
                <span class="notification-message">${notification.message}</span>
                <button class="notification-close" aria-label="Cerrar">×</button>
            </div>
        `;
        // Agregar event listener para cerrar
        const closeBtn = element.querySelector('.notification-close');
        closeBtn?.addEventListener('click', () => {
            this.removeNotification(element);
        });
        // Agregar al container
        container.appendChild(element);
        // Auto-remover después del tiempo especificado
        if (notification.duration && notification.duration > 0) {
            setTimeout(() => {
                this.removeNotification(element);
            }, notification.duration);
        }
        // Animar entrada
        requestAnimationFrame(() => {
            element.classList.add('notification-show');
        });
    }
    /**
     * Obtiene o crea el contenedor de notificaciones
     */
    getOrCreateContainer() {
        let container = document.getElementById('notifications-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'notifications-container';
            container.className = 'notifications-container';
            document.body.appendChild(container);
        }
        return container;
    }
    /**
     * Remueve una notificación con animación
     */
    removeNotification(element) {
        element.classList.add('notification-hide');
        setTimeout(() => {
            if (element.parentNode) {
                element.parentNode.removeChild(element);
            }
        }, 300);
    }
}
//# sourceMappingURL=NotificationService.js.map