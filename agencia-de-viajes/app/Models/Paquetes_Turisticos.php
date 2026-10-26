<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paquetes_Turisticos extends Model
{
    protected $table = 'paquetes_turisticos';
    protected $primaryKey = 'id_paquete';

    protected $fillable = [
        'nombre_paquete',
        'descripcion',
        'duracion_dias',
        'precio_total',

    ];
}
