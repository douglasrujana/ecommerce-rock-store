@extends('plantilla.app')
@section('contenido')
<div class="app-content">
    <!--begin::Container-->
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">{{ isset($registro) ? 'Editar Rol' : 'Crear Rol' }}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="{{ isset($registro) ? route('roles.update', $registro->id) : route('roles.store') }}"
                    method="POST" id="formRegistroRol">
                    <!-- Para que Laraver envie el token al hacer action -->
                    @csrf
                    @if(isset($registro))
                    @method('PUT')
                    @endif
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="name">Nombre</label>
                            <input class="form-control @error('name') is-invalid @enderror"
                                type="text" id="name" name="name"
                                value="{{ old('name', $registro->name??'') }}" required>
                            @error('name')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">Permisos</label>
                            @error('permissions')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                            <div class="row">
                                @foreach($permissions as $permission)
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}"
                                            {{ (isset($registro) && $registro->hasPermissionTo($permission->name)) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                                            {{ ucfirst($permission->name) }}
                                        </label>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button class="btn btn-secondary me-md-2" type="button"
                            onclick="window.location.href='{{route('roles.index')}}'">Cancelar</button>
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
        document.getElementById('itemRole').classList.add('active')
    </script>
    @endpush
