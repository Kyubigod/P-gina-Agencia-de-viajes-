<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservaciones extends Model
{
    protected $table = 'reservaciones';

    protected $fillable = [
        'id_cliente',
        'fecha_reservacion',
        'estado',
        'monto_total',
    ];
}