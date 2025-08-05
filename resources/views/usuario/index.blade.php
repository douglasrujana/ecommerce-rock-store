@extends('plantilla.app')
@section('contenido')
<div class="app-content">
    <!--begin::Container-->
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Usuarios</h3>
            </div> <!-- /.card-header -->
            <div class="card-body">
                <div>
                    <!-- Campo de busqueda -->
                    <form action="{{ route('usuarios.index') }}" method="get">
                        <div class="input-group">
                            <input class="form-control" name="texto" type="text" value="{{ $texto }}"
                                placeholder="Ingrese texto a buscar">
                            <div class="input-group-append">
                                <button class="btn btn-secondary" type="submit">
                                    <i class="fas fa-search"></i>Buscar
                                </button>
                                <!-- drrc: Permiso -->
                                @can('user-create')
                                <a class="btn btn-primary" href="{{route('usuarios.create')}}">
                                    Nuevo
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
                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                        aria-label="close">
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
                                <th>E-mail</th>
                                <th>Rol</th>
                                <th>Activo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($registros) <= 0)
                                <tr>
                                <td colspan="5">
                                    <p>No hay registros que coincidan con la busqueda</p>
                                </td>
                                </tr>
                                @else
                                @foreach ($registros as $reg )
                                <tr class="align-middle">
                                    <!-- drrc: Método para actulizar el registro -->
                                    <td>
                                        <!-- drrc: permis -->
                                        @can('user-edit')
                                        <a class="btn btn-info btn-sm"
                                            href="{{ route('usuarios.edit', $reg->id)}}">
                                            <i class="bi bi-pencil-fill"></i>
                                        </a>
                                        @endcan
                                        &nbsp
                                        <!-- drrc: Permiso -->
                                        @can('user-delete')
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modal-eliminar-{{$reg->id}}">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                        @endcan
                                        <!-- drrc: Permiso -->
                                        @can('user-activate')
                                        <button class="btn {{ $reg->activo ?
                                                'btn-warning' : 'btn-success'}} btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modal-toggle-{{ $reg->id}}">
                                                <i class="bi {{ $reg->activo ?
                                                    'bi-ban' :
                                                    'bi-check-circle'}}">
                                                </i>
                                        </button>
                                        @endcan
                                    </td>
                                    <td>{{ $reg->id }}</td>
                                    <td>{{ $reg->name }}</td>
                                    <td>{{ $reg->email }}</td>
                                    <td>
                                        {{-- Usamos pluck()->first() en la relación 'roles' ya cargada para eficiencia --}}
                                        <span class="badge bg-info text-dark">{{ $reg->roles->pluck('name')->first() ?? 'Sin rol' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge {{$reg->activo ? 'bg-success' : 'bg-danger' }}">
                                            {{ $reg->activo ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                </tr>
                                <!-- drrc: Inclusión de los modales -->
                                @can('user-delete')
                                    @include('usuario.delete')
                                @endcan
                                @can('user-activate')
                                    @include('usuario.activate')
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
        document.getElementById('mnuSeguridad').classList.add('menu-open');
        document.getElementById('itemUsuario').classList.add('active');
    </script>
    @endpush
