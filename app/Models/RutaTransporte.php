<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RutaTransporte extends Model
{
    protected $table = 'rutas_transporte';

    protected $fillable = [
        'tipo_transporte_id', 'ciudad_origen_id', 'ciudad_destino_id',
        'precio_trayecto', 'fecha_salida', 'duracion_horas',
    ];

    protected $casts = [
        'fecha_salida' => 'datetime',
    ];

    public function tipo()
    {
        return $this->belongsTo(TipoTransporte::class, 'tipo_transporte_id');
    }

    public function origen()
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_origen_id');
    }

    public function destino()
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_destino_id');
    }
}