/**
 * 📱 SISTEMA DE NOTIFICACIONES
 */

export class Notifications {
    static show(type, message, duration = 3000) {
        const messageDiv = document.getElementById('cartMessage');
        if (!messageDiv) return;

        messageDiv.className = `alert alert-${type}`;
        messageDiv.textContent = message;
        messageDiv.classList.remove('d-none');

        setTimeout(() => {
            messageDiv.classList.add('d-none');
        }, duration);
    }

    static success(message) {
        this.show('success', message);
    }

    static error(message) {
        this.show('danger', message);
    }

    static warning(message) {
        this.show('warning', message);
    }
}