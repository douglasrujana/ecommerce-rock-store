<div class="modal fade" id="modal-eliminar-{{ $reg->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog">
        <div class="modal-content bg-danger">
            <form action="{{ route('usuarios.destroy', $reg->id) }}" method="post">
                @csrf
                @method('DELETE')
                <div class="modal-footer">
                    <h4 class="modal-title">Eliminar Registro</h4>
                </div>
                <div class="modal-body">
                    ¿Desea eliminar el registro: {{ $reg->name }}?
                </div>
                <div class="modal-footer justify-content-between">
                    <button class="btn btn-outline-light" type="text" data-bs-dismiss="modal">Cerrar</button>
                    <button class="btn btn-outline-light" type="submit">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>
