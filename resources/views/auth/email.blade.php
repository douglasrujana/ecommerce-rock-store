@extends('auth.app')
@section('titulo', 'Sistema - Login')
@section('contenido')
<div class="card card-outline card-primary">
    <div class="card-header">
        <a href="/"
            class="link-dark text-center link-offset-2 link-opacity-100 link-opacity-50-hover">
            <h1 class="mb-0"> <b>Systema:</b>v1
            </h1>
        </a>
    </div>
    <div class="card-body login-card-body">
        <p class="login-box-msg">Ingrese su email para recuperar su password</p>
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
        <form action="{{ route('password.send-link') }}" method="post">
            <!-- Directiva de seguridad, se coloca siempre despues de un form -->
            @csrf
            @if(Session::has('mensaje'))
            <div class="alert alert-info alert-dismissible fade show mt-2">
                {{Session::get('mensaje')}}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
            </div>public function sendResetLinkEmail(Request $request)
{
    $request->validate(['email' => 'required|email|exists:users,email']);

    $token = Str::random(64);

    // Insertar o actualizar el token en la tabla de restablecimiento de contraseña
    DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $request->email],
        ['token' => $token, 'created_at' => Carbon::now()]
    );

    try {
        Mail::send(
            'emails.reset-password',
            ['token' => $token],
            function ($message) use ($request) {
                $message->to($request->email)->subject('Recuperación de contraseña');
            }
        );
    } catch (\Exception $e) {
        // Retornar con error si falla el envío
        return back()->withErrors(['email' => 'Error al enviar el correo: ' . $e->getMessage()]);
    }

    return back()->with('status', 'Te hemos enviado un enlace de recuperación');
}

            @endif
            <div class="input-group mb-3">
                <div class="form-floating">
                    <!-- email: 📧 -->
                    <input id="loginEmail" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="email@example.com" required>
                    <label for="loginEmail">Email</label>
                </div>
                <div class="input-group-text"> <span class="bi bi-envelope"></span> </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <div class="d-grid gap-2">
                        <button type="submit"
                            class="btn btn-primary">Enviar enlace de recuperación</button>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!--end::Row-->
        </form>
        <div class="social-auth-links text-center mb-3 d-grid gap-2">
            <p>- OR -</p> <a href="#" class="btn btn-primary"> <i class="bi bi-facebook me-2">
                </i> Sign in using Facebook
            </a> <a href="#" class="btn btn-danger">
                <i class="bi bi-google me-2"></i> Sign in using Google+</a>
        </div>
        <!-- /.social-auth-links -->
        <p class="mb-1"> <a href="forgot-password.html">Olvidé mi clave</a> </p>
        <p class="mb-0">
            <a href="register.html" class="text-center">Registrare como nuevo usuario</a>
        </p>
    </div>
    <!-- /.login-card-body -->
</div>
@endsection
