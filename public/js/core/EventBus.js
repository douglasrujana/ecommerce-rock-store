/**
 * 🚌 EVENT BUS
 *
 * Sistema de eventos centralizado siguiendo el patrón Observer
 * - Single Responsibility: Solo maneja eventos
 * - Open/Closed: Extensible para nuevos tipos de eventos
 * - Liskov Substitution: Implementa IEventBus correctamente
 */
export class EventBus {
    constructor() {
        this.name = 'EventBus';
        this.events = new Map();
    }
    initialize() {
        console.log('🚌 EventBus initialized');
    }
    /**
     * Emite un evento con datos opcionales
     */
    emit(event, data) {
        const handlers = this.events.get(event);
        if (handlers) {
            handlers.forEach(handler => {
                try {
                    handler(data);
                }
                catch (error) {
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
    on(event, handler) {
        if (!this.events.has(event)) {
            this.events.set(event, new Set());
        }
        this.events.get(event).add(handler);
    }
    /**
     * Desregistra un handler o todos los handlers de un evento
     */
    off(event, handler) {
        const handlers = this.events.get(event);
        if (!handlers)
            return;
        if (handler) {
            handlers.delete(handler);
            // Si no quedan handlers, eliminar el evento
            if (handlers.size === 0) {
                this.events.delete(event);
            }
        }
        else {
            // Eliminar todos los handlers del evento
            this.events.delete(event);
        }
    }
    /**
     * Registra un handler que se ejecuta solo una vez
     */
    once(event, handler) {
        const onceHandler = (data) => {
            handler(data);
            this.off(event, onceHandler);
        };
        this.on(event, onceHandler);
    }
    /**
     * Obtiene el número de handlers registrados para un evento
     */
    getHandlerCount(event) {
        return this.events.get(event)?.size || 0;
    }
    /**
     * Obtiene todos los eventos registrados
     */
    getRegisteredEvents() {
        return Array.from(this.events.keys());
    }
    /**
     * Limpia todos los eventos registrados
     */
    clear() {
        this.events.clear();
    }
    /**
     * Destruye el event bus
     */
    destroy() {
        this.clear();
        console.log('🗑️ EventBus destroyed');
    }
}
//# sourceMappingURL=EventBus.js.map