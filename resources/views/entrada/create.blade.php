<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sistema💻</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <header>
            <h2>Registro de Entradas</h2>
        </header>
        <section class="mt-4">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form action="{{ route('entrada.store') }}" method="post">
                @csrf
                <div class="row">
                    <div class="form-group col-12 col-md-6">
                        <label for="titulo">Título</label>
                        <input type="text" class="form-control" value="{{ old('titulo') }}" required name="titulo" placeholder="Ingresar Título">
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label for="tag">Tag</label>
                        <input type="text" class="form-control" value="{{ old('tag') }}" required name="tag" placeholder="Ingresar Tag">
                    </div>
                    <div class="form-group col-12 col-md-6">
                        <label for="contenido">Contenido</label>
                        <input type="text" class="form-control" value="{{ old('contenido') }}" required name="contenido" placeholder="Ingresar Contenido">
                    </div>
                    <div class="center mt-4">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
            @if($errors->any())
                <div class="mt-4 alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </section>
    </div>
</body>

</html>
