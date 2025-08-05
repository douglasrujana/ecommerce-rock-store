/**
 * ⚡ CONFIGURACIÓN HTMX
 *
 * Configuración centralizada de HTMX con interceptores y manejo de errores
 * - Single Responsibility: Solo configura HTMX
 * - Open/Closed: Extensible para nuevos interceptores
 */
import htmx from 'htmx.org';
declare global {
    interface Window {
        htmx: typeof htmx;
    }
}
/**
 * Configuración principal de HTMX
 */
export declare function configureHTMX(): void;
//# sourceMappingURL=htmx-config.d.ts.map