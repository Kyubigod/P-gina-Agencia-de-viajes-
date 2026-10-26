<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paquete_Destinos extends Model
{
    use HasFactory;

    protected $table = 'paquete_destinos';
    public $incrementing = false;
    protected $primaryKey = ['id_paquete', 'id_ciudad'];

    protected $fillable = [
        'id_paquete',
        'id_ciudad',
    ];
}