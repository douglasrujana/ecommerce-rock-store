/**
 * 🎸 ROCK STORE - JavaScript Simple Bootstrap
 */

// Funciones básicas para el producto
document.addEventListener('DOMContentLoaded', function() {
    
    // Control de cantidad
    const decrementBtn = document.querySelector('button[data-action="decrement"]');
    const incrementBtn = document.querySelector('button[data-action="increment"]');
    const quantityInput = document.querySelector('#cantidad');

    if (decrementBtn && quantityInput) {
        decrementBtn.addEventListener('click', function() {
            const current = parseInt(quantityInput.value) || 1;
            const min = parseInt(quantityInput.min) || 1;
            if (current > min) {
                quantityInput.value = current - 1;
            }
        });
    }

    if (incrementBtn && quantityInput) {
        incrementBtn.addEventListener('click', function() {
            const current = parseInt(quantityInput.value) || 1;
            const max = parseInt(quantityInput.max) || 99;
            if (current < max) {
                quantityInput.value = current + 1;
            }
        });
    }

    // Botón de favoritos
    const favoriteBtn = document.querySelector('button[data-action="favorite"]');
    if (favoriteBtn) {
        favoriteBtn.addEventListener('click', function() {
            showMessage('❤️ Agregado a favoritos', 'success');
        });
    }

    // Formulario de agregar al carrito
    const addToCartForm = document.querySelector('#addToCartForm');
    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(addToCartForm);
            const addToCartBtn = document.querySelector('#addToCartBtn');
            
            if (addToCartBtn) {
                addToCartBtn.disabled = true;
                addToCartBtn.innerHTML = '<i class="bi-cart-fill me-2"></i>Agregando...';
            }

            fetch(addToCartForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.ok) {
                    showMessage('✅ Producto agregado al carrito', 'success');
                } else {
                    showMessage('❌ Error al agregar al carrito', 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('❌ Error al agregar al carrito', 'danger');
            })
            .finally(() => {
                if (addToCartBtn) {
                    addToCartBtn.disabled = false;
                    addToCartBtn.innerHTML = '<i class="bi-cart-fill me-2"></i><span class="btn-text">Agregar al carrito</span>';
                }
            });
        });
    }

    // Función para mostrar mensajes
    function showMessage(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} position-fixed`;
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alertDiv.textContent = message;
        
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            alertDiv.style.opacity = '0';
            setTimeout(() => {
                if (document.body.contains(alertDiv)) {
                    document.body.removeChild(alertDiv);
                }
            }, 300);
        }, 3000);
    }
});