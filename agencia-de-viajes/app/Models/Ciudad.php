<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    protected $table = 'ciudades';

    protected $fillable = ['nombre_ciudad', 'pais_id'];

    public function pais()
    {
        return $this->belongsTo(Pais::class, 'pais_id');
    }

    public function hoteles()
   {
    return $this->hasMany(Hotel::class, 'ciudad_id');
   }
}