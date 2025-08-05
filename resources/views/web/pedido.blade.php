@extends('web.app')

@section('title', 'Carrito de Compras - Rock Store')

@section('contenido')

{{-- 🛒 CART HERO SECTION --}}
<section class="cart-hero">
    <div class="container">
        <div class="cart-header">
            <h1 class="cart-title">Tu Carrito</h1>
            <p class="cart-subtitle">Revisa tus productos antes de finalizar la compra</p>
        </div>
    </div>
</section>

{{-- 🛍️ CART CONTENT --}}
<section class="cart-content">
    <div class="container">
        <div class="cart-layout">
            {{-- CART ITEMS --}}
            <div class="cart-items">
                @if(isset($carrito) && count($carrito) > 0)
                    @foreach($carrito as $item)
                    <div class="cart-item" data-product-id="{{ $item['id'] }}">
                        <div class="item-image">
                            <img src="{{ $item['imagen'] ? asset('uploads/productos/' . $item['imagen']) : 'https://dummyimage.com/120x120/f5f5f7/1d1d1f.png?text=🎵' }}" 
                                 alt="{{ $item['nombre'] }}">
                        </div>
                        
                        <div class="item-details">
                            <h3 class="item-name">{{ $item['nombre'] }}</h3>
                            <div class="item-category">{{ $item['categoria'] ?? 'Música' }}</div>
                            <div class="item-price">${{ number_format($item['precio'], 2) }}</div>
                        </div>
                        
                        <div class="item-controls">
                            <div class="quantity-control">
                                <button class="qty-btn qty-minus" data-action="decrease">−</button>
                                <input type="number" class="qty-input" value="{{ $item['cantidad'] }}" min="1" max="99">
                                <button class="qty-btn qty-plus" data-action="increase">+</button>
                            </div>
                            
                            <div class="item-total">${{ number_format($item['precio'] * $item['cantidad'], 2) }}</div>
                            
                            <button class="remove-btn" data-action="remove">
                                <span class="remove-icon">🗑️</span>
                            </button>
                        </div>
                    </div>
                    @endforeach
                @else
                    {{-- EMPTY CART --}}
                    <div class="empty-cart">
                        <div class="empty-icon">🛒</div>
                        <h3>Tu carrito está vacío</h3>
                        <p>Descubre nuestra increíble colección de música y agrega algunos álbumes a tu carrito.</p>
                        <a href="{{ route('web.index') }}" class="btn-apple">Explorar Catálogo</a>
                    </div>
                @endif
            </div>
            
            {{-- CART SUMMARY --}}
            <div class="cart-summary">
                <div class="summary-card">
                    <h3 class="summary-title">Resumen del Pedido</h3>
                    
                    <div class="summary-details">
                        <div class="summary-row">
                            <span>Subtotal</span>
                            <span class="subtotal">$0.00</span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Envío</span>
                            <span class="shipping">Gratis</span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Impuestos</span>
                            <span class="taxes">$0.00</span>
                        </div>
                        
                        <div class="summary-divider"></div>
                        
                        <div class="summary-row total-row">
                            <span>Total</span>
                            <span class="total">$0.00</span>
                        </div>
                    </div>
                    
                    <div class="summary-actions">
                        <button class="btn-checkout" id="checkoutBtn">
                            <span>Proceder al Pago</span>
                            <span class="checkout-icon">→</span>
                        </button>
                        
                        <a href="{{ route('web.index') }}" class="btn-continue">
                            Continuar Comprando
                        </a>
                    </div>
                    
                    <div class="payment-methods">
                        <div class="methods-title">Métodos de pago aceptados</div>
                        <div class="methods-icons">
                            <div class="payment-icon">💳</div>
                            <div class="payment-icon">🏦</div>
                            <div class="payment-icon">📱</div>
                            <div class="payment-icon">💰</div>
                        </div>
                    </div>
                </div>
                
                {{-- SECURITY FEATURES --}}
                <div class="security-features">
                    <div class="feature-item">
                        <div class="feature-icon">🔒</div>
                        <div class="feature-text">
                            <strong>Compra Segura</strong>
                            <span>Protección SSL de 256 bits</span>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">🚚</div>
                        <div class="feature-text">
                            <strong>Envío Gratuito</strong>
                            <span>En pedidos superiores a $25</span>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">↩️</div>
                        <div class="feature-text">
                            <strong>Devolución Fácil</strong>
                            <span>30 días para devolver</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 🎵 RECOMMENDED SECTION --}}
<section class="recommended-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">También te puede interesar</h2>
            <p class="section-subtitle">Álbumes recomendados basados en tu selección</p>
        </div>
        
        <div class="recommended-grid">
            @for($i = 1; $i <= 4; $i++)
            <div class="recommended-card">
                <div class="card-image">
                    <img src="https://dummyimage.com/280x280/f5f5f7/1d1d1f.png?text=Album+{{ $i }}" alt="Álbum Recomendado {{ $i }}">
                    <div class="card-overlay">
                        <button class="quick-add-btn">Agregar Rápido</button>
                    </div>
                </div>
                <div class="card-content">
                    <h4>Álbum Recomendado {{ $i }}</h4>
                    <div class="card-price">${{ number_format(19.99 + ($i * 3), 2) }}</div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* 🛒 CART PAGE STYLES */

/* CART HERO */
.cart-hero {
    padding: 120px 0 60px;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.cart-header {
    text-align: center;
}

.cart-title {
    font-size: 48px;
    font-weight: 600;
    margin-bottom: 16px;
    color: var(--apple-dark);
}

.cart-subtitle {
    font-size: 18px;
    color: var(--apple-gray);
}

/* CART CONTENT */
.cart-content {
    padding: 80px 0;
}

.cart-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 60px;
    align-items: start;
}

/* CART ITEMS */
.cart-items {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.cart-item {
    background: var(--apple-white);
    border-radius: var(--apple-radius);
    padding: 24px;
    box-shadow: var(--apple-shadow);
    display: grid;
    grid-template-columns: 120px 1fr auto;
    gap: 24px;
    align-items: center;
    transition: var(--apple-transition);
}

.cart-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}

.item-image {
    width: 120px;
    height: 120px;
    border-radius: var(--apple-radius);
    overflow: hidden;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-details h3 {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--apple-dark);
}

.item-category {
    font-size: 14px;
    color: var(--apple-gray);
    margin-bottom: 12px;
}

.item-price {
    font-size: 18px;
    font-weight: 500;
    color: var(--apple-blue);
}

.item-controls {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 16px;
}

.quantity-control {
    display: flex;
    align-items: center;
    background: var(--apple-light-gray);
    border-radius: var(--apple-radius);
    padding: 4px;
}

.qty-btn {
    width: 36px;
    height: 36px;
    border: none;
    background: transparent;
    font-size: 18px;
    cursor: pointer;
    border-radius: 8px;
    transition: var(--apple-transition);
    display: flex;
    align-items: center;
    justify-content: center;
}

.qty-btn:hover {
    background: rgba(0,0,0,0.1);
}

.qty-input {
    width: 50px;
    text-align: center;
    border: none;
    background: transparent;
    font-size: 16px;
    font-weight: 500;
}

.item-total {
    font-size: 18px;
    font-weight: 600;
    color: var(--apple-dark);
}

.remove-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 8px;
    border-radius: 50%;
    transition: var(--apple-transition);
}

.remove-btn:hover {
    background: rgba(255, 59, 48, 0.1);
}

.remove-icon {
    font-size: 18px;
}

/* EMPTY CART */
.empty-cart {
    text-align: center;
    padding: 80px 40px;
    background: var(--apple-white);
    border-radius: var(--apple-radius);
    box-shadow: var(--apple-shadow);
}

.empty-icon {
    font-size: 80px;
    margin-bottom: 24px;
    opacity: 0.5;
}

.empty-cart h3 {
    font-size: 28px;
    font-weight: 600;
    margin-bottom: 16px;
    color: var(--apple-dark);
}

.empty-cart p {
    font-size: 16px;
    color: var(--apple-gray);
    margin-bottom: 32px;
    line-height: 1.5;
}

/* CART SUMMARY */
.cart-summary {
    position: sticky;
    top: 100px;
}

.summary-card {
    background: var(--apple-white);
    border-radius: var(--apple-radius);
    padding: 32px;
    box-shadow: var(--apple-shadow);
    margin-bottom: 24px;
}

.summary-title {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 24px;
    color: var(--apple-dark);
}

.summary-details {
    margin-bottom: 32px;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
    font-size: 16px;
}

.summary-row span:first-child {
    color: var(--apple-gray);
}

.summary-row span:last-child {
    font-weight: 500;
    color: var(--apple-dark);
}

.shipping {
    color: var(--apple-green) !important;
}

.summary-divider {
    height: 1px;
    background: var(--apple-light-gray);
    margin: 24px 0;
}

.total-row {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 0;
}

.total-row span {
    color: var(--apple-dark) !important;
}

.summary-actions {
    margin-bottom: 32px;
}

.btn-checkout {
    background: var(--apple-blue);
    color: var(--apple-white);
    border: none;
    padding: 16px 24px;
    border-radius: var(--apple-radius);
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--apple-transition);
    width: 100%;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-checkout:hover {
    background: #0056CC;
    transform: translateY(-2px);
}

.btn-continue {
    color: var(--apple-blue);
    text-decoration: none;
    font-weight: 500;
    display: block;
    text-align: center;
    transition: var(--apple-transition);
}

.btn-continue:hover {
    color: #0056CC;
}

.payment-methods {
    text-align: center;
}

.methods-title {
    font-size: 14px;
    color: var(--apple-gray);
    margin-bottom: 12px;
}

.methods-icons {
    display: flex;
    justify-content: center;
    gap: 12px;
}

.payment-icon {
    width: 40px;
    height: 40px;
    background: var(--apple-light-gray);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

/* SECURITY FEATURES */
.security-features {
    background: var(--apple-white);
    border-radius: var(--apple-radius);
    padding: 24px;
    box-shadow: var(--apple-shadow);
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 20px;
}

.feature-item:last-child {
    margin-bottom: 0;
}

.feature-icon {
    font-size: 24px;
    width: 48px;
    height: 48px;
    background: var(--apple-light-gray);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.feature-text {
    display: flex;
    flex-direction: column;
}

.feature-text strong {
    font-size: 14px;
    font-weight: 600;
    color: var(--apple-dark);
    margin-bottom: 2px;
}

.feature-text span {
    font-size: 12px;
    color: var(--apple-gray);
}

/* RECOMMENDED SECTION */
.recommended-section {
    padding: 80px 0;
    background: var(--apple-light-gray);
}

.recommended-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 24px;
}

.recommended-card {
    background: var(--apple-white);
    border-radius: var(--apple-radius);
    overflow: hidden;
    box-shadow: var(--apple-shadow);
    transition: var(--apple-transition);
}

.recommended-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}

.recommended-card .card-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.recommended-card .card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.recommended-card .card-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: var(--apple-transition);
}

.recommended-card:hover .card-overlay {
    opacity: 1;
}

.quick-add-btn {
    background: var(--apple-white);
    color: var(--apple-dark);
    border: none;
    padding: 12px 20px;
    border-radius: var(--apple-radius);
    font-weight: 500;
    cursor: pointer;
    transition: var(--apple-transition);
}

.quick-add-btn:hover {
    background: var(--apple-blue);
    color: var(--apple-white);
}

.recommended-card .card-content {
    padding: 20px;
}

.recommended-card h4 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--apple-dark);
}

.recommended-card .card-price {
    font-size: 16px;
    font-weight: 500;
    color: var(--apple-blue);
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .cart-layout {
        grid-template-columns: 1fr;
        gap: 40px;
    }
    
    .cart-item {
        grid-template-columns: 80px 1fr;
        gap: 16px;
    }
    
    .item-image {
        width: 80px;
        height: 80px;
    }
    
    .item-controls {
        grid-column: 1 / -1;
        flex-direction: row;
        justify-content: space-between;
        margin-top: 16px;
    }
    
    .cart-title {
        font-size: 32px;
    }
    
    .recommended-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 480px) {
    .recommended-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize cart functionality
    initializeCart();
    
    function initializeCart() {
        // Quantity controls
        document.querySelectorAll('.qty-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const action = this.dataset.action;
                const input = this.parentElement.querySelector('.qty-input');
                const cartItem = this.closest('.cart-item');
                
                let currentValue = parseInt(input.value) || 1;
                
                if (action === 'increase' && currentValue < 99) {
                    input.value = currentValue + 1;
                } else if (action === 'decrease' && currentValue > 1) {
                    input.value = currentValue - 1;
                }
                
                updateItemTotal(cartItem);
                updateCartSummary();
            });
        });
        
        // Quantity input changes
        document.querySelectorAll('.qty-input').forEach(input => {
            input.addEventListener('change', function() {
                const cartItem = this.closest('.cart-item');
                updateItemTotal(cartItem);
                updateCartSummary();
            });
        });
        
        // Remove item buttons
        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const cartItem = this.closest('.cart-item');
                removeCartItem(cartItem);
            });
        });
        
        // Checkout button
        const checkoutBtn = document.getElementById('checkoutBtn');
        if (checkoutBtn) {
            checkoutBtn.addEventListener('click', function() {
                this.disabled = true;
                this.innerHTML = '<span>Procesando...</span>';
                
                // Simulate checkout process
                setTimeout(() => {
                    showNotification('🎉 ¡Pedido procesado exitosamente!', 'success');
                    this.disabled = false;
                    this.innerHTML = '<span>Proceder al Pago</span><span class="checkout-icon">→</span>';
                }, 2000);
            });
        }
        
        // Quick add buttons
        document.querySelectorAll('.quick-add-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                this.disabled = true;
                this.textContent = 'Agregando...';
                
                setTimeout(() => {
                    this.disabled = false;
                    this.textContent = 'Agregar Rápido';
                    showNotification('✅ Producto agregado al carrito', 'success');
                }, 1000);
            });
        });
        
        // Initial cart summary update
        updateCartSummary();
    }
    
    function updateItemTotal(cartItem) {
        const priceElement = cartItem.querySelector('.item-price');
        const quantityInput = cartItem.querySelector('.qty-input');
        const totalElement = cartItem.querySelector('.item-total');
        
        if (priceElement && quantityInput && totalElement) {
            const price = parseFloat(priceElement.textContent.replace('$', ''));
            const quantity = parseInt(quantityInput.value) || 1;
            const total = price * quantity;
            
            totalElement.textContent = `$${total.toFixed(2)}`;
        }
    }
    
    function updateCartSummary() {
        const cartItems = document.querySelectorAll('.cart-item');
        let subtotal = 0;
        
        cartItems.forEach(item => {
            const totalElement = item.querySelector('.item-total');
            if (totalElement) {
                const total = parseFloat(totalElement.textContent.replace('$', ''));
                subtotal += total;
            }
        });
        
        const taxes = subtotal * 0.1; // 10% tax
        const total = subtotal + taxes;
        
        // Update summary elements
        const subtotalElement = document.querySelector('.subtotal');
        const taxesElement = document.querySelector('.taxes');
        const totalElement = document.querySelector('.total');
        
        if (subtotalElement) subtotalElement.textContent = `$${subtotal.toFixed(2)}`;
        if (taxesElement) taxesElement.textContent = `$${taxes.toFixed(2)}`;
        if (totalElement) totalElement.textContent = `$${total.toFixed(2)}`;
        
        // Update cart badge
        const itemCount = Array.from(cartItems).reduce((count, item) => {
            const qty = parseInt(item.querySelector('.qty-input').value) || 1;
            return count + qty;
        }, 0);
        
        updateCartBadge(itemCount);
    }
    
    function removeCartItem(cartItem) {
        cartItem.style.transform = 'translateX(-100%)';
        cartItem.style.opacity = '0';
        
        setTimeout(() => {
            cartItem.remove();
            updateCartSummary();
            
            // Check if cart is empty
            const remainingItems = document.querySelectorAll('.cart-item');
            if (remainingItems.length === 0) {
                showEmptyCart();
            }
            
            showNotification('🗑️ Producto eliminado del carrito', 'success');
        }, 300);
    }
    
    function showEmptyCart() {
        const cartItems = document.querySelector('.cart-items');
        cartItems.innerHTML = `
            <div class="empty-cart">
                <div class="empty-icon">🛒</div>
                <h3>Tu carrito está vacío</h3>
                <p>Descubre nuestra increíble colección de música y agrega algunos álbumes a tu carrito.</p>
                <a href="${window.location.origin}" class="btn-apple">Explorar Catálogo</a>
            </div>
        `;
    }
    
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 32px;
            right: 32px;
            background: ${type === 'success' ? '#34C759' : '#FF3B30'};
            color: white;
            padding: 16px 24px;
            border-radius: 12px;
            font-weight: 500;
            z-index: 9999;
            box-shadow: 0 8px 30px rgba(0,0,0,0.2);
            transform: translateX(400px);
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        `;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(400px)';
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }
});
</script>
@endpush