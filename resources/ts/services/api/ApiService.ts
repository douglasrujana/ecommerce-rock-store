/**
 * 🌐 SERVICIO API
 * 
 * Maneja todas las comunicaciones HTTP siguiendo principios SOLID
 * - Single Responsibility: Solo maneja comunicación HTTP
 * - Open/Closed: Extensible para nuevos endpoints
 * - Interface Segregation: Métodos específicos por tipo de operación
 */

import type { IService, ApiResponse, AppConfig } from '@types/index';

export interface IApiService extends IService {
    get<T>(endpoint: string, params?: Record<string, any>): Promise<ApiResponse<T>>;
    post<T>(endpoint: string, data?: any): Promise<ApiResponse<T>>;
    put<T>(endpoint: string, data?: any): Promise<ApiResponse<T>>;
    delete<T>(endpoint: string): Promise<ApiResponse<T>>;
}

export class ApiService implements IApiService {
    public readonly name = 'ApiService';
    
    constructor(private readonly config: AppConfig) {}

    public initialize(): void {
        console.log('🌐 ApiService initialized');
    }

    /**
     * Realiza petición GET
     */
    public async get<T>(endpoint: string, params?: Record<string, any>): Promise<ApiResponse<T>> {
        const url = this.buildUrl(endpoint, params);
        return this.request<T>(url, { method: 'GET' });
    }

    /**
     * Realiza petición POST
     */
    public async post<T>(endpoint: string, data?: any): Promise<ApiResponse<T>> {
        const url = this.buildUrl(endpoint);
        return this.request<T>(url, {
            method: 'POST',
            body: this.prepareBody(data)
        });
    }

    /**
     * Realiza petición PUT
     */
    public async put<T>(endpoint: string, data?: any): Promise<ApiResponse<T>> {
        const url = this.buildUrl(endpoint);
        return this.request<T>(url, {
            method: 'PUT',
            body: this.prepareBody(data)
        });
    }

    /**
     * Realiza petición DELETE
     */
    public async delete<T>(endpoint: string): Promise<ApiResponse<T>> {
        const url = this.buildUrl(endpoint);
        return this.request<T>(url, { method: 'DELETE' });
    }

    /**
     * Método base para realizar peticiones HTTP
     */
    private async request<T>(url: string, options: RequestInit): Promise<ApiResponse<T>> {
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

        } catch (error) {
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
    private buildUrl(endpoint: string, params?: Record<string, any>): string {
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
    private prepareBody(data: any): string | FormData | null {
        if (!data) return null;
        
        if (data instanceof FormData) {
            return data;
        }
        
        return JSON.stringify(data);
    }
}