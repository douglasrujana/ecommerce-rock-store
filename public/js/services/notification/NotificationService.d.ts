/**
 * 🔔 SERVICIO DE NOTIFICACIONES
 *
 * Maneja todas las notificaciones del sistema siguiendo principios SOLID
 * - Single Responsibility: Solo maneja notificaciones
 * - Open/Closed: Extensible para nuevos tipos de notificación
 * - Interface Segregation: Métodos específicos por tipo
 */
import type { IService, NotificationType, IEventBus } from '@types/index';
export interface INotificationService extends IService {
    success(message: string, duration?: number): void;
    error(message: string, duration?: number): void;
    warning(message: string, duration?: number): void;
    info(message: string, duration?: number): void;
    show(type: NotificationType, message: string, duration?: number): void;
}
export declare class NotificationService implements INotificationService {
    private readonly eventBus;
    readonly name = "NotificationService";
    private readonly defaultDuration;
    constructor(eventBus: IEventBus);
    initialize(): void;
    /**
     * Muestra notificación de éxito
     */
    success(message: string, duration?: number): void;
    /**
     * Muestra notificación de error
     */
    error(message: string, duration?: number): void;
    /**
     * Muestra notificación de advertencia
     */
    warning(message: string, duration?: number): void;
    /**
     * Muestra notificación informativa
     */
    info(message: string, duration?: number): void;
    /**
     * Muestra notificación de tipo específico
     */
    show(type: NotificationType, message: string, duration?: number): void;
    /**
     * Configura los event listeners
     */
    private setupEventListeners;
    /**
     * Crea el elemento DOM de la notificación
     */
    private createNotificationElement;
    /**
     * Obtiene o crea el contenedor de notificaciones
     */
    private getOrCreateContainer;
    /**
     * Remueve una notificación con animación
     */
    private removeNotification;
}
//# sourceMappingURL=NotificationService.d.ts.map