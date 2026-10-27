<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaqueteHotel extends Model
{
    use HasFactory;

    protected $table = 'paquete_hoteles';
    public $incrementing = false;
    protected $primaryKey = ['id_paquete', 'id_hotel'];

    protected $fillable = [
        'id_paquete',
        'id_hotel',
    ];
}