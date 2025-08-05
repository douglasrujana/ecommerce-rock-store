<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Sitema')</title>
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    <!-- Aplicar Estílos -->
    @stack('css')
</head>
<body>
    <header>
        <h1 class="texto-rojo">Bienvenido a mi sistema</h1>
    </header>
    <!-- Incluir el menú desde un archivo parcial -->
    @include('partials.menu')
    <main>
        @yield('contenido')
    </main>
    <footer><p>2025 - Sitema</p></footer>
    <!-- Aplicar JavaScript -->
    @stack('scripts')
</body>
</html>
