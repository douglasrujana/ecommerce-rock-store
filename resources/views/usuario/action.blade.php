@extends('plantilla.app')
@section('contenido')
<div class="app-content">
    <!--begin::Container-->
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Usuarios</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- drrc: Envair al ruta  -->
                <form action="{{ isset($registro) ?
                                route('usuarios.update', $registro->id) :
                                route('usuarios.store') }}"
                    method="POST" id="formRegistroUsuario">
                    <!-- Para que Laraver envie el token al hacer action -->
                    @csrf
                    @if(isset($registro))
                    @method('PUT')
                    @endif
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
                                value="{{ old('password') }}"
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
                                value="{{ old('password_confirmation') }}"
                                {{ isset($registro) ? '' : 'required' }}>
                            {{-- El mensaje de error de confirmación ya se muestra bajo el campo principal de password --}}
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select @error('activo') is-invalid @enderror" name="activo" id="activo" required>
                                <option
                                    value="1" {{ old('activo', $registro->activo ?? '1') == '1' ? 'selected' : '' }}>
                                    Activo
                                </option>
                                <option
                                    value="0" {{ old('activo', $registro->activo ?? '1') == '0' ? 'selected'  : '' }}>
                                    Inactivo
                                </option>
                            </select>
                            @error('activo')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="role" class="form-label">Rol</label>
                            @php
                                // Para edición, obtenemos el nombre del primer rol del usuario.
                                // Usamos ->first() porque el sistema parece manejar un solo rol por usuario.
                                $userRoleName = isset($registro) ? $registro->getRoleNames()->first() : '';
                            @endphp
                            <select class="form-select @error('role') is-invalid @enderror" name="role" id="role" required>
                                <option value="" disabled {{ old('role', $userRoleName) == '' ? 'selected' : '' }}>Seleccione un rol</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{-- old('role') se prioriza para repoblar el form en caso de error de validación --}}
                                        {{ old('role', $userRoleName) == $role->name ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button class="btn btn-secondary me-md-2" type="button"
                            onclick="window.location.href='{{route('usuarios.index')}}'">Cancelar</button>
                        <button class="btn btn-primary" type="submit">Guardar</button>
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
        document.getElementById('mnuSeguridad').classList.add('menu-open')
        document.getElementById('itemUsuario').classList.add('active')
    </script>
    @endpush
