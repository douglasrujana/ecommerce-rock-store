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

export class EventBus implements IEventBus, IService {
    public readonly name = 'EventBus';
    private readonly events = new Map<string, Set<EventHandler>>();

    public initialize(): void {
        console.log('🚌 EventBus initialized');
    }

    /**
     * Emite un evento con datos opcionales
     */
    public emit<T = any>(event: string, data?: T): void {
        const handlers = this.events.get(event);
        
        if (handlers) {
            handlers.forEach(handler => {
                try {
                    handler(data);
                } catch (error) {
                    console.error(`Error in event handler for "${event}":`, error);
                }
            });
        }

        // Log para debugging en desarrollo
        if (process.env.NODE_ENV === 'development') {
            console.log(`📡 Event emitted: ${event}`, data);
        }
    }

    /**
     * Registra un handler para un evento
     */
    public on<T = any>(event: string, handler: EventHandler<T>): void {
        if (!this.events.has(event)) {
            this.events.set(event, new Set());
        }

        this.events.get(event)!.add(handler);
    }

    /**
     * Desregistra un handler o todos los handlers de un evento
     */
    public off(event: string, handler?: EventHandler): void {
        const handlers = this.events.get(event);
        
        if (!handlers) return;

        if (handler) {
            handlers.delete(handler);
            
            // Si no quedan handlers, eliminar el evento
            if (handlers.size === 0) {
                this.events.delete(event);
            }
        } else {
            // Eliminar todos los handlers del evento
            this.events.delete(event);
        }
    }

    /**
     * Registra un handler que se ejecuta solo una vez
     */
    public once<T = any>(event: string, handler: EventHandler<T>): void {
        const onceHandler = (data: T) => {
            handler(data);
            this.off(event, onceHandler);
        };

        this.on(event, onceHandler);
    }

    /**
     * Obtiene el número de handlers registrados para un evento
     */
    public getHandlerCount(event: string): number {
        return this.events.get(event)?.size || 0;
    }

    /**
     * Obtiene todos los eventos registrados
     */
    public getRegisteredEvents(): string[] {
        return Array.from(this.events.keys());
    }

    /**
     * Limpia todos los eventos registrados
     */
    public clear(): void {
        this.events.clear();
    }

    /**
     * Destruye el event bus
     */
    public destroy(): void {
        this.clear();
        console.log('🗑️ EventBus destroyed');
    }
}