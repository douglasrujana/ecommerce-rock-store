<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FichaMusical extends Model
{
    protected $fillable = [
        'producto_id', 'banda_id', 'historia_album', 'proceso_grabacion',
        'productor', 'estudio_grabacion', 'impacto_cultural', 'premios',
        'ventas_millones', 'curiosidades', 'equipos_usados', 'es_premium'
    ];

    protected $casts = [
        'premios' => 'array',
        'equipos_usados' => 'array',
        'es_premium' => 'boolean'
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function banda()
    {
        return $this->belongsTo(Banda::class);
    }

    /**
     * 🏛️ SCOPE: Solo contenido premium
     */
    public function scopePremium($query)
    {
        return $query->where('es_premium', true);
    }
}