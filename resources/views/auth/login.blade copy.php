<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Sistema V1 | Login Page v1</title>
    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="color-scheme" content="light dark">
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)">
    <!--end::Accessibility Meta Tags-->
    <!--begin::Primary Meta Tags-->
    <meta name="title" content="Sistema V1 | Login Page v1">
    <meta name="author" content="douglasrujana.dev">
    <meta name="description"
        content="sistema Fullstack.">
    <meta name="keywords"
        content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant">
    <!--end::Primary Meta Tags-->
    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark">
    <link rel="preload" href="../css/adminlte.css" as="style">
    <!--end::Accessibility Features-->
    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print"
        onload="this.media='all'">
    <!--end::Fonts-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
        crossorigin="anonymous">
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        crossorigin="anonymous">
    <!--end::Third Party Plugin(Bootstrap Icons)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="{{asset('css/adminlte.css')}}">
    <!--end::Required Plugin(AdminLTE)-->
</head>
<!--end::Head-->
<!--begin::Body-->

<body class="login-page bg-body-secondary">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header"> <a href="../index2.html"
                    class="link-dark text-center link-offset-2 link-opacity-100 link-opacity-50-hover">
                    <h1 class="mb-0"> <b>Systema:</b>v1
                    </h1>
                </a> </div>
            <div class="card-body login-card-body">
                <p class="login-box-msg">Ingrese sus credenciales</p>
                {{--
                    Laravel, por defecto, no usa `session('error')` para los fallos de autenticación o validación.
                    En su lugar, utiliza una variable especial `$errors` que está disponible en todas las vistas.
                    Este bloque ahora revisa si hay algún error en `$errors` y lo muestra en una lista.
                --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('login.post') }}" method="post">
                <!-- Directiva de seguridad, se coloca siempre despues de un form -->
                @csrf
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <!-- email: 📧 -->
                            <input id="loginEmail" name="loginEmail" type="email" class="form-control @error('loginEmail') is-invalid @enderror" value="{{ old('loginEmail') }}" placeholder="email@example.com" required> <label for="loginEmail">Email</label> </div>
                        <div class="input-group-text"> <span class="bi bi-envelope"></span> </div>
                    </div>
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <!-- Password: 🔑 -->
                            <input id="loginPassword" name="loginPassword" type="password" class="form-control @error('loginPassword') is-invalid @enderror" placeholder="Password" required> <label for="loginPassword">Password</label> </div>
                        <div class="input-group-text"> <span class="bi bi-lock-fill"></span> </div>
                    </div>
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-8 d-inline-flex align-items-center">
                            <div class="form-check"> <input class="form-check-input" type="checkbox" value=""
                                    id="flexCheckDefault"> <label class="form-check-label" for="flexCheckDefault">
                                    Recordar clave 🔑
                                </label> </div>
                        </div> <!-- /.col -->
                        <div class="col-4">
                            <div class="d-grid gap-2">
                                <button type="submit"
                                class="btn btn-primary">Acceder</button>
                            </div>
                        </div> <!-- /.col -->
                    </div>
                    <!--end::Row-->
                </form>
                <div class="social-auth-links text-center mb-3 d-grid gap-2">
                    <p>- OR -</p> <a href="#" class="btn btn-primary"> <i class="bi bi-facebook me-2"></i> Sign in using
                        Facebook
                    </a> <a href="#" class="btn btn-danger"> <i class="bi bi-google me-2"></i> Sign in using Google+
                    </a>
                </div> <!-- /.social-auth-links -->
                <p class="mb-1"> <a href="forgot-password.html">Olvidé mi clave</a> </p>
                <p class="mb-0"> <a href="register.html" class="text-center">
                        Registrare como nuevo usuario
                    </a> </p>
            </div> <!-- /.login-card-body -->
        </div>
        
    </div> <!-- /.login-box -->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
        crossorigin="anonymous"></script>
    <!--end::Third Party Plugin(OverlayScrollbars)-->
    <!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous">
    </script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)-->
    <!--begin::Required Plugin(Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous">
    </script>
    <!--end::Required Plugin(Bootstrap 5)-->
    <!--begin::Required Plugin(AdminLTE)-->
    <!-- drrc: Ruta java script -->
    <script src="{{asset('/js/adminlte.js')}}"></script>
    <!--end::Required Plugin(AdminLTE)-->
    <!--begin::OverlayScrollbars Configure-->
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper"
        const Default = {
            scrollbarTheme: "os-theme-light",
            scrollbarAutoHide: "leave",
            scrollbarClickScroll: true
        }
        document.addEventListener("DOMContentLoaded", function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER)
            if (
                sidebarWrapper && OverlayScrollbarsGlobal &&
                OverlayScrollbarsGlobal.OverlayScrollbars !== undefined
            ) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll
                    }
                })
            }
        })
    </script>
    <!--end::OverlayScrollbars Configure-->
    <!-- Image path runtime fix -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Find the link tag for the main AdminLTE CSS file.
            const cssLink = document.querySelector('link[href*="css/adminlte.css"]');
            if (!cssLink) {
                return; // Exit if the link isn't found
            }
            // Extract the base path from the CSS href.
            // e.g., from "../css/adminlte.css", we get "../"
            // e.g., from "./css/adminlte.css", we get "./"
            const cssHref = cssLink.getAttribute('href');
            const deploymentPath = cssHref.slice(0, cssHref.indexOf('css/adminlte.css'));
            // Find all images with absolute paths and fix them.
            document.querySelectorAll('img[src^="/assets/"]').forEach(img => {
                const originalSrc = img.getAttribute('src');
                if (originalSrc) {
                    const relativeSrc = originalSrc.slice(1); // Remove leading '/'
                    img.src = deploymentPath + relativeSrc;
                }
            });
        });
    </script>
    <!--end::Script-->
</body>
<!--end::Body-->

</html>
