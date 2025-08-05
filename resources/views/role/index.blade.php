@extends('plantilla.app')
@section('contenido')
<div class="app-content">
    <!--begin::Container-->
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Roles</h3>
            </div> <!-- /.card-header -->
            <div class="card-body">
                <div>
                    <!-- Campo de busqueda -->
                    <form action="{{ route('roles.index') }}" method="get">
                        <div class="input-group">
                            <input class="form-control" name="texto" type="text" value="{{ $texto }}"
                                placeholder="Ingrese texto a buscar">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="submit">
                                    <i class="fas fa-search"></i>Buscar
                                </button>
                                @can('rol-create')
                                <a class="btn btn-primary" href="{{route('roles.create')}}">Nuevo</a>
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
                                <th>Nombre</th>
                                <th>Permisos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($registros) <= 0)
                                <tr>
                                <td colspan="4">
                                    <p>No hay registros que coincidan con la busqueda</p>
                                </td>
                                </tr>
                                @else
                                @foreach($registros as $reg)
                                <tr class="align-middle">
                                    <!-- Opciones -->
                                    <td>
                                        @can('rol-edit')
                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('roles.edit', $reg->id) }}">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        @endcan
                                        &nbsp
                                        @can('rol-delte')
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modal-eliminar-{{$reg->id}}">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                        @endcan
                                    </td>
                                    <td>{{ $reg->id }}</td>
                                    <td>{{ $reg->name }}</td>
                                    <td>
                                    @if($reg->permissions->isNotEmpty())
                                        @foreach($reg->permissions as $permission)
                                            <span class="badge bg-success">{{ $permission->name }}</span>
                                        @endforeach
                                    @else
                                        <span class="badge bg-secondary">Sin Permisos</span>
                                    @endif
                                    </td>
                                </tr>
                                <!-- Inclusión del modal de eliminación para roles -->
                                @can('rol-delete')
                                    @include('role.delete')
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
                {{ $registros->appends(["texto" => $texto]) }}
            </div>
        </div> <!-- /.card -->
    </div> <!-- /.card-header -->
    <!--end::Container-->
    @endsection
    @push('scripts')
    <script>
        document.getElementById('mnuSeguridad').classList.add('menu-open');
        document.getElementById('itemRole').classList.add('active');
    </script>
    @endpush
