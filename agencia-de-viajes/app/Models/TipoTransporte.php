<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoTransporte extends Model
{
    protected $table = 'tipos_transporte';

    protected $fillable = ['nombre_tipo'];

    public function rutas()
    {
        return $this->hasMany(RutaTransporte::class, 'tipo_transporte_id');
    }
}