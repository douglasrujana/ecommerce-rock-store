<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    //
    protected $fillable = ['entrada_id', 'user_id', 'contenido'];

    // Un comentario pertenece a una entrada
    public function entrada()
    {
        $this->belongsTo(Entrada::class, 'entrada_id');
    }

    public function usuario()
    {
        $this->belongsTo(User::class, 'user_id');
    }
}
