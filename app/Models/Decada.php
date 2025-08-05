<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Decada extends Model
{
    protected $fillable = ['nombre', 'inicio', 'fin'];

    /**
     * 📅 RELACIÓN: Una década tiene muchos productos
     */
    public function productos()
    {
        return $this->hasMany(Producto::class);
    }
}