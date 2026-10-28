<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservaciones extends Model
{
    use HasFactory;

    protected $table = 'reservaciones';
    protected $primaryKey = 'id_reservacion';

    protected $fillable = [
        'id_cliente',
        'fecha_reservacion',
        'estado',
        'monto_total',
    ];

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'reservacion_id', 'id_reservacion');
    }
}