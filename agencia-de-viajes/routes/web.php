<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PaisController;
use App\Http\Controllers\CiudadController;

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
