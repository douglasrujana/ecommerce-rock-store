@extends('plantilla.app')
@section('contenido')
<div class="app-content">
    <!--begin::Container-->
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Productos</h3>
            </div> <!-- /.card-header -->
            <div class="card-body">
                <div>
                    <!-- Campo de busqueda -->
                    <form action="{{ route('productos.index') }}" method="get">
                        <div class="input-group">
                            <input class="form-control" name="texto" type="text" value="{{ $texto }}"
                                placeholder="Ingrese texto a buscar">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="submit">
                                    <i class="fas fa-search"></i>Buscar
                                </button>
                                <!-- drrc: Permiso -->
                                @can('producto-create')
                                <a class="btn btn-primary" href="{{route('productos.create')}}">
                                    <i class="bi bi-plus-circle"></i> Nuevo Producto
                                </a>
                                @endcan
                            </div>
                        </div>
                    </form>
                </div>
                <!-- drrc: Mostrar el div solo cuna exista un valor de session  -->
                @if(Session::has('mensaje'))
                <div class="alert alert-info alert-dismissible fade show mt-2">
                    {{ Session::get('mensaje') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close">
                    </button>
                </div>
                @endif
                <div class="table-responsive mt-3">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 150px">Opciones</th>
                                <th style="width: 20px">ID</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Imágen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($registros) <= 0) <tr>
                                <td colspan="5">
                                    <p>No hay registros que coincidan con la busqueda</p>
                                </td>
                                </tr>
                                @else
                                @foreach ($registros as $reg )
                                <tr class="align-middle">
                                    <!-- drrc: Método para actulizar el registro -->
                                    <td>
                                        <!-- drrc: permiso -->
                                        @can('producto-edit')
                                        <a class="btn btn-info btn-sm" href="{{ route('productos.edit', $reg->id)}}">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        @endcan
                                        &nbsp
                                        <!-- drrc: Permiso -->
                                        @can('producto-delete')
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modal-eliminar-{{$reg->id}}">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                        @endcan
                                    </td>
                                    <td>{{ $reg->id }}</td>
                                    <td>{{ $reg->codigo }}</td>
                                    <td>{{ $reg->nombre }}</td>
                                    <td>{{ $reg->precio }}</td>
                                    <td>
                                        @if($reg->imagen)
                                        <img src="{{ asset('uploads/productos/' . $reg->imagen) }}"
                                            alt="{{ $reg->nombre }}"
                                            style="max-width: 80px; height: auto;" class="img-thumbnail">
                                        @else
                                        <span class="text-muted">Sin imagen</span>
                                        @endif
                                    </td>
                                </tr>
                                <!-- drrc: Inclusión de los modales -->
                                @can('producto-delete')
                                    @include('producto.delete')
                                @endcan
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div> <!-- /.card-body -->
            <div class="card-footer clearfix">
                <!-- Valor devuelto por el controlador -->
                <!-- Preservar parámetros de busquedas -->
                {{$registros->appends(["texto"=>$texto])}}
            </div>
        </div> <!-- /.card -->
    </div> <!-- /.card-header -->
    <!--end::Container-->
    @endsection
    @push('scripts')
    <script>
        document.getElementById('mnuAlmacen').classList.add('menu-open');
        document.getElementById('itemProducto').classList.add('active');
    </script>
    @endpush
