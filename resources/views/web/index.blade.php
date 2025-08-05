@extends('web.app')

@section('title', 'Rock Store - Descubre la Mejor Música')

@section('contenido')

{{-- 🍎 HERO SECTION --}}
<section class="hero-home">
    <div class="hero-content">
        <div class="hero-text">
            <h1 class="hero-title">Descubre la música que te inspira</h1>
            <p class="hero-subtitle">Explora nuestra colección curada de álbumes legendarios, desde clásicos del rock hasta las últimas tendencias musicales.</p>
            <div class="hero-actions">
                <a href="#catalog" class="btn-apple">Explorar Catálogo</a>
                <a href="#featured" class="btn-secondary">Ver Destacados</a>
            </div>
        </div>
        <div class="hero-visual">
            <div class="floating-albums">
                <div class="album-float album-1">🎸</div>
                <div class="album-float album-2">🎵</div>
                <div class="album-float album-3">🎤</div>
            </div>
        </div>
    </div>
</section>

{{-- 🔍 FILTERS SECTION --}}
<section class="filters-section" id="catalog">
    <div class="container">
        <form method="GET" action="{{ route('web.index') }}" class="filters-form">
            <div class="filters-grid">
                <div class="filter-group">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar productos..." class="search-input">
                </div>
                
                <div class="filter-group">
                    <select name="categoria" class="filter-select">
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ request('categoria') == $categoria->id ? 'selected' : '' }}>
                                {{ $categoria->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-group">
                    <select name="decada" class="filter-select">
                        <option value="">Todas las décadas</option>
                        @foreach($decadas as $decada)
                            <option value="{{ $decada->id }}" {{ request('decada') == $decada->id ? 'selected' : '' }}>
                                {{ $decada->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-group">
                    <select name="pais" class="filter-select">
                        <option value="">Todos los países</option>
                        @foreach($paises as $pais)
                            <option value="{{ $pais->id }}" {{ request('pais') == $pais->id ? 'selected' : '' }}>
                                {{ $pais->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-group">
                    <select name="sort" class="filter-select">
                        <option value="nombre" {{ request('sort') == 'nombre' ? 'selected' : '' }}>Nombre A-Z</option>
                        <option value="priceAsc" {{ request('sort') == 'priceAsc' ? 'selected' : '' }}>Precio: Menor a Mayor</option>
                        <option value="priceDesc" {{ request('sort') == 'priceDesc' ? 'selected' : '' }}>Precio: Mayor a Menor</option>
                        <option value="year" {{ request('sort') == 'year' ? 'selected' : '' }}>Año</option>
                    </select>
                </div>
                
                <button type="submit" class="filter-btn">Filtrar</button>
            </div>
        </form>
    </div>
</section>

{{-- 🌟 PRODUCTS SECTION --}}
<section class="featured-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Catálogo de Productos</h2>
            <p class="section-subtitle">{{ $productos->total() }} productos encontrados</p>
        </div>
        
        <div class="featured-grid">
            @if($productos->count() > 0)
                @foreach($productos->take(16) as $producto)
                <div class="featured-card">
                    <div class="card-image">
                        <img src="{{ $producto->imagen ? asset('uploads/productos/' . $producto->imagen) : 'https://via.placeholder.com/300x300/f5f5f7/1d1d1f?text=Album' }}" 
                             alt="{{ $producto->nombre }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                        <div class="card-overlay">
                            <a href="{{ route('web.show', $producto->id) }}" class="view-btn">Ver Detalles</a>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="card-category">{{ $producto->categoria->nombre ?? 'Música' }}</div>
                        <h3 class="card-title" title="{{ $producto->nombre }}">{{ $producto->nombre }}</h3>
                        <div class="card-price">${{ number_format($producto->precio, 2) }}</div>
                        <button class="add-to-cart-btn" data-product-id="{{ $producto->id }}">
                            Agregar al Carrito
                        </button>
                    </div>
                </div>
                @endforeach
            @else
                <div class="no-products">
                    <p>No se encontraron productos con los filtros seleccionados.</p>
                </div>
            @endif
        </div>
        

    </div>
</section>

{{-- 🎯 CATEGORIES SECTION --}}
<section class="categories-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Catálogo de Géneros</h2>
            <p class="section-subtitle">Encuentra tu estilo musical perfecto</p>
        </div>
        
        <div class="carousel-container">
            <button class="carousel-btn prev-btn" id="prevBtn">‹</button>
            
            <div class="carousel-wrapper">
                <div class="carousel-track" id="carouselTrack">
                    <div class="genre-card">
                        <div class="genre-icon">🎸</div>
                        <h3>Rock Clásico</h3>
                        <p>Los grandes clásicos que definieron una era</p>
                        <a href="#" class="explore-link">Explorar →</a>
                    </div>
                    
                    <div class="genre-card">
                        <div class="genre-icon">🎤</div>
                        <h3>Pop Internacional</h3>
                        <p>Los éxitos más populares del momento</p>
                        <a href="#" class="explore-link">Explorar →</a>
                    </div>
                    
                    <div class="genre-card">
                        <div class="genre-icon">🎷</div>
                        <h3>Jazz & Blues</h3>
                        <p>Sonidos sofisticados y atemporales</p>
                        <a href="#" class="explore-link">Explorar →</a>
                    </div>
                    
                    <div class="genre-card">
                        <div class="genre-icon">🎧</div>
                        <h3>Electrónica</h3>
                        <p>Beats modernos y sonidos innovadores</p>
                        <a href="#" class="explore-link">Explorar →</a>
                    </div>
                    
                    <div class="genre-card">
                        <div class="genre-icon">🌴</div>
                        <h3>Reggae</h3>
                        <p>Ritmos relajantes del Caribe</p>
                        <a href="#" class="explore-link">Explorar →</a>
                    </div>
                    
                    <div class="genre-card">
                        <div class="genre-icon">⚡</div>
                        <h3>Heavy Metal</h3>
                        <p>Potencia y energía pura</p>
                        <a href="#" class="explore-link">Explorar →</a>
                    </div>
                    

                </div>
            </div>
            
            <button class="carousel-btn next-btn" id="nextBtn">›</button>
        </div>
    </div>
</section>

{{-- 📱 EXPERIENCE SECTION --}}
<section class="experience-section">
    <div class="container">
        <div class="experience-content">
            <div class="experience-text">
                <h2>Catálogo de Experiencias</h2>
                <p>Descubre música como nunca antes. Nuestra plataforma te ofrece información detallada sobre cada álbum, análisis musical profundo y la historia detrás de cada canción.</p>
                
                <div class="features-list">
                    <div class="feature-item">
                        <div class="feature-icon">🎵</div>
                        <div class="feature-content">
                            <h4>Análisis Musical Detallado</h4>
                            <p>Comprende la estructura y técnicas de cada canción</p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">📚</div>
                        <div class="feature-content">
                            <h4>Historia y Contexto</h4>
                            <p>Conoce el trasfondo de cada álbum y artista</p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">🎯</div>
                        <div class="feature-content">
                            <h4>Recomendaciones Personalizadas</h4>
                            <p>Descubre nueva música basada en tus gustos</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="experience-visual">
                <div class="phone-mockup">
                    <div class="phone-screen">
                        <div class="app-interface">
                            <div class="now-playing">
                                <div class="album-art">🎸</div>
                                <div class="song-info">
                                    <div class="song-title">Bohemian Rhapsody</div>
                                    <div class="artist-name">Queen</div>
                                </div>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')
<style>
/* 🍎 HOME PAGE STYLES */

/* HERO SECTION */
.hero-home {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    position: relative;
    overflow: hidden;
}

.hero-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: center;
}

.hero-text {
    color: var(--apple-white);
}

.hero-title {
    font-size: 56px;
    font-weight: 600;
    line-height: 1.1;
    margin-bottom: 24px;
}

.hero-subtitle {
    font-size: 20px;
    line-height: 1.5;
    margin-bottom: 40px;
    opacity: 0.9;
}

.hero-actions {
    display: flex;
    gap: 16px;
}

.hero-visual {
    position: relative;
    height: 500px;
}

.floating-albums {
    position: relative;
    width: 100%;
    height: 100%;
}

.album-float {
    position: absolute;
    width: 120px;
    height: 120px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border-radius: var(--apple-radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    animation: float 6s ease-in-out infinite;
}

.album-1 {
    top: 20%;
    left: 10%;
    animation-delay: 0s;
}

.album-2 {
    top: 50%;
    right: 20%;
    animation-delay: 2s;
}

.album-3 {
    bottom: 20%;
    left: 30%;
    animation-delay: 4s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(5deg); }
}

/* FILTERS SECTION */
.filters-section {
    padding: 40px 0;
    background: var(--apple-light-gray);
    border-bottom: 1px solid rgba(0,0,0,0.1);
}

.filters-form {
    max-width: 1200px;
    margin: 0 auto;
}

.filters-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr 1fr 1fr auto;
    gap: 16px;
    align-items: center;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.search-input,
.filter-select {
    padding: 12px 16px;
    border: 1px solid rgba(0,0,0,0.1);
    border-radius: var(--apple-radius);
    background: var(--apple-white);
    font-size: 14px;
    transition: var(--apple-transition);
}

.search-input:focus,
.filter-select:focus {
    outline: none;
    border-color: var(--apple-blue);
    box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
}

.filter-btn {
    background: var(--apple-blue);
    color: var(--apple-white);
    border: none;
    padding: 12px 24px;
    border-radius: var(--apple-radius);
    font-weight: 500;
    cursor: pointer;
    transition: var(--apple-transition);
    white-space: nowrap;
}

.filter-btn:hover {
    background: #0056CC;
}

.no-products {
    text-align: center;
    padding: 60px 20px;
    color: var(--apple-gray);
    font-size: 18px;
}



/* FEATURED SECTION */
.featured-section {
    padding: 40px 0;
    background: var(--apple-white);
}

.section-header {
    text-align: center;
    margin-bottom: 80px;
}

.section-title {
    font-size: 40px;
    font-weight: 600;
    margin-bottom: 16px;
    color: var(--apple-dark);
}

.section-subtitle {
    font-size: 18px;
    color: var(--apple-gray);
}

.featured-grid {
    display: grid;
    grid-template-columns: repeat(8, 1fr);
    gap: 8px;
    width: 100%;
    padding: 0 12px;
}

.featured-card {
    background: var(--apple-white);
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: var(--apple-transition);
    border: 1px solid rgba(0,0,0,0.05);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.featured-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
}

.card-image {
    position: relative;
    height: 140px;
    overflow: hidden;
}

.card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--apple-transition);
}

.card-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: var(--apple-transition);
}

.featured-card:hover .card-overlay {
    opacity: 1;
}

.view-btn {
    background: var(--apple-white);
    color: var(--apple-dark);
    padding: 12px 24px;
    border-radius: var(--apple-radius);
    text-decoration: none;
    font-weight: 500;
    transition: var(--apple-transition);
}

.view-btn:hover {
    background: var(--apple-blue);
    color: var(--apple-white);
}

.card-content {
    padding: 8px;
    display: flex;
    flex-direction: column;
    flex-grow: 1;
}

.card-category {
    font-size: 11px;
    color: var(--apple-gray);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.card-title {
    font-size: 12px;
    font-weight: 400;
    margin-bottom: 4px;
    color: var(--apple-dark);
    line-height: 1.2;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.card-price {
    font-size: 12px;
    font-weight: 600;
    color: #ff6b35;
    margin-bottom: 3px;
}

.add-to-cart-btn {
    background: #ff6b35;
    color: var(--apple-white);
    border: none;
    padding: 6px 8px;
    border-radius: 4px;
    font-weight: 400;
    cursor: pointer;
    transition: var(--apple-transition);
    width: 100%;
    font-size: 10px;
    margin-top: auto;
}

.add-to-cart-btn:hover {
    background: #0056CC;
}

/* CATEGORIES SECTION */
.categories-section {
    padding: 80px 0;
    background: var(--apple-light-gray);
}

.carousel-container {
    position: relative;
    width: 100%;
    padding: 0 12px;
}

.carousel-wrapper {
    overflow: hidden;
    margin: 0;
}

.carousel-track {
    display: grid;
    grid-template-columns: repeat(8, 1fr);
    gap: 8px;
    transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    width: 100%;
}

.genre-card {
    background: var(--apple-white);
    padding: 8px;
    border-radius: 6px;
    text-align: center;
    height: 180px;
    transition: var(--apple-transition);
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    border: 1px solid rgba(0,0,0,0.05);
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.genre-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--apple-blue);
    transform: scaleX(0);
    transition: var(--apple-transition);
}

.genre-card:hover::before {
    transform: scaleX(1);
}

.genre-card:hover {
    transform: translateY(-6px);
    box-shadow: var(--apple-shadow);
}

.genre-icon {
    font-size: 28px;
    margin-bottom: 8px;
    height: 140px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f5f5f7;
    border-radius: 4px;
}

.genre-card h3 {
    font-size: 12px;
    font-weight: 400;
    margin-bottom: 4px;
    color: var(--apple-dark);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.genre-card p {
    color: var(--apple-gray);
    margin-bottom: 4px;
    line-height: 1.2;
    font-size: 9px;
    flex-grow: 1;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
}

.explore-link {
    background: #ff6b35;
    color: var(--apple-white);
    text-decoration: none;
    font-weight: 400;
    font-size: 10px;
    padding: 6px 8px;
    border-radius: 4px;
    transition: var(--apple-transition);
    margin-top: auto;
    display: inline-block;
    width: 100%;
    text-align: center;
}

.explore-link:hover {
    color: #0056CC;
}

.carousel-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    background: var(--apple-white);
    border: 1px solid rgba(0,0,0,0.1);
    border-radius: 50%;
    font-size: 20px;
    cursor: pointer;
    transition: var(--apple-transition);
    z-index: 10;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--apple-dark);
    box-shadow: 0 2px 12px rgba(0,0,0,0.1);
}

.carousel-btn:hover {
    background: var(--apple-blue);
    color: var(--apple-white);
    border-color: var(--apple-blue);
    transform: translateY(-50%) scale(1.05);
}

.prev-btn {
    left: 20px;
}

.next-btn {
    right: 20px;
}

/* EXPERIENCE SECTION */
.experience-section {
    padding: 80px 0;
    background: var(--apple-white);
}

.experience-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: center;
}

.experience-text h2 {
    font-size: 40px;
    font-weight: 600;
    margin-bottom: 24px;
    color: var(--apple-dark);
}

.experience-text p {
    font-size: 18px;
    color: var(--apple-gray);
    line-height: 1.6;
    margin-bottom: 40px;
}

.features-list {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.feature-item {
    display: flex;
    gap: 16px;
    align-items: flex-start;
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

.feature-content h4 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--apple-dark);
}

.feature-content p {
    color: var(--apple-gray);
    margin: 0;
}

.phone-mockup {
    width: 280px;
    height: 560px;
    background: var(--apple-dark);
    border-radius: 32px;
    padding: 8px;
    margin: 0 auto;
    position: relative;
}

.phone-screen {
    width: 100%;
    height: 100%;
    background: var(--apple-white);
    border-radius: 24px;
    padding: 40px 20px 20px;
    position: relative;
}

.phone-screen::before {
    content: '';
    position: absolute;
    top: 12px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 4px;
    background: var(--apple-dark);
    border-radius: 2px;
}

.app-interface {
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 24px;
}

.now-playing {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: var(--apple-light-gray);
    border-radius: 12px;
}

.album-art {
    width: 60px;
    height: 60px;
    background: var(--apple-blue);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: var(--apple-white);
}

.song-title {
    font-weight: 600;
    color: var(--apple-dark);
}

.artist-name {
    font-size: 14px;
    color: var(--apple-gray);
}

.progress-bar {
    height: 4px;
    background: var(--apple-light-gray);
    border-radius: 2px;
    overflow: hidden;
}

.progress-fill {
    width: 60%;
    height: 100%;
    background: var(--apple-blue);
    border-radius: 2px;
    animation: progress 3s ease-in-out infinite;
}

@keyframes progress {
    0% { width: 30%; }
    50% { width: 70%; }
    100% { width: 30%; }
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .hero-content {
        grid-template-columns: 1fr;
        gap: 40px;
        text-align: center;
    }
    
    .hero-title {
        font-size: 36px;
    }
    
    .hero-actions {
        justify-content: center;
    }
    
    .filters-grid {
        grid-template-columns: 1fr;
        gap: 12px;
    }
    
    .experience-content {
        grid-template-columns: 1fr;
        gap: 60px;
    }
    
    .section-title {
        font-size: 32px;
    }
    
    .featured-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
    
    .carousel-wrapper {
        margin: 0 20px;
    }
    
    .carousel-btn {
        display: none;
    }
    
    .carousel-track {
        gap: 16px;
    }
    
    .genre-card {
        min-width: 160px;
        padding: 12px;
        height: 200px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add to cart functionality for featured products
    const addToCartBtns = document.querySelectorAll('.add-to-cart-btn');
    
    addToCartBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const productId = this.dataset.productId;
            
            // Disable button temporarily
            this.disabled = true;
            this.textContent = 'Agregando...';
            
            // Simulate API call
            setTimeout(() => {
                this.disabled = false;
                this.textContent = 'Agregar al Carrito';
                showNotification('✅ Producto agregado al carrito', 'success');
                updateCartBadge(Math.floor(Math.random() * 5) + 1);
            }, 1000);
        });
    });
    
    // Notification system
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
    
    // Smooth scroll for hero CTA
    document.querySelector('a[href="#catalog"]')?.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelector('.filters-section').scrollIntoView({
            behavior: 'smooth'
        });
    });
    
    // Auto-submit filters on change
    const filterSelects = document.querySelectorAll('.filter-select');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.closest('form').submit();
        });
    });
    
    // Search input with debounce
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.closest('form').submit();
            }, 500);
        });
    }
    
    // Parallax effect for floating albums
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const albums = document.querySelectorAll('.album-float');
        
        albums.forEach((album, index) => {
            const speed = 0.5 + (index * 0.1);
            album.style.transform = `translateY(${scrolled * speed}px) rotate(${scrolled * 0.02}deg)`;
        });
    });
    
    // Genre carousel
    const track = document.getElementById('carouselTrack');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    
    if (track && prevBtn && nextBtn) {
        let currentIndex = 0;
        const cardWidth = 304; // 280px + 24px gap
        const totalCards = track.children.length;
        const visibleCards = Math.floor(window.innerWidth / cardWidth);
        const maxIndex = totalCards - visibleCards;
        
        function updateCarousel() {
            const translateX = -currentIndex * cardWidth;
            track.style.transform = `translateX(${translateX}px)`;
        }
        
        prevBtn.addEventListener('click', function() {
            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            }
        });
        
        nextBtn.addEventListener('click', function() {
            if (currentIndex < maxIndex) {
                currentIndex++;
                updateCarousel();
            }
        });
        
        // Touch support for mobile
        let startX = 0;
        let isDragging = false;
        
        track.addEventListener('touchstart', function(e) {
            startX = e.touches[0].clientX;
            isDragging = true;
        });
        
        track.addEventListener('touchmove', function(e) {
            if (!isDragging) return;
            e.preventDefault();
        });
        
        track.addEventListener('touchend', function(e) {
            if (!isDragging) return;
            
            const endX = e.changedTouches[0].clientX;
            const diffX = startX - endX;
            
            if (Math.abs(diffX) > 50) {
                if (diffX > 0 && currentIndex < maxIndex) {
                    currentIndex++;
                } else if (diffX < 0 && currentIndex > 0) {
                    currentIndex--;
                }
                updateCarousel();
            }
            
            isDragging = false;
        });
    }
});

// Global function to update cart badge
window.updateCartBadge = function(count) {
    const badge = document.getElementById('cartBadge');
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'block' : 'none';
    }
};
</script>
@endpush