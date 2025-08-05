@extends('web.app')

@section('title', 'Catálogo - Rock Store')

@section('contenido')

{{-- 🎸 CATALOG HEADER --}}
<section class="catalog-header">
    <div class="container">
        <div class="header-content">
            <h1>Catálogo Musical</h1>
            <p>{{ isset($productos) ? count($productos) : '120' }} productos encontrados</p>
        </div>
    </div>
</section>

{{-- 🔍 FILTERS BAR --}}
<section class="filters-bar">
    <div class="container">
        <div class="filters-wrapper">
            <div class="filters-left">
                <select class="filter-dropdown">
                    <option>Todos los géneros</option>
                    <option>Rock Clásico</option>
                    <option>Pop Internacional</option>
                    <option>Jazz & Blues</option>
                    <option>Electrónica</option>
                </select>
                
                <select class="filter-dropdown">
                    <option>Todos los precios</option>
                    <option>$0 - $25</option>
                    <option>$25 - $50</option>
                    <option>$50+</option>
                </select>
            </div>
            
            <div class="filters-right">
                <div class="search-box">
                    <input type="text" placeholder="Buscar álbumes...">
                    <button>🔍</button>
                </div>
                
                <select class="sort-dropdown">
                    <option>Ordenar por relevancia</option>
                    <option>Precio: menor a mayor</option>
                    <option>Precio: mayor a menor</option>
                    <option>Nombre A-Z</option>
                </select>
            </div>
        </div>
    </div>
</section>

{{-- 📀 PRODUCTS GRID --}}
<section class="products-grid-section">
    <div class="container">
        <div class="products-grid">
            @if(isset($productos) && count($productos) > 0)
                @foreach($productos as $producto)
                <div class="product-item">
                    <div class="product-image-wrapper">
                        <img src="{{ $producto->imagen ? asset('uploads/productos/' . $producto->imagen) : 'https://dummyimage.com/280x280/f5f5f7/1d1d1f.png?text=🎵' }}" 
                             alt="{{ $producto->nombre }}">
                        <div class="product-actions">
                            <button class="action-btn favorite-btn">♡</button>
                            <button class="action-btn cart-btn">🛒</button>
                        </div>
                    </div>
                    <div class="product-details">
                        <div class="product-category">{{ $producto->categoria->nombre ?? 'Música' }}</div>
                        <h3 class="product-title">
                            <a href="{{ route('web.show', $producto->id) }}">{{ $producto->nombre }}</a>
                        </h3>
                        <div class="product-price">${{ number_format($producto->precio, 2) }}</div>
                        <div class="product-rating">
                            <span class="stars">★★★★★</span>
                            <span class="reviews">({{ rand(10, 500) }})</span>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                {{-- Productos de ejemplo con patrón Alibaba --}}
                @for($i = 1; $i <= 24; $i++)
                <div class="product-item">
                    <div class="product-image-wrapper">
                        <img src="https://dummyimage.com/280x280/f5f5f7/1d1d1f.png?text=Album+{{ $i }}" alt="Álbum {{ $i }}">
                        <div class="product-actions">
                            <button class="action-btn favorite-btn">♡</button>
                            <button class="action-btn cart-btn">🛒</button>
                        </div>
                    </div>
                    <div class="product-details">
                        <div class="product-category">{{ ['Rock Clásico', 'Pop Internacional', 'Jazz & Blues', 'Electrónica'][($i-1) % 4] }}</div>
                        <h3 class="product-title">
                            <a href="#">{{ ['Greatest Hits Collection', 'Live Concert Experience', 'Studio Sessions', 'Acoustic Versions'][($i-1) % 4] }} {{ $i }}</a>
                        </h3>
                        <div class="product-price">${{ number_format(15 + ($i * 2.5), 2) }}</div>
                        <div class="product-rating">
                            <span class="stars">★★★★{{ $i % 2 ? '★' : '☆' }}</span>
                            <span class="reviews">({{ rand(10, 500) }})</span>
                        </div>
                    </div>
                </div>
                @endfor
            @endif
        </div>
        
        {{-- PAGINATION --}}
        <div class="pagination-wrapper">
            <div class="pagination">
                <button class="page-btn prev-btn" disabled>‹</button>
                <button class="page-btn active">1</button>
                <button class="page-btn">2</button>
                <button class="page-btn">3</button>
                <button class="page-btn">4</button>
                <button class="page-btn">5</button>
                <span class="page-dots">...</span>
                <button class="page-btn">20</button>
                <button class="page-btn next-btn">›</button>
            </div>
            <div class="pagination-info">
                Mostrando 1-24 de 480 productos
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* 🎸 CATALOG STYLES - ALIBABA INSPIRED WITH APPLE AESTHETICS */

/* CATALOG HEADER */
.catalog-header {
    padding: 100px 0 40px;
    background: var(--apple-white);
    border-bottom: 1px solid rgba(0,0,0,0.05);
}

.header-content {
    text-align: center;
}

.header-content h1 {
    font-size: 32px;
    font-weight: 600;
    color: var(--apple-dark);
    margin-bottom: 8px;
}

.header-content p {
    font-size: 16px;
    color: var(--apple-gray);
}

/* FILTERS BAR */
.filters-bar {
    padding: 20px 0;
    background: var(--apple-white);
    border-bottom: 1px solid rgba(0,0,0,0.05);
    position: sticky;
    top: 64px;
    z-index: 100;
}

.filters-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

.filters-left {
    display: flex;
    gap: 16px;
}

.filters-right {
    display: flex;
    gap: 16px;
    align-items: center;
}

.filter-dropdown, .sort-dropdown {
    padding: 8px 16px;
    border: 1px solid rgba(0,0,0,0.1);
    border-radius: 8px;
    background: var(--apple-white);
    font-size: 14px;
    color: var(--apple-dark);
    min-width: 140px;
    transition: var(--apple-transition);
}

.filter-dropdown:hover, .sort-dropdown:hover {
    border-color: var(--apple-blue);
}

.search-box {
    display: flex;
    border: 1px solid rgba(0,0,0,0.1);
    border-radius: 8px;
    overflow: hidden;
    background: var(--apple-white);
}

.search-box input {
    padding: 8px 16px;
    border: none;
    outline: none;
    font-size: 14px;
    width: 200px;
}

.search-box button {
    padding: 8px 12px;
    background: var(--apple-blue);
    color: var(--apple-white);
    border: none;
    cursor: pointer;
    transition: var(--apple-transition);
}

.search-box button:hover {
    background: #0056CC;
}

/* PRODUCTS GRID */
.products-grid-section {
    padding: 40px 0 80px;
    background: #fafafa;
}

.products-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 16px;
    margin-bottom: 60px;
}

.product-item {
    background: var(--apple-white);
    border-radius: 8px;
    overflow: hidden;
    transition: var(--apple-transition);
    border: 1px solid rgba(0,0,0,0.05);
}

.product-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    border-color: rgba(0,0,0,0.1);
}

.product-image-wrapper {
    position: relative;
    height: 160px;
    overflow: hidden;
}

.product-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--apple-transition);
}

.product-item:hover .product-image-wrapper img {
    transform: scale(1.05);
}

.product-actions {
    position: absolute;
    top: 12px;
    right: 12px;
    display: flex;
    flex-direction: column;
    gap: 8px;
    opacity: 0;
    transition: var(--apple-transition);
}

.product-item:hover .product-actions {
    opacity: 1;
}

.action-btn {
    width: 36px;
    height: 36px;
    background: rgba(255,255,255,0.9);
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--apple-transition);
    backdrop-filter: blur(10px);
}

.action-btn:hover {
    background: var(--apple-blue);
    color: var(--apple-white);
    transform: scale(1.1);
}

.product-details {
    padding: 12px;
}

.product-category {
    font-size: 11px;
    color: var(--apple-gray);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.product-title {
    margin-bottom: 6px;
}

.product-title a {
    font-size: 14px;
    font-weight: 500;
    color: var(--apple-dark);
    text-decoration: none;
    line-height: 1.3;
    height: 36px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: var(--apple-transition);
}

.product-title a:hover {
    color: var(--apple-blue);
}

.product-price {
    font-size: 16px;
    font-weight: 600;
    color: var(--apple-blue);
    margin-bottom: 6px;
}

.product-rating {
    display: flex;
    align-items: center;
    gap: 6px;
}

.stars {
    color: #ffa500;
    font-size: 14px;
}

.reviews {
    font-size: 12px;
    color: var(--apple-gray);
}

/* PAGINATION */
.pagination-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 40px;
    border-top: 1px solid rgba(0,0,0,0.05);
}

.pagination {
    display: flex;
    gap: 4px;
    align-items: center;
}

.page-btn {
    width: 40px;
    height: 40px;
    border: 1px solid rgba(0,0,0,0.1);
    background: var(--apple-white);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--apple-transition);
    font-size: 14px;
    font-weight: 500;
}

.page-btn:hover:not(:disabled) {
    border-color: var(--apple-blue);
    color: var(--apple-blue);
}

.page-btn.active {
    background: var(--apple-blue);
    color: var(--apple-white);
    border-color: var(--apple-blue);
}

.page-btn:disabled {
    opacity: 0.3;
    cursor: not-allowed;
}

.page-dots {
    padding: 0 8px;
    color: var(--apple-gray);
}

.pagination-info {
    font-size: 14px;
    color: var(--apple-gray);
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .filters-wrapper {
        flex-direction: column;
        gap: 16px;
    }
    
    .filters-left, .filters-right {
        width: 100%;
        justify-content: space-between;
    }
    
    .search-box input {
        width: 150px;
    }
    
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .pagination-wrapper {
        flex-direction: column;
        gap: 16px;
        text-align: center;
    }
    
    .pagination {
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .product-details {
        padding: 12px;
    }
    
    .filters-left, .filters-right {
        flex-direction: column;
        gap: 12px;
    }
}

/* LOADING STATES */
.product-item.loading {
    opacity: 0.6;
    pointer-events: none;
}

.product-item.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid var(--apple-blue);
    border-top: 2px solid transparent;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add to cart functionality
    document.querySelectorAll('.cart-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const productItem = this.closest('.product-item');
            productItem.classList.add('loading');
            
            setTimeout(() => {
                productItem.classList.remove('loading');
                showNotification('✅ Producto agregado al carrito', 'success');
                updateCartBadge(Math.floor(Math.random() * 5) + 1);
            }, 800);
        });
    });
    
    // Favorite functionality
    document.querySelectorAll('.favorite-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            if (this.textContent === '♡') {
                this.textContent = '♥';
                this.style.color = '#FF3B30';
                showNotification('❤️ Agregado a favoritos', 'success');
            } else {
                this.textContent = '♡';
                this.style.color = '';
                showNotification('💔 Eliminado de favoritos', 'info');
            }
        });
    });
    
    // Search functionality
    const searchInput = document.querySelector('.search-box input');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const products = document.querySelectorAll('.product-item');
            
            products.forEach(product => {
                const title = product.querySelector('.product-title a').textContent.toLowerCase();
                const category = product.querySelector('.product-category').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || category.includes(searchTerm)) {
                    product.style.display = 'block';
                } else {
                    product.style.display = searchTerm ? 'none' : 'block';
                }
            });
        });
    }
    
    // Pagination
    document.querySelectorAll('.page-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (this.disabled) return;
            
            document.querySelectorAll('.page-btn').forEach(b => b.classList.remove('active'));
            if (!this.classList.contains('prev-btn') && !this.classList.contains('next-btn')) {
                this.classList.add('active');
            }
            
            // Simulate page load
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    });
    
    // Notification system
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 32px;
            right: 32px;
            background: ${type === 'success' ? '#34C759' : type === 'info' ? '#007AFF' : '#FF3B30'};
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