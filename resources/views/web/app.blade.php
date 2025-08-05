<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Rock Store - Música que Inspira')</title>
    
    <!-- Apple-style favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Apple Design System CSS -->
    <style>
        /* 🍎 JONY IVE DESIGN SYSTEM - GLOBAL */
        :root {
            --apple-blue: #007AFF;
            --apple-gray: #8E8E93;
            --apple-light-gray: #F2F2F7;
            --apple-dark: #1D1D1F;
            --apple-white: #FFFFFF;
            --apple-green: #34C759;
            --apple-red: #FF3B30;
            --apple-orange: #FF9500;
            --apple-radius: 12px;
            --apple-shadow: 0 4px 20px rgba(0,0,0,0.1);
            --apple-transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            --apple-font: -apple-system, BlinkMacSystemFont, 'SF Pro Display', 'Helvetica Neue', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--apple-font);
            background: var(--apple-white);
            color: var(--apple-dark);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* NAVIGATION */
        .apple-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            transition: var(--apple-transition);
        }

        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
        }

        .nav-logo {
            font-size: 24px;
            font-weight: 600;
            color: var(--apple-dark);
            text-decoration: none;
            transition: var(--apple-transition);
        }

        .nav-logo:hover {
            color: var(--apple-blue);
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 32px;
            align-items: center;
        }

        .nav-link {
            color: var(--apple-dark);
            text-decoration: none;
            font-weight: 500;
            transition: var(--apple-transition);
            position: relative;
        }

        .nav-link:hover {
            color: var(--apple-blue);
        }

        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--apple-blue);
            border-radius: 1px;
        }

        .nav-cart {
            background: var(--apple-blue);
            color: var(--apple-white);
            padding: 8px 16px;
            border-radius: var(--apple-radius);
            text-decoration: none;
            font-weight: 500;
            transition: var(--apple-transition);
            position: relative;
        }

        .nav-cart:hover {
            background: #0056CC;
            transform: translateY(-2px);
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--apple-red);
            color: var(--apple-white);
            font-size: 12px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-top: 64px;
            min-height: calc(100vh - 64px);
        }

        /* FOOTER */
        .apple-footer {
            background: var(--apple-light-gray);
            padding: 60px 0 40px;
            margin-top: 100px;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            margin-bottom: 40px;
        }

        .footer-section h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
            color: var(--apple-dark);
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 8px;
        }

        .footer-links a {
            color: var(--apple-gray);
            text-decoration: none;
            transition: var(--apple-transition);
        }

        .footer-links a:hover {
            color: var(--apple-blue);
        }

        .footer-bottom {
            border-top: 1px solid rgba(0, 0, 0, 0.1);
            padding-top: 20px;
            text-align: center;
            color: var(--apple-gray);
            font-size: 14px;
        }

        /* UTILITIES */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .btn-apple {
            background: var(--apple-blue);
            color: var(--apple-white);
            border: none;
            padding: 12px 24px;
            border-radius: var(--apple-radius);
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--apple-transition);
            text-decoration: none;
            display: inline-block;
        }

        .btn-apple:hover {
            background: #0056CC;
            transform: translateY(-2px);
            color: var(--apple-white);
        }

        .btn-secondary {
            background: transparent;
            color: var(--apple-blue);
            border: 2px solid var(--apple-blue);
            padding: 10px 24px;
            border-radius: var(--apple-radius);
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--apple-transition);
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary:hover {
            background: var(--apple-blue);
            color: var(--apple-white);
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .nav-container {
                padding: 0 16px;
            }
            
            .nav-menu {
                gap: 16px;
            }
            
            .container {
                padding: 0 16px;
            }
            
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 32px;
            }
        }

        /* MOBILE MENU */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: var(--apple-dark);
        }

        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: block;
            }
            
            .nav-menu {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(20px);
                flex-direction: column;
                padding: 20px;
                border-top: 1px solid rgba(0, 0, 0, 0.1);
            }
            
            .nav-menu.active {
                display: flex;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="apple-nav">
        <div class="nav-container">
            <a href="{{ route('web.index') }}" class="nav-logo">🎸 Rock Store</a>
            
            <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">☰</button>
            
            <ul class="nav-menu" id="navMenu">
                <li><a href="{{ route('web.index') }}" class="nav-link {{ request()->routeIs('web.index') ? 'active' : '' }}">Inicio</a></li>
                <li><a href="{{ route('web.catalogo') }}" class="nav-link {{ request()->routeIs('web.catalogo') ? 'active' : '' }}">Catálogo</a></li>
                <li><a href="#generos" class="nav-link">Géneros</a></li>
                <li><a href="#artistas" class="nav-link">Artistas</a></li>
                <li><a href="{{ route('carrito.mostrar') }}" class="nav-cart">
                    🛒 Carrito
                    <span class="cart-badge" id="cartBadge">0</span>
                </a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @yield('contenido')
    </main>

    <!-- Footer -->
    <footer class="apple-footer">
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-section">
                    <h3>Tienda</h3>
                    <ul class="footer-links">
                        <li><a href="#">Catálogo Completo</a></li>
                        <li><a href="#">Nuevos Lanzamientos</a></li>
                        <li><a href="#">Ofertas Especiales</a></li>
                        <li><a href="#">Géneros Musicales</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Soporte</h3>
                    <ul class="footer-links">
                        <li><a href="#">Centro de Ayuda</a></li>
                        <li><a href="#">Envíos y Devoluciones</a></li>
                        <li><a href="#">Garantía</a></li>
                        <li><a href="#">Contacto</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Cuenta</h3>
                    <ul class="footer-links">
                        <li><a href="#">Mi Perfil</a></li>
                        <li><a href="#">Mis Pedidos</a></li>
                        <li><a href="#">Lista de Deseos</a></li>
                        <li><a href="#">Configuración</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Empresa</h3>
                    <ul class="footer-links">
                        <li><a href="#">Acerca de Nosotros</a></li>
                        <li><a href="#">Carreras</a></li>
                        <li><a href="#">Prensa</a></li>
                        <li><a href="#">Términos y Condiciones</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Rock Store. Todos los derechos reservados. Diseñado con principios de Jony Ive.</p>
            </div>
        </div>
    </footer>

    <!-- Global Scripts -->
    <script>
        // Mobile menu toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('navMenu');
            menu.classList.toggle('active');
        }

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('navMenu');
            const toggle = document.querySelector('.mobile-menu-toggle');
            
            if (!menu.contains(event.target) && !toggle.contains(event.target)) {
                menu.classList.remove('active');
            }
        });

        // Update cart badge
        function updateCartBadge(count) {
            const badge = document.getElementById('cartBadge');
            if (badge) {
                badge.textContent = count;
                badge.style.display = count > 0 ? 'block' : 'none';
            }
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add scroll effect to navigation
        window.addEventListener('scroll', function() {
            const nav = document.querySelector('.apple-nav');
            if (window.scrollY > 50) {
                nav.style.background = 'rgba(255, 255, 255, 0.95)';
            } else {
                nav.style.background = 'rgba(255, 255, 255, 0.8)';
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>