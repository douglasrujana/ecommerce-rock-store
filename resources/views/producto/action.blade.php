@extends('plantilla.app')
@section('contenido')
<div class="app-content">
    <!--begin::Container-->
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Productos</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <!-- drrc: Envair al ruta  -->
                <form action="{{ isset($registro) ?
                                route('productos.update', $registro->id) :
                                route('productos.store') }}"
                    method="POST" enctype="multipart/form-data">
                    <!-- Para que Laraver envie el token al hacer action -->
                    @csrf
                    @if(isset($registro))
                    @method('PUT')
                    @endif
                    <div class="row">
                        <!-- CÓDIGO -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="codigo">Código</label>
                            <input class="form-control @error('codigo') is-invalid @enderror"
                                type="text" id="codigo" name="codigo"
                                value="{{ old('name', $registro->codigo ??'') }}" required>
                            @error('codigo')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- NOMBRE -->
                        <div class="col-md-6 mb-3">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input class="form-control @error('nombre') is-invalid @enderror"
                                type="text" id="nombre" name="nombre"
                                value="{{ old('nombre', $registro->nombre ??'') }}" required>
                            @error('nombre')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <!-- PRECIO -->
                        <div class="col-md-4 mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="text" class="form-control @error('precio') is-invalid @enderror"
                                id="precio" name="precio" value="{{old('precio',  $registro->precio ??'')}}" required>
                            @error('precio')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <!-- DESCRIPCIÓN -->
                        <div class="col-md-8 mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" id="descripcion"
                                rows="4">{{ old('descripcion', $registro->descripcion ?? '') }}</textarea>
                            @error('descripcion')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <!-- IMÁGEN -->
                        <div class="col-md-4 mb-3">
                            <label for="imagen" class="form-label">Imagen</label>
                            <input type="file" class="form-control @error('imagen') is-invalid @enderror"
                                    id="imagen" name="imagen" value="{{old('imagen')}}">
                            @error('imagen')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                            @if(isset($registro) && $registro->imagen)
                            <div class="mt-2">
                                <img src="{{ asset('uploads/productos/' . $registro->imagen) }}"
                                    alt="Imagen actual"
                                    style="max-width: 150px; height: auto; border-radius: 8px;">
                            </div>
                            @endif
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
