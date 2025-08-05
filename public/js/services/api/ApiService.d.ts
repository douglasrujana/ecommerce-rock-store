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
export declare class ApiService implements IApiService {
    private readonly config;
    readonly name = "ApiService";
    constructor(config: AppConfig);
    initialize(): void;
    /**
     * Realiza petición GET
     */
    get<T>(endpoint: string, params?: Record<string, any>): Promise<ApiResponse<T>>;
    /**
     * Realiza petición POST
     */
    post<T>(endpoint: string, data?: any): Promise<ApiResponse<T>>;
    /**
     * Realiza petición PUT
     */
    put<T>(endpoint: string, data?: any): Promise<ApiResponse<T>>;
    /**
     * Realiza petición DELETE
     */
    delete<T>(endpoint: string): Promise<ApiResponse<T>>;
    /**
     * Método base para realizar peticiones HTTP
     */
    private request;
    /**
     * Construye la URL completa con parámetros
     */
    private buildUrl;
    /**
     * Prepara el cuerpo de la petición
     */
    private prepareBody;
}
//# sourceMappingURL=ApiService.d.ts.map