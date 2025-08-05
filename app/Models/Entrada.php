<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Entrada extends Model
{
    // HasFactory permite crear datos de prueba fácilmente usando factories
    use HasFactory;
    //protected $hasTimestamps = true;
    //protected $table = 'entradas';
    //protected $primaryKey = 'id';

    //
    protected $fillable = [
        'titulo',
        'imagen',
        'tag',
        'contenido',
        'user_id',
    ];

    // One to Many: Muchas entradas tienen muchos coementarios
    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }

    // Many to One:  Muchas entradas pertenecen a un usuario
    public function usuario()
    {
        return $this->belongTo(User::class, 'user_id');
    }
}
