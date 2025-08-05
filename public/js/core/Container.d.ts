/**
 * 🏗️ CONTENEDOR DE INYECCIÓN DE DEPENDENCIAS
 *
 * Implementa el patrón IoC Container siguiendo principios SOLID
 * - Single Responsibility: Solo maneja registro y resolución de dependencias
 * - Open/Closed: Extensible para nuevos tipos de servicios
 * - Dependency Inversion: Las clases dependen de abstracciones, no de implementaciones
 */
type Constructor<T = {}> = new (...args: any[]) => T;
type Factory<T> = () => T;
type ServiceDefinition<T> = Constructor<T> | Factory<T> | T;
export declare class Container {
    private static _instance;
    private readonly services;
    private readonly resolving;
    private constructor();
    /**
     * Singleton pattern para el contenedor
     */
    static getInstance(): Container;
    /**
     * Registra un servicio como singleton
     */
    singleton<T>(name: string, definition: ServiceDefinition<T>, dependencies?: string[]): this;
    /**
     * Registra un servicio como transient (nueva instancia cada vez)
     */
    transient<T>(name: string, definition: ServiceDefinition<T>, dependencies?: string[]): this;
    /**
     * Registra una instancia específica
     */
    instance<T>(name: string, instance: T): this;
    /**
     * Resuelve un servicio por su nombre
     */
    resolve<T>(name: string): T;
    /**
     * Verifica si un servicio está registrado
     */
    has(name: string): boolean;
    /**
     * Inicializa todos los servicios registrados
     */
    initializeServices(): Promise<void>;
    /**
     * Registra un servicio con configuración completa
     */
    private bind;
    /**
     * Crea una instancia del servicio resolviendo sus dependencias
     */
    private createInstance;
    /**
     * Verifica si es una factory function
     */
    private isFactory;
    /**
     * Verifica si implementa la interfaz IService
     */
    private isService;
}
/**
 * Instancia global del contenedor
 */
export declare const container: Container;
export {};
//# sourceMappingURL=Container.d.ts.map