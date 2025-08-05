<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    protected $table = 'pais';
    protected $fillable = ['nombre', 'codigo', 'bandera'];

    /**
     * 🌍 RELACIÓN: Un país tiene muchos productos
     */
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}