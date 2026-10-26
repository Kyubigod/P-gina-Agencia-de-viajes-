<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PaisController;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\ReservacionesController;
use App\Http\Controllers\PaquetesTuristicosController;
use App\Http\Controllers\PaqueteDestinoController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\TipoTransporteController;
use App\Http\Controllers\RutaTransporteController;


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

Route::get('/hoteles', [HotelController::class, 'getHotel']);
Route::post('/hoteles', [HotelController::class, 'createHotel']);
Route::delete('/hoteles/{id}', [HotelController::class, 'deleteHotel']);
Route::put('/hoteles/{id}', [HotelController::class, 'updateHotel']);

Route::get('/habitaciones', [HabitacionController::class, 'getHabitacion']);
Route::post('/habitaciones', [HabitacionController::class, 'createHabitacion']);
Route::delete('/habitaciones/{id}', [HabitacionController::class, 'deleteHabitacion']);
Route::put('/habitaciones/{id}', [HabitacionController::class, 'updateHabitacion']);

Route::get('/tipos-transporte', [TipoTransporteController::class, 'getTipoTransporte']);
Route::post('/tipos-transporte', [TipoTransporteController::class, 'createTipoTransporte']);
Route::delete('/tipos-transporte/{id}', [TipoTransporteController::class, 'deleteTipoTransporte']);
Route::put('/tipos-transporte/{id}', [TipoTransporteController::class, 'updateTipoTransporte']);

Route::get('/rutas', [RutaTransporteController::class, 'getRutaTransporte']);
Route::post('/rutas', [RutaTransporteController::class, 'createRutaTransporte']);
Route::delete('/rutas/{id}', [RutaTransporteController::class, 'deleteRutaTransporte']);
Route::put('/rutas/{id}', [RutaTransporteController::class, 'updateRutaTransporte']);
