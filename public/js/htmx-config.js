/**
 * ⚡ CONFIGURACIÓN HTMX
 *
 * Configuración centralizada de HTMX con interceptores y manejo de errores
 * - Single Responsibility: Solo configura HTMX
 * - Open/Closed: Extensible para nuevos interceptores
 */
import htmx from 'htmx.org';
// Hacer HTMX disponible globalmente
window.htmx = htmx;
/**
 * Configuración principal de HTMX
 */
export function configureHTMX() {
    // Configuración global
    htmx.config.timeout = 10000;
    htmx.config.defaultSwapStyle = 'outerHTML';
    htmx.config.defaultSwapDelay = 100;
    htmx.config.defaultSettleDelay = 100;
    // Headers por defecto
    document.addEventListener('htmx:configRequest', (event) => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken) {
            event.detail.headers['X-CSRF-TOKEN'] = csrfToken;
        }
        event.detail.headers['X-Requested-With'] = 'XMLHttpRequest';
        event.detail.headers['Accept'] = 'application/json, text/html';
    });
    // Manejo de errores HTTP
    document.addEventListener('htmx:responseError', (event) => {
        const { xhr, target } = event.detail;
        console.error('HTMX Response Error:', {
            status: xhr.status,
            statusText: xhr.statusText,
            url: xhr.responseURL,
            target: target
        });
        // Mostrar notificación de error
        showErrorNotification(`Error ${xhr.status}: ${xhr.statusText}`);
        // Manejar errores específicos
        switch (xhr.status) {
            case 401:
                handleUnauthorized();
                break;
            case 403:
                showErrorNotification('No tienes permisos para realizar esta acción');
                break;
            case 404:
                showErrorNotification('Recurso no encontrado');
                break;
            case 422:
                handleValidationErrors(xhr);
                break;
            case 500:
                showErrorNotification('Error interno del servidor');
                break;
            default:
                showErrorNotification('Error de conexión');
        }
    });
    // Manejo de errores de red
    document.addEventListener('htmx:sendError', (event) => {
        console.error('HTMX Network Error:', event.detail);
        showErrorNotification('Error de conexión. Verifica tu conexión a internet.');
    });
    // Manejo de timeout
    document.addEventListener('htmx:timeout', (event) => {
        console.error('HTMX Timeout:', event.detail);
        showErrorNotification('La solicitud tardó demasiado tiempo. Inténtalo de nuevo.');
    });
    // Interceptor antes de enviar
    document.addEventListener('htmx:beforeRequest', (event) => {
        const { target } = event.detail;
        // Agregar indicador de carga
        if (target) {
            target.classList.add('htmx-loading');
            // Agregar spinner si no existe
            if (!target.querySelector('.htmx-spinner')) {
                const spinner = document.createElement('div');
                spinner.className = 'htmx-spinner';
                spinner.innerHTML = '<div class="spinner-border spinner-border-sm" role="status"></div>';
                target.appendChild(spinner);
            }
        }
    });
    // Interceptor después de recibir respuesta
    document.addEventListener('htmx:afterRequest', (event) => {
        const { target, xhr } = event.detail;
        // Remover indicador de carga
        if (target) {
            target.classList.remove('htmx-loading');
            const spinner = target.querySelector('.htmx-spinner');
            if (spinner) {
                spinner.remove();
            }
        }
        // Manejar respuestas exitosas con mensajes
        if (xhr.status >= 200 && xhr.status < 300) {
            try {
                const response = JSON.parse(xhr.responseText);
                if (response.mensaje) {
                    showSuccessNotification(response.mensaje);
                }
            }
            catch (e) {
                // No es JSON, ignorar
            }
        }
    });
    // Interceptor después de swap
    document.addEventListener('htmx:afterSwap', (event) => {
        const { target } = event.detail;
        // Reinicializar componentes Alpine en el nuevo contenido
        if (window.Alpine && target) {
            window.Alpine.initTree(target);
        }
        // Emitir evento personalizado para otros componentes
        document.dispatchEvent(new CustomEvent('htmx:contentUpdated', {
            detail: { target }
        }));
    });
    // Interceptor para formularios
    document.addEventListener('htmx:beforeRequest', (event) => {
        const { elt } = event.detail;
        if (elt.tagName === 'FORM') {
            // Validar formulario antes de enviar
            if (!elt.checkValidity()) {
                event.preventDefault();
                elt.reportValidity();
                return;
            }
            // Deshabilitar botón de submit
            const submitBtn = elt.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.dataset.originalText = submitBtn.textContent;
                submitBtn.textContent = 'Enviando...';
            }
        }
    });
    // Rehabilitar botones después de la respuesta
    document.addEventListener('htmx:afterRequest', (event) => {
        const { elt } = event.detail;
        if (elt.tagName === 'FORM') {
            const submitBtn = elt.querySelector('button[type="submit"]');
            if (submitBtn && submitBtn.dataset.originalText) {
                submitBtn.disabled = false;
                submitBtn.textContent = submitBtn.dataset.originalText;
                delete submitBtn.dataset.originalText;
            }
        }
    });
    console.log('⚡ HTMX configured successfully');
}
/**
 * Maneja errores de autorización
 */
function handleUnauthorized() {
    showErrorNotification('Sesión expirada. Redirigiendo al login...');
    setTimeout(() => {
        window.location.href = '/login';
    }, 2000);
}
/**
 * Maneja errores de validación
 */
function handleValidationErrors(xhr) {
    try {
        const response = JSON.parse(xhr.responseText);
        if (response.errors) {
            Object.entries(response.errors).forEach(([field, messages]) => {
                const fieldElement = document.querySelector(`[name="${field}"]`);
                if (fieldElement && Array.isArray(messages)) {
                    showFieldError(fieldElement, messages[0]);
                }
            });
        }
        else if (response.mensaje) {
            showErrorNotification(response.mensaje);
        }
    }
    catch (e) {
        showErrorNotification('Error de validación');
    }
}
/**
 * Muestra error en un campo específico
 */
function showFieldError(field, message) {
    // Remover errores previos
    const existingError = field.parentElement?.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
    // Agregar clase de error
    field.classList.add('is-invalid');
    // Crear elemento de error
    const errorElement = document.createElement('div');
    errorElement.className = 'field-error text-danger small mt-1';
    errorElement.textContent = message;
    // Insertar después del campo
    field.parentElement?.appendChild(errorElement);
    // Remover error cuando el usuario empiece a escribir
    field.addEventListener('input', () => {
        field.classList.remove('is-invalid');
        errorElement.remove();
    }, { once: true });
}
/**
 * Muestra notificación de éxito
 */
function showSuccessNotification(message) {
    createNotification('success', message);
}
/**
 * Muestra notificación de error
 */
function showErrorNotification(message) {
    createNotification('error', message);
}
/**
 * Crea una notificación
 */
function createNotification(type, message) {
    const container = getNotificationContainer();
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-message">${message}</span>
            <button class="notification-close" aria-label="Cerrar">×</button>
        </div>
    `;
    // Event listener para cerrar
    notification.querySelector('.notification-close')?.addEventListener('click', () => {
        notification.remove();
    });
    container.appendChild(notification);
    // Auto-remover después de 5 segundos
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 5000);
}
/**
 * Obtiene o crea el contenedor de notificaciones
 */
function getNotificationContainer() {
    let container = document.getElementById('htmx-notifications');
    if (!container) {
        container = document.createElement('div');
        container.id = 'htmx-notifications';
        container.className = 'htmx-notifications-container';
        document.body.appendChild(container);
    }
    return container;
}
//# sourceMappingURL=htmx-config.js.map