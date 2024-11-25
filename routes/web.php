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

#web:
Route::get('/verificar-pago', [PagoController::class, 'showVerificarPago'])->name('verificar-pago');
Route::post('/verificar-pago', [PagoController::class, 'verificarPago'])->name('verificar-pago.submit');
