<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PaquetesTuristicos;

class PaquetesTuristicosController extends Controller
{
    public function getPaquetesTuristicos() {
        $paquetesturisticos = Paquetes_Turisticos::all();
        return view('paquetesturisticos', compact('paquetesturisticos'));
    }
}