<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleReservacion extends Model
{
    use HasFactory;

    protected $table = 'detalles_reservacion';
    protected $primaryKey = 'id_detalle';

    protected $fillable = [
        'id_reservacion',
        'id_paquete',
        'id_habitacion',
        'id_ruta',
        'fecha_inicio',
        'fecha_fin',
        'cantidad',
        'subtotal',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];
}