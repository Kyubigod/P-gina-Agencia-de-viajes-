<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = ['reservacion_id', 'metodo_pago', 'monto', 'fecha_pago'];

    protected $casts = [
        'fecha_pago' => 'date',
        'monto' => 'decimal:2',
    ];

    public function reservacion()
    {
        return $this->belongsTo(Reservaciones::class, 'reservacion_id', 'id_reservacion');
    }
}