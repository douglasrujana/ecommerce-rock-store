@extends('plantilla.app')
@section('contenido')
<div class="app-content">
    <!--begin::Container-->
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Dashboard</h3>
            </div> <!-- /.card-header -->
            <!-- Body -->
            <div class="card-body">
                <!-- Mostrar mensaje de bienvenida si existe en la sesión -->
                @if(session('mensaje'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('mensaje') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <!-- función: Verificar el estado de la cuenta  -->
                @if(auth()->user()->activo)
                <p class="text-success">
                    <i class="bi bi-check-circle-fill"></i>
                    Su cuenta está activa.
                </p>
                @else
                <p class="text-warning">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    Su cuenta está desactivada.
                </p>
                @endif
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
        document.getElementById('mnuDashboard').classList.add('active');
    </script>
    @endpush
