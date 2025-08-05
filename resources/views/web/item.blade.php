@extends('web.app')
@section('contenido')

{{-- 🍎 HERO SECTION - JONY IVE STYLE --}}
<section class="hero-product">
    <div class="container-fluid">
        <div class="row min-vh-100 align-items-center">
            <div class="col-lg-6 order-2 order-lg-1">
                <div class="product-showcase">
                    <div class="product-image-container">
                        <img src="{{ $producto->imagen ? asset('uploads/productos/' . $producto->imagen) : 'https://dummyimage.com/800x800/f5f5f7/1d1d1f.png?text=🎸' }}" 
                             alt="{{ $producto->nombre }}" 
                             class="product-hero-image">
                    </div>
                </div>
            </div>
            <div class="col-lg-6 order-1 order-lg-2">
                <div class="product-info">
                    <div class="product-category">{{ $producto->categoria->nombre ?? 'Música' }}</div>
                    <h1 class="product-title">{{ $producto->nombre }}</h1>
                    <div class="product-price">${{ number_format($producto->precio, 2) }}</div>
                    <p class="product-description">{{ $producto->descripcion }}</p>
                    
                    <div class="product-actions">
                        <form action="{{ route('carrito.agregar') }}" method="POST" class="add-to-cart-form">
                            @csrf
                            <input type="hidden" name="id" value="{{ $producto->id }}">
                            
                            <div class="quantity-control">
                                <button type="button" class="qty-btn qty-minus">−</button>
                                <input type="number" name="cantidad" value="1" min="1" max="99" class="qty-input">
                                <button type="button" class="qty-btn qty-plus">+</button>
                            </div>
                            
                            <button type="submit" class="btn-primary-apple">
                                <span>Agregar al carrito</span>
                            </button>
                        </form>
                        
                        <button class="btn-secondary-apple">
                            <span>♡ Favoritos</span>
                        </button>
                    </div>
                    
                    <div class="product-features">
                        <div class="feature-item">
                            <div class="feature-icon">📦</div>
                            <div class="feature-text">Envío gratuito</div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">🔄</div>
                            <div class="feature-text">Devolución fácil</div>
                        </div>
                        <div class="feature-item">
                            <div class="feature-icon">🛡️</div>
                            <div class="feature-text">Garantía incluida</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 🎭 MUSEUM SECTION --}}
<section class="museum-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Ficha del Museo</h2>
            <p class="section-subtitle">Descubre la historia detrás de la música</p>
        </div>
        
        <div class="museum-grid">
            <div class="museum-card">
                <div class="card-icon">📜</div>
                <h3>Historia del Álbum</h3>
                <p>{{ $producto->fichaMusical->historia_album ?? 'Este álbum representa un hito en la historia de la música rock, marcando una época dorada del género con su sonido innovador y letras profundas.' }}</p>
                @if($producto->fichaMusical && $producto->fichaMusical->ventas_millones)
                <div class="stat">{{ $producto->fichaMusical->ventas_millones }}M copias vendidas</div>
                @endif
            </div>
            
            <div class="museum-card">
                <div class="card-icon">🎧</div>
                <h3>Proceso de Grabación</h3>
                <p>{{ $producto->fichaMusical->proceso_grabacion ?? 'Grabado en estudios de primera clase con la mejor tecnología disponible, este álbum captura la esencia pura del rock.' }}</p>
                <div class="details">
                    <div class="detail-item">
                        <strong>Productor:</strong> {{ $producto->fichaMusical->productor ?? 'Productor legendario' }}
                    </div>
                    <div class="detail-item">
                        <strong>Estudio:</strong> {{ $producto->fichaMusical->estudio_grabacion ?? 'Abbey Road Studios' }}
                    </div>
                </div>
            </div>
            
            <div class="museum-card full-width">
                <div class="card-icon">🌍</div>
                <h3>Impacto Cultural</h3>
                <p>{{ $producto->fichaMusical->impacto_cultural ?? 'Este álbum revolucionó la industria musical y dejó una huella imborrable en la cultura popular, influenciando a generaciones de músicos.' }}</p>
                @if($producto->fichaMusical && $producto->fichaMusical->curiosidades)
                <div class="curiosities">
                    <h4>Curiosidades</h4>
                    <p>{{ $producto->fichaMusical->curiosidades }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- 🎵 SONGS SECTION --}}
<section class="songs-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Canciones y Acordes</h2>
            <p class="section-subtitle">Explora cada track en detalle</p>
        </div>
        
        <div class="songs-grid">
            @if($producto->cancions && $producto->cancions->count() > 0)
                @foreach($producto->cancions as $cancion)
                <div class="song-card">
                    <div class="song-header">
                        <div class="track-number">{{ $cancion->track_number }}</div>
                        <div class="song-title">{{ $cancion->titulo }}</div>
                        <div class="song-duration">{{ $cancion->duracion ?? '3:45' }}</div>
                    </div>
                    
                    @if($cancion->analisis_musical)
                    <div class="song-analysis">
                        <h4>🎸 Análisis Musical</h4>
                        <p>{{ $cancion->analisis_musical }}</p>
                    </div>
                    @endif
                    
                    @if($cancion->letra_original)
                    <div class="song-lyrics">
                        <h4>🇬🇧 Letra Original</h4>
                        <p>{{ Str::limit($cancion->letra_original, 150) }}</p>
                    </div>
                    @endif
                    
                    @if($cancion->vocabulario_clave)
                    <div class="vocabulary">
                        @foreach($cancion->vocabulario_clave as $palabra)
                        <span class="vocab-tag">{{ $palabra }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach
            @else
                <div class="song-card">
                    <div class="song-header">
                        <div class="track-number">1</div>
                        <div class="song-title">Canción Principal</div>
                        <div class="song-duration">3:45</div>
                    </div>
                    <div class="song-analysis">
                        <h4>🎸 Análisis Musical</h4>
                        <p>Una composición clásica con acordes en Do Mayor y ritmo 4/4, perfecta para guitarristas intermedios.</p>
                    </div>
                    <div class="vocabulary">
                        <span class="vocab-tag">rock</span>
                        <span class="vocab-tag">classic</span>
                        <span class="vocab-tag">guitar</span>
                    </div>
                </div>
                
                <div class="song-card">
                    <div class="song-header">
                        <div class="track-number">2</div>
                        <div class="song-title">Balada Emocional</div>
                        <div class="song-duration">4:12</div>
                    </div>
                    <div class="song-analysis">
                        <h4>🎸 Análisis Musical</h4>
                        <p>Balada emocional en La menor con progresiones complejas y solos de guitarra expresivos.</p>
                    </div>
                    <div class="vocabulary">
                        <span class="vocab-tag">ballad</span>
                        <span class="vocab-tag">emotional</span>
                        <span class="vocab-tag">solo</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- 🎸 RELATED PRODUCTS --}}
<section class="related-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">También te puede interesar</h2>
            <p class="section-subtitle">Descubre más música increíble</p>
        </div>
        
        <div class="products-carousel">
            @if(isset($relacionados) && count($relacionados) > 0)
                @foreach($relacionados as $relacionado)
                <div class="product-card">
                    <div class="product-image">
                        <img src="{{ $relacionado->imagen ? asset('uploads/productos/' . $relacionado->imagen) : 'https://dummyimage.com/300x300/f5f5f7/1d1d1f.png?text=🎵' }}" 
                             alt="{{ $relacionado->nombre }}">
                        <div class="product-overlay">
                            <a href="{{ route('web.show', $relacionado->id) }}" class="view-btn">Ver</a>
                        </div>
                    </div>
                    <div class="product-details">
                        <h3>{{ Str::limit($relacionado->nombre, 30) }}</h3>
                        <div class="price">${{ number_format($relacionado->precio, 2) }}</div>
                        <button class="add-btn">Agregar</button>
                    </div>
                </div>
                @endforeach
            @else
                <div class="product-card">
                    <div class="product-image">
                        <img src="https://dummyimage.com/300x300/f5f5f7/1d1d1f.png?text=🎸" alt="Rock Clásico">
                        <div class="product-overlay">
                            <a href="#" class="view-btn">Ver</a>
                        </div>
                    </div>
                    <div class="product-details">
                        <h3>Rock Clásico Vol. 1</h3>
                        <div class="price">$25.99</div>
                        <button class="add-btn">Agregar</button>
                    </div>
                </div>
                
                <div class="product-card">
                    <div class="product-image">
                        <img src="https://dummyimage.com/300x300/f5f5f7/1d1d1f.png?text=🎵" alt="Greatest Hits">
                        <div class="product-overlay">
                            <a href="#" class="view-btn">Ver</a>
                        </div>
                    </div>
                    <div class="product-details">
                        <h3>Greatest Hits</h3>
                        <div class="price">$29.99</div>
                        <button class="add-btn">Agregar</button>
                    </div>
                </div>
                
                <div class="product-card">
                    <div class="product-image">
                        <img src="https://dummyimage.com/300x300/f5f5f7/1d1d1f.png?text=🎤" alt="Live Concert">
                        <div class="product-overlay">
                            <a href="#" class="view-btn">Ver</a>
                        </div>
                    </div>
                    <div class="product-details">
                        <h3>Live Concert</h3>
                        <div class="price">$35.99</div>
                        <button class="add-btn">Agregar</button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- 🛒 FLOATING CART --}}
<div class="floating-cart">
    <button class="cart-button">
        <span class="cart-icon">🛒</span>
        <span class="cart-count">0</span>
    </button>
</div>

@endsection

@push('styles')
<style>
/* 🍎 JONY IVE DESIGN SYSTEM */
:root {
    --apple-blue: #007AFF;
    --apple-gray: #8E8E93;
    --apple-light-gray: #F2F2F7;
    --apple-dark: #1D1D1F;
    --apple-white: #FFFFFF;
    --apple-radius: 12px;
    --apple-shadow: 0 4px 20px rgba(0,0,0,0.1);
    --apple-transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'SF Pro Display', sans-serif;
    background: var(--apple-white);
    color: var(--apple-dark);
    line-height: 1.6;
}

/* HERO SECTION */
.hero-product {
    padding: 80px 0;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
}

.product-showcase {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 600px;
}

.product-image-container {
    position: relative;
    width: 500px;
    height: 500px;
    border-radius: var(--apple-radius);
    overflow: hidden;
    box-shadow: var(--apple-shadow);
    background: var(--apple-white);
}

.product-hero-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--apple-transition);
}

.product-info {
    padding: 60px;
}

.product-category {
    font-size: 14px;
    color: var(--apple-gray);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 16px;
}

.product-title {
    font-size: 48px;
    font-weight: 600;
    line-height: 1.1;
    margin-bottom: 24px;
    color: var(--apple-dark);
}

.product-price {
    font-size: 32px;
    font-weight: 300;
    color: var(--apple-blue);
    margin-bottom: 24px;
}

.product-description {
    font-size: 18px;
    color: var(--apple-gray);
    margin-bottom: 40px;
    line-height: 1.5;
}

.product-actions {
    margin-bottom: 40px;
}

.add-to-cart-form {
    display: flex;
    gap: 16px;
    margin-bottom: 16px;
    align-items: center;
}

.quantity-control {
    display: flex;
    align-items: center;
    background: var(--apple-light-gray);
    border-radius: var(--apple-radius);
    padding: 4px;
}

.qty-btn {
    width: 40px;
    height: 40px;
    border: none;
    background: transparent;
    font-size: 20px;
    cursor: pointer;
    border-radius: 8px;
    transition: var(--apple-transition);
}

.qty-btn:hover {
    background: rgba(0,0,0,0.1);
}

.qty-input {
    width: 60px;
    text-align: center;
    border: none;
    background: transparent;
    font-size: 16px;
    font-weight: 500;
}

.btn-primary-apple {
    background: var(--apple-blue);
    color: var(--apple-white);
    border: none;
    padding: 16px 32px;
    border-radius: var(--apple-radius);
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--apple-transition);
    flex: 1;
}

.btn-primary-apple:hover {
    background: #0056CC;
    transform: translateY(-2px);
}

.btn-secondary-apple {
    background: transparent;
    color: var(--apple-blue);
    border: 2px solid var(--apple-blue);
    padding: 14px 32px;
    border-radius: var(--apple-radius);
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: var(--apple-transition);
    width: 100%;
}

.btn-secondary-apple:hover {
    background: var(--apple-blue);
    color: var(--apple-white);
}

.product-features {
    display: flex;
    gap: 32px;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 8px;
}

.feature-icon {
    font-size: 20px;
}

.feature-text {
    font-size: 14px;
    color: var(--apple-gray);
}

/* SECTIONS */
.museum-section, .songs-section, .related-section {
    padding: 100px 0;
}

.museum-section {
    background: var(--apple-light-gray);
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

.museum-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px;
    max-width: 1200px;
    margin: 0 auto;
}

.museum-card {
    background: var(--apple-white);
    padding: 40px;
    border-radius: var(--apple-radius);
    box-shadow: var(--apple-shadow);
    transition: var(--apple-transition);
}

.museum-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 40px rgba(0,0,0,0.15);
}

.museum-card.full-width {
    grid-column: 1 / -1;
}

.card-icon {
    font-size: 32px;
    margin-bottom: 24px;
}

.museum-card h3 {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 16px;
    color: var(--apple-dark);
}

.museum-card p {
    color: var(--apple-gray);
    line-height: 1.6;
    margin-bottom: 24px;
}

.stat {
    font-size: 14px;
    color: var(--apple-blue);
    font-weight: 600;
}

.details {
    display: flex;
    gap: 32px;
}

.detail-item {
    font-size: 14px;
    color: var(--apple-gray);
}

/* SONGS SECTION */
.songs-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 32px;
    max-width: 1200px;
    margin: 0 auto;
}

.song-card {
    background: var(--apple-white);
    border-radius: var(--apple-radius);
    padding: 32px;
    box-shadow: var(--apple-shadow);
    transition: var(--apple-transition);
}

.song-card:hover {
    transform: translateY(-4px);
}

.song-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid var(--apple-light-gray);
}

.track-number {
    width: 32px;
    height: 32px;
    background: var(--apple-blue);
    color: var(--apple-white);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
}

.song-title {
    flex: 1;
    font-size: 18px;
    font-weight: 600;
    color: var(--apple-dark);
}

.song-duration {
    font-size: 14px;
    color: var(--apple-gray);
}

.song-analysis h4, .song-lyrics h4 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 12px;
    color: var(--apple-dark);
}

.song-analysis p, .song-lyrics p {
    color: var(--apple-gray);
    margin-bottom: 20px;
}

.vocabulary {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.vocab-tag {
    background: var(--apple-light-gray);
    color: var(--apple-dark);
    padding: 6px 12px;
    border-radius: 16px;
    font-size: 12px;
    font-weight: 500;
}

/* RELATED PRODUCTS */
.products-carousel {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 32px;
    max-width: 1200px;
    margin: 0 auto;
}

.product-card {
    background: var(--apple-white);
    border-radius: var(--apple-radius);
    overflow: hidden;
    box-shadow: var(--apple-shadow);
    transition: var(--apple-transition);
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.2);
}

.product-image {
    position: relative;
    height: 280px;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: var(--apple-transition);
}

.product-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: var(--apple-transition);
}

.product-card:hover .product-overlay {
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

.product-details {
    padding: 24px;
}

.product-details h3 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--apple-dark);
}

.price {
    font-size: 16px;
    color: var(--apple-blue);
    font-weight: 500;
    margin-bottom: 16px;
}

.add-btn {
    background: var(--apple-blue);
    color: var(--apple-white);
    border: none;
    padding: 12px 24px;
    border-radius: var(--apple-radius);
    font-weight: 500;
    cursor: pointer;
    transition: var(--apple-transition);
    width: 100%;
}

.add-btn:hover {
    background: #0056CC;
}

/* FLOATING CART */
.floating-cart {
    position: fixed;
    bottom: 32px;
    right: 32px;
    z-index: 1000;
}

.cart-button {
    width: 64px;
    height: 64px;
    background: var(--apple-blue);
    border: none;
    border-radius: 50%;
    box-shadow: var(--apple-shadow);
    cursor: pointer;
    transition: var(--apple-transition);
    position: relative;
}

.cart-button:hover {
    transform: scale(1.1);
    box-shadow: 0 8px 30px rgba(0,122,255,0.4);
}

.cart-icon {
    font-size: 24px;
    color: var(--apple-white);
}

.cart-count {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #FF3B30;
    color: var(--apple-white);
    font-size: 12px;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 12px;
    min-width: 20px;
    text-align: center;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .hero-product .row {
        flex-direction: column;
    }
    
    .product-info {
        padding: 32px 16px;
    }
    
    .product-title {
        font-size: 32px;
    }
    
    .museum-grid {
        grid-template-columns: 1fr;
        gap: 24px;
    }
    
    .songs-grid {
        grid-template-columns: 1fr;
    }
    
    .products-carousel {
        grid-template-columns: 1fr;
    }
    
    .product-features {
        flex-direction: column;
        gap: 16px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Quantity controls
    const minusBtn = document.querySelector('.qty-minus');
    const plusBtn = document.querySelector('.qty-plus');
    const qtyInput = document.querySelector('.qty-input');
    
    if (minusBtn && qtyInput) {
        minusBtn.addEventListener('click', function() {
            const current = parseInt(qtyInput.value) || 1;
            if (current > 1) {
                qtyInput.value = current - 1;
            }
        });
    }
    
    if (plusBtn && qtyInput) {
        plusBtn.addEventListener('click', function() {
            const current = parseInt(qtyInput.value) || 1;
            if (current < 99) {
                qtyInput.value = current + 1;
            }
        });
    }
    
    // Add to cart form
    const cartForm = document.querySelector('.add-to-cart-form');
    if (cartForm) {
        cartForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(cartForm);
            const submitBtn = cartForm.querySelector('.btn-primary-apple');
            
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span>Agregando...</span>';
            }
            
            fetch(cartForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (response.ok) {
                    showNotification('✅ Producto agregado al carrito', 'success');
                } else {
                    showNotification('❌ Error al agregar al carrito', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('❌ Error al agregar al carrito', 'error');
            })
            .finally(() => {
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<span>Agregar al carrito</span>';
                }
            });
        });
    }
    
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
});
</script>
@endpush