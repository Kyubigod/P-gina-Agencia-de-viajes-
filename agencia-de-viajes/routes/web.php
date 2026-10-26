<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PaisController;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\ReservacionesController;
use App\Http\Controllers\PaquetesTuristicosController;
use App\Http\Controllers\PaqueteDestinoController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/clientes', [ClienteController:: class, 'getCliente']);

Route::post('/clientes', [ClienteController:: class, 'createCliente']);
Route::delete('/clientes/{id}', [ClienteController:: class, 'deleteCliente']);
Route::put('/clientes/{id}', [ClienteController:: class, 'updateCliente']);


Route::get('/paises', [PaisController::class, 'getPais']);
Route::post('/paises', [PaisController::class, 'createPais']);
Route::delete('/paises/{id}', [PaisController::class, 'deletePais']);
Route::put('/paises/{id}', [PaisController::class, 'updatePais']);

Route::get('/ciudades', [CiudadController::class, 'getCiudad']);
Route::post('/ciudades', [CiudadController::class, 'createCiudad']);
Route::delete('/ciudades/{id}', [CiudadController::class, 'deleteCiudad']);
Route::put('/ciudades/{id}', [CiudadController::class, 'updateCiudad']);

Route::get('/reservaciones', [ReservacionesController:: class, 'getReservaciones']);

Route::post('/reservaciones', [ReservacionesController:: class, 'createReservaciones']);

Route::delete('/reservaciones/{id_reservacion}', [ReservacionesController::class, 'deleteReservaciones']);

Route::put('/reservaciones/{id_reservacion}', [ReservacionesController::class, 'updateReservaciones']);

Route::get('/paquetesturisticos', [PaquetesTuristicosController::class, 'getPaquetesTuristicos']);

Route::post('/paquetesturisticos', [PaquetesTuristicosController::class, 'createPaquetesTuristicos']);

Route::put('/paquetesturisticos/{id_paquete}', [PaquetesTuristicosController::class, 'updatePaquetesTuristicos']);

Route::delete('/paquetesturisticos/{id_paquete}', [PaquetesTuristicosController::class, 'deletePaquetesTuristicos']);

Route::get('/paquetedestinos', [PaqueteDestinoController::class, 'getPaqueteDestinos']);
Route::post('/paquetedestinos', [PaqueteDestinoController::class, 'createPaqueteDestino']);
Route::delete('/paquetedestinos/{id_paquete}/{id_ciudad}', [PaqueteDestinoController::class, 'deletePaqueteDestino']);