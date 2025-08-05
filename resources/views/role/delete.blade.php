<div class="modal fade" id="modal-eliminar-{{ $reg->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog">
        <div class="modal-content bg-danger">
            <form action="{{ route('roles.destroy', $reg->id) }}" method="post">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h4 class="modal-title">Eliminar Rol</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>¿Desea eliminar el rol: <strong>{{ $reg->name }}</strong>?</p>
                </div>
                <div class="modal-footer justify-content-between">
                    <button class="btn btn-outline-light" type="button" data-bs-dismiss="modal">Cerrar</button>
                    <button class="btn btn-outline-light" type="submit">Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>
