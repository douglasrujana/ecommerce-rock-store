<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banda extends Model
{
    protected $fillable = [
        'nombre', 'biografia', 'año_formacion', 'año_separacion', 
        'miembros', 'ciudad_origen', 'pais_id', 'genero_principal', 
        'influencias', 'imagen'
    ];

    protected $casts = [
        'miembros' => 'array'
    ];

    public function pais()
    {
        return $this->belongsTo(Pais::class);
    }

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }

    public function fichasMusicals()
    {
        return $this->hasMany(FichaMusical::class);
    }
}