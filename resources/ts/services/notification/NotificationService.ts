/**
 * 🔔 SERVICIO DE NOTIFICACIONES
 * 
 * Maneja todas las notificaciones del sistema siguiendo principios SOLID
 * - Single Responsibility: Solo maneja notificaciones
 * - Open/Closed: Extensible para nuevos tipos de notificación
 * - Interface Segregation: Métodos específicos por tipo
 */

import type { IService, NotificationEvent, NotificationType, IEventBus } from '@types/index';

export interface INotificationService extends IService {
    success(message: string, duration?: number): void;
    error(message: string, duration?: number): void;
    warning(message: string, duration?: number): void;
    info(message: string, duration?: number): void;
    show(type: NotificationType, message: string, duration?: number): void;
}

export class NotificationService implements INotificationService {
    public readonly name = 'NotificationService';
    private readonly defaultDuration = 5000;

    constructor(private readonly eventBus: IEventBus) {}

    public initialize(): void {
        this.setupEventListeners();
        console.log('🔔 NotificationService initialized');
    }

    /**
     * Muestra notificación de éxito
     */
    public success(message: string, duration: number = this.defaultDuration): void {
        this.show(NotificationType.SUCCESS, message, duration);
    }

    /**
     * Muestra notificación de error
     */
    public error(message: string, duration: number = this.defaultDuration): void {
        this.show(NotificationType.ERROR, message, duration);
    }

    /**
     * Muestra notificación de advertencia
     */
    public warning(message: string, duration: number = this.defaultDuration): void {
        this.show(NotificationType.WARNING, message, duration);
    }

    /**
     * Muestra notificación informativa
     */
    public info(message: string, duration: number = this.defaultDuration): void {
        this.show(NotificationType.INFO, message, duration);
    }

    /**
     * Muestra notificación de tipo específico
     */
    public show(type: NotificationType, message: string, duration: number = this.defaultDuration): void {
        const notification: NotificationEvent = {
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
    private setupEventListeners(): void {
        // Escuchar eventos de carrito para mostrar notificaciones automáticas
        this.eventBus.on('cart:changed', (event: any) => {
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
        this.eventBus.on('api:error', (error: string) => {
            this.error(`❌ ${error}`);
        });
    }

    /**
     * Crea el elemento DOM de la notificación
     */
    private createNotificationElement(notification: NotificationEvent): void {
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
    private getOrCreateContainer(): HTMLElement {
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
    private removeNotification(element: HTMLElement): void {
        element.classList.add('notification-hide');
        
        setTimeout(() => {
            if (element.parentNode) {
                element.parentNode.removeChild(element);
            }
        }, 300);
    }
}