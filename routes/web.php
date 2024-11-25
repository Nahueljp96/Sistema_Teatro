<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


use App\Http\Controllers\PagoController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\CompraController;

#web:
Route::get('/verificar-pago', [PagoController::class, 'showVerificarPago'])->name('verificar-pago');
Route::post('/verificar-pago', [PagoController::class, 'verificarPago'])->name('verificar-pago.submit');

Route::get('/', [WebController::class, 'inicio']);
Route::get('/obras', [WebController::class, 'obras']);
Route::post('/comprar-entrada', [WebController::class, 'comprarEntrada']);
Route::post('/procesar-compra', [CompraController::class, 'procesarCompra'])->name('procesar-compra');