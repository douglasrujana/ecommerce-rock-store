/**
 * 🏗️ CONTENEDOR DE INYECCIÓN DE DEPENDENCIAS
 * 
 * Implementa el patrón IoC Container siguiendo principios SOLID
 * - Single Responsibility: Solo maneja registro y resolución de dependencias
 * - Open/Closed: Extensible para nuevos tipos de servicios
 * - Dependency Inversion: Las clases dependen de abstracciones, no de implementaciones
 */

import type { IService } from '@types/index';

type Constructor<T = {}> = new (...args: any[]) => T;
type Factory<T> = () => T;
type ServiceDefinition<T> = Constructor<T> | Factory<T> | T;

interface ServiceBinding<T> {
    readonly definition: ServiceDefinition<T>;
    readonly singleton: boolean;
    readonly dependencies: string[];
    instance?: T;
}

export class Container {
    private static _instance: Container;
    private readonly services = new Map<string, ServiceBinding<any>>();
    private readonly resolving = new Set<string>();

    private constructor() {}

    /**
     * Singleton pattern para el contenedor
     */
    public static getInstance(): Container {
        if (!Container._instance) {
            Container._instance = new Container();
        }
        return Container._instance;
    }

    /**
     * Registra un servicio como singleton
     */
    public singleton<T>(
        name: string, 
        definition: ServiceDefinition<T>, 
        dependencies: string[] = []
    ): this {
        return this.bind(name, definition, dependencies, true);
    }

    /**
     * Registra un servicio como transient (nueva instancia cada vez)
     */
    public transient<T>(
        name: string, 
        definition: ServiceDefinition<T>, 
        dependencies: string[] = []
    ): this {
        return this.bind(name, definition, dependencies, false);
    }

    /**
     * Registra una instancia específica
     */
    public instance<T>(name: string, instance: T): this {
        this.services.set(name, {
            definition: instance,
            singleton: true,
            dependencies: [],
            instance
        });
        return this;
    }

    /**
     * Resuelve un servicio por su nombre
     */
    public resolve<T>(name: string): T {
        if (this.resolving.has(name)) {
            throw new Error(`Circular dependency detected: ${name}`);
        }

        const binding = this.services.get(name);
        if (!binding) {
            throw new Error(`Service not found: ${name}`);
        }

        // Si es singleton y ya tiene instancia, devolverla
        if (binding.singleton && binding.instance) {
            return binding.instance;
        }

        this.resolving.add(name);

        try {
            const instance = this.createInstance(binding);
            
            if (binding.singleton) {
                binding.instance = instance;
            }

            return instance;
        } finally {
            this.resolving.delete(name);
        }
    }

    /**
     * Verifica si un servicio está registrado
     */
    public has(name: string): boolean {
        return this.services.has(name);
    }

    /**
     * Inicializa todos los servicios registrados
     */
    public async initializeServices(): Promise<void> {
        const services = Array.from(this.services.keys());
        
        for (const serviceName of services) {
            const service = this.resolve<IService>(serviceName);
            
            if (this.isService(service)) {
                await service.initialize();
                console.log(`✅ Service initialized: ${service.name}`);
            }
        }
    }

    /**
     * Registra un servicio con configuración completa
     */
    private bind<T>(
        name: string,
        definition: ServiceDefinition<T>,
        dependencies: string[],
        singleton: boolean
    ): this {
        this.services.set(name, {
            definition,
            singleton,
            dependencies
        });
        return this;
    }

    /**
     * Crea una instancia del servicio resolviendo sus dependencias
     */
    private createInstance<T>(binding: ServiceBinding<T>): T {
        const { definition, dependencies } = binding;

        // Si es una instancia directa
        if (typeof definition !== 'function') {
            return definition;
        }

        // Resolver dependencias
        const resolvedDependencies = dependencies.map(dep => this.resolve(dep));

        // Si es una factory function
        if (this.isFactory(definition)) {
            return definition();
        }

        // Si es un constructor
        return new definition(...resolvedDependencies);
    }

    /**
     * Verifica si es una factory function
     */
    private isFactory<T>(definition: ServiceDefinition<T>): definition is Factory<T> {
        return typeof definition === 'function' && !definition.prototype;
    }

    /**
     * Verifica si implementa la interfaz IService
     */
    private isService(obj: any): obj is IService {
        return obj && 
               typeof obj.name === 'string' && 
               typeof obj.initialize === 'function';
    }
}

/**
 * Instancia global del contenedor
 */
export const container = Container.getInstance();