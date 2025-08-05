/**
 * 🚌 EVENT BUS
 *
 * Sistema de eventos centralizado siguiendo el patrón Observer
 * - Single Responsibility: Solo maneja eventos
 * - Open/Closed: Extensible para nuevos tipos de eventos
 * - Liskov Substitution: Implementa IEventBus correctamente
 */
import type { IEventBus, IService } from '@types/index';
type EventHandler<T = any> = (data: T) => void;
export declare class EventBus implements IEventBus, IService {
    readonly name = "EventBus";
    private readonly events;
    initialize(): void;
    /**
     * Emite un evento con datos opcionales
     */
    emit<T = any>(event: string, data?: T): void;
    /**
     * Registra un handler para un evento
     */
    on<T = any>(event: string, handler: EventHandler<T>): void;
    /**
     * Desregistra un handler o todos los handlers de un evento
     */
    off(event: string, handler?: EventHandler): void;
    /**
     * Registra un handler que se ejecuta solo una vez
     */
    once<T = any>(event: string, handler: EventHandler<T>): void;
    /**
     * Obtiene el número de handlers registrados para un evento
     */
    getHandlerCount(event: string): number;
    /**
     * Obtiene todos los eventos registrados
     */
    getRegisteredEvents(): string[];
    /**
     * Limpia todos los eventos registrados
     */
    clear(): void;
    /**
     * Destruye el event bus
     */
    destroy(): void;
}
export {};
//# sourceMappingURL=EventBus.d.ts.map