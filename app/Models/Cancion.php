<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cancion extends Model
{
    protected $fillable = [
        'titulo', 'producto_id', 'letra_original', 'letra_traducida',
        'nivel_ingles', 'vocabulario_clave', 'analisis_musical', 
        'duracion', 'track_number'
    ];

    protected $casts = [
        'vocabulario_clave' => 'array'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    /**
     * 🎓 SCOPE: Filtrar por nivel de inglés
     */
    public function scopeNivel($query, $nivel)
    {
        return $query->where('nivel_ingles', $nivel);
    }
}