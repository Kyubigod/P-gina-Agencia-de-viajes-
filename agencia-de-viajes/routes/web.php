<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PaisController;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\ReservacionesController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/clientes', [ClienteController:: class, 'getCliente']);

Route::post('/clientes', [ClienteController:: class, 'createCliente']);

Route::delete('/clientes/{id}', [ClienteController:: class, 'deleteCliente']);

Route::put('/clientes/{id}', [ClienteController:: class, 'updateCliente']);

Route::get('/reservaciones', [ReservacionesController:: class, 'getReservaciones']);

Route::get('/ciudades', [CiudadController::class, 'getCiudad']);
Route::post('/ciudades', [CiudadController::class, 'createCiudad']);
Route::delete('/ciudades/{id}', [CiudadController::class, 'deleteCiudad']);
Route::put('/ciudades/{id}', [CiudadController::class, 'updateCiudad']);
Route::get('/reservaciones', [ReservacionesController:: class, 'getReservaciones']);

Route::post('/reservaciones', [ReservacionesController:: class, 'createReservaciones']);

Route::delete('/reservaciones/{id_reservacion}', [ReservacionesController:: class, 'deleteReservaciones']);

Route::put('/reservaciones/{id_reservacion}', [ReservacionesController:: class, 'updateReservaciones']);
g