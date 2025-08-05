@extends('auth.app')
@section('titulo', 'Sistema - Registro')
@section('contenido')
<div class="card card-outline card-primary">
            <div class="card-header">
                <a href="/"
                    class="link-dark text-center link-offset-2 link-opacity-100 link-opacity-50-hover">
                    <h1 class="mb-0"> <b>Systema:</b>v1</h1>
                </a>
            </div>
            <div class="card-body login-card-body">
                <p class="login-box-msg">Registro</p>
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
                <form action="{{ route('registro.store') }}" method="post">
                <!-- Directiva de seguridad, se coloca siempre despues de un form -->
                @csrf
                <!-- Campo oculto para el estado activo requerido por el validador -->
                <input type="hidden" name="activo" value="1">
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <!-- name: 🪪 -->
                            <input id="name" name="name" type="text" class="form-control @error('name')
                            is-invalid @enderror" value="{{old('name')}}" placeholder="Inrese Nombre" required>
                            <label for="name">Nombre</label>
                        </div>
                        <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <!-- email: 📧 -->
                            <input id="loginEmail" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="email@example.com" required>
                            <label for="loginEmail">Email</label>
                        </div>
                        <div class="input-group-text"><span class="bi bi-envelope"></span></div>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <!-- Password: 🔑 -->
                            <input id="loginPassword" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
                            <label for="loginPassword">Password</label>
                        </div>
                        <div class="input-group-text"><span class="bi bi-lock-fill"></span>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="input-group mb-3">
                        <div class="form-floating">
                            <!-- Confirme su Password: 🔑 🔑 -->
                            <input id="password_confirmation" name="password_confirmation" type="password"
                            class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="" required>
                            <label for="password_confirmation">Confirme su Password</label>
                        </div>
                        <div class="input-group-text"><span class="bi bi-lock-fill"></span></div>
                        @error('password_confirmation')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    </div>
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-8 d-inline-flex align-items-center">
                            <div class="form-check">
                                <input class="form-check-input"type="checkbox"        value=""
                                    id="flexCheckDefault">
                                    <label class="form-check-label" for="flexCheckDefault">Recordar clave 🔑</label>
                            </div>
                        </div> <!-- /.col -->
                        <div class="col-4">
                            <div class="d-grid gap-2">
                                <button type="submit"
                                class="btn btn-primary">Registrar</button>
                            </div>
                        </div> <!-- /.col -->
                    </div>
                    <!--end::Row-->
                </form>
                <div class="social-auth-links text-center mb-3 d-grid gap-2">
                    <p>- OR -</p> <a href="#" class="btn btn-primary">
                    <i class="bi bi-facebook me-2"></i> Sign in using Facebook
                    </a>
                    <a href="#" class="btn btn-danger">
                    <i class="bi bi-google me-2"></i> Sign in using Google+
                    </a>
                </div> <!-- /.social-auth-links -->
                <p class="mb-1"> <a href="forgot-password.html">Olvidé mi clave</a> </p>
                <p class="mb-0"> <a href="register.html" class="text-center">
                        Registrare como nuevo usuario
                    </a> </p>
            </div> <!-- /.login-card-body -->
</div>
@endsection
