@extends('plantilla.app')
@section('contenido')
<div class="app-content">
    <!--begin::Container-->
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Perfil del Usuario</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                @if(session('mensaje'))
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('mensaje') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <!-- drrc: Envair al ruta  -->
                <form action="{{ route('perfil.update') }}"
                    method="POST" id="formRegistroUsuario" autocomplete="off">
                    <!-- Para que Laraver envie el token al hacer action -->
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="name">Nombre</label>
                            <input class="form-control @error('name') is-invalid @enderror"
                                type="text" id="name" name="name"
                                value="{{ old('name', $registro->name??'') }}" required>
                            @error('name')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input class="form-control @error('email') is-invalid @enderror"
                                type="text" id="email" name="email"
                                value="{{ old('email', $registro->email??'') }}" required>
                            @error('email')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="password">Password</label>
                            <small class="text-muted">{{ isset($registro) ? '(Opcional: Dejar en blanco para no cambiar)' : '' }}
                            </small>
                            <input class="form-control @error('password') is-invalid @enderror"
                                type="password" id="password" name="password"
                                autocomplete="new-password" value=""
                                {{ isset($registro) ? '' : 'required' }}>
                            @error('password')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="password_confirmation">Confirmar Password</label>
                            {{-- Se añade la clase is-invalid si hay un error en 'password' para que ambos campos se marquen en rojo --}}
                            <input class="form-control @error('password') is-invalid @enderror"
                                type="password" id="password_confirmation" name="password_confirmation"
                                autocomplete="new-password" value=""
                                {{ isset($registro) ? '' : 'required' }}>
                            {{-- El mensaje de error de confirmación ya se muestra bajo el campo principal de password --}}
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button class="btn btn-secondary me-md-2" type="button"
                            onclick="window.location.href='{{route('dashboard')}}'">
                        Cancelar
                        </button>
                        <button class="btn btn-primary" type="submit">Actulizar Datos</button>
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
            <div class="card-footer clearfix">
            </div>
        </div>
        <!-- /.card -->
    </div>
    <!-- /.card-header -->
    <!--end::Container-->
    @endsection
    
    @push('scripts')
    <script>
        // Limpiar los campos de contraseña al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('password').value = '';
            document.getElementById('password_confirmation').value = '';
        });
    </script>
    @endpush
