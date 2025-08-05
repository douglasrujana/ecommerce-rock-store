<!-- Archivo modal Activate -->
<div class="modal fade" id="modal-toggle-{{ $reg->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog">
        <div class="modal-content {{ $reg->activo ? 'bg-warning' : 'bg-success' }}">
            <form action="{{ route('usuarios.toggle', $reg->id) }}" method="post">
                @csrf
                @method('PATCH')
                <div class="modal-footer">
                    <h4 class="modal-title">{{$reg->activo ? 'Desactivar' : 'Activar' }} Registro</h4>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Desea {{ $reg->activo ? 'desactivar' : 'activar' }} el registro: {{$reg->name}}?
                </div>
                <div class="modal-footer justify-content-between">
                    <button class="btn btn-outline-light" type="text" data-bs-dismiss="modal">Cerrar</button>
                    <button class="btn btn-outline-light" type="submit">
                        {{ $reg->activo ? 'Desactivar' : 'Activar' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
