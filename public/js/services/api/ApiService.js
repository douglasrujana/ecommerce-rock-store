/**
 * 🌐 SERVICIO API
 *
 * Maneja todas las comunicaciones HTTP siguiendo principios SOLID
 * - Single Responsibility: Solo maneja comunicación HTTP
 * - Open/Closed: Extensible para nuevos endpoints
 * - Interface Segregation: Métodos específicos por tipo de operación
 */
export class ApiService {
    constructor(config) {
        this.config = config;
        this.name = 'ApiService';
    }
    initialize() {
        console.log('🌐 ApiService initialized');
    }
    /**
     * Realiza petición GET
     */
    async get(endpoint, params) {
        const url = this.buildUrl(endpoint, params);
        return this.request(url, { method: 'GET' });
    }
    /**
     * Realiza petición POST
     */
    async post(endpoint, data) {
        const url = this.buildUrl(endpoint);
        return this.request(url, {
            method: 'POST',
            body: this.prepareBody(data)
        });
    }
    /**
     * Realiza petición PUT
     */
    async put(endpoint, data) {
        const url = this.buildUrl(endpoint);
        return this.request(url, {
            method: 'PUT',
            body: this.prepareBody(data)
        });
    }
    /**
     * Realiza petición DELETE
     */
    async delete(endpoint) {
        const url = this.buildUrl(endpoint);
        return this.request(url, { method: 'DELETE' });
    }
    /**
     * Método base para realizar peticiones HTTP
     */
    async request(url, options) {
        try {
            const response = await fetch(url, {
                ...options,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.config.csrfToken,
                    ...options.headers
                }
            });
            const data = await response.json();
            if (!response.ok) {
                return {
                    success: false,
                    mensaje: data.mensaje || `HTTP Error: ${response.status}`,
                    errors: data.errors
                };
            }
            return {
                success: true,
                data: data.data || data,
                mensaje: data.mensaje
            };
        }
        catch (error) {
            console.error('API Request failed:', error);
            return {
                success: false,
                mensaje: 'Error de conexión con el servidor'
            };
        }
    }
    /**
     * Construye la URL completa con parámetros
     */
    buildUrl(endpoint, params) {
        const baseUrl = this.config.apiBaseUrl.replace(/\/$/, '');
        const cleanEndpoint = endpoint.replace(/^\//, '');
        let url = `${baseUrl}/${cleanEndpoint}`;
        if (params) {
            const searchParams = new URLSearchParams();
            Object.entries(params).forEach(([key, value]) => {
                if (value !== null && value !== undefined) {
                    searchParams.append(key, String(value));
                }
            });
            const queryString = searchParams.toString();
            if (queryString) {
                url += `?${queryString}`;
            }
        }
        return url;
    }
    /**
     * Prepara el cuerpo de la petición
     */
    prepareBody(data) {
        if (!data)
            return null;
        if (data instanceof FormData) {
            return data;
        }
        return JSON.stringify(data);
    }
}
//# sourceMappingURL=ApiService.js.map