<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    protected $table = 'hoteles';

    protected $fillable = ['nombre_hotel', 'direccion', 'estrellas', 'ciudad_id'];

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_id');
    }

    public function habitaciones()
    {
        return $this->hasMany(Habitacion::class, 'hotel_id');
    }
}