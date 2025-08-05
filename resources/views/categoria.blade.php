@extends('app')
@section('title', 'Sistema - Categoría')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/mantenimiento.css') }}">
@endpush

@section('contenido')
    <h1 class="texto-rojo">categorias</h1>
    <p>Este es el contenido de categorias</p>
@endsection()

@push('scripts')
    <script src="{{ asset('js/mantenimiento.js') }}"></script>
@endpush

