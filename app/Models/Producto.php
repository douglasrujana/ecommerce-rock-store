<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'precio',
        'imagen',
        'categoria_id',
        'decada_id',
        'pais_id',
        'año'
    ];

    /**
     * 🎸 RELACIÓN: Un producto pertenece a una categoría
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * 📅 RELACIÓN: Un producto pertenece a una década
     */
    public function decada()
    {
        return $this->belongsTo(Decada::class);
    }

    /**
     * 🌍 RELACIÓN: Un producto pertenece a un país
     */
    public function pais()
    {
        return $this->belongsTo(Pais::class);
    }

    /**
     * 🎭 RELACIÓN: Un producto tiene una ficha musical
     */
    public function fichaMusical()
    {
        return $this->hasOne(FichaMusical::class);
    }

    /**
     * 🎵 RELACIÓN: Un producto tiene muchas canciones
     */
    public function cancions()
    {
        return $this->hasMany(Cancion::class);
    }
}
