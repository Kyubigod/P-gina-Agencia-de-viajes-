<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/clientes', [ClienteController:: class, 'getCliente']);



Route::post('/clientes', [ClienteController:: class, 'createCliente']);

Route::delete('/clientes/{id}', [ClienteController:: class, 'deleteCliente']);

Route::put('/clientes/{id}', [ClienteController:: class, 'updateCliente']);
