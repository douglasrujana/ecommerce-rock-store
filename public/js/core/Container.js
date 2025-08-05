/**
 * 🏗️ CONTENEDOR DE INYECCIÓN DE DEPENDENCIAS
 *
 * Implementa el patrón IoC Container siguiendo principios SOLID
 * - Single Responsibility: Solo maneja registro y resolución de dependencias
 * - Open/Closed: Extensible para nuevos tipos de servicios
 * - Dependency Inversion: Las clases dependen de abstracciones, no de implementaciones
 */
export class Container {
    constructor() {
        this.services = new Map();
        this.resolving = new Set();
    }
    /**
     * Singleton pattern para el contenedor
     */
    static getInstance() {
        if (!Container._instance) {
            Container._instance = new Container();
        }
        return Container._instance;
    }
    /**
     * Registra un servicio como singleton
     */
    singleton(name, definition, dependencies = []) {
        return this.bind(name, definition, dependencies, true);
    }
    /**
     * Registra un servicio como transient (nueva instancia cada vez)
     */
    transient(name, definition, dependencies = []) {
        return this.bind(name, definition, dependencies, false);
    }
    /**
     * Registra una instancia específica
     */
    instance(name, instance) {
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
    resolve(name) {
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
        }
        finally {
            this.resolving.delete(name);
        }
    }
    /**
     * Verifica si un servicio está registrado
     */
    has(name) {
        return this.services.has(name);
    }
    /**
     * Inicializa todos los servicios registrados
     */
    async initializeServices() {
        const services = Array.from(this.services.keys());
        for (const serviceName of services) {
            const service = this.resolve(serviceName);
            if (this.isService(service)) {
                await service.initialize();
                console.log(`✅ Service initialized: ${service.name}`);
            }
        }
    }
    /**
     * Registra un servicio con configuración completa
     */
    bind(name, definition, dependencies, singleton) {
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
    createInstance(binding) {
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
    isFactory(definition) {
        return typeof definition === 'function' && !definition.prototype;
    }
    /**
     * Verifica si implementa la interfaz IService
     */
    isService(obj) {
        return obj &&
            typeof obj.name === 'string' &&
            typeof obj.initialize === 'function';
    }
}
/**
 * Instancia global del contenedor
 */
export const container = Container.getInstance();
//# sourceMappingURL=Container.js.map