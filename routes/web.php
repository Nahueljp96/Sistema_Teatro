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




use App\Http\Controllers\PagoController;
use App\Http\Controllers\WebController;
use App\Http\Controllers\WebControllerCursos;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\MercadoPagoController2;
use App\Models\Obra;
use App\Models\CursoVenta;


#web:
Route::get('/verificar-pago', [PagoController::class, 'showVerificarPago'])->name('verificar-pago');
Route::post('/verificar-pago', [PagoController::class, 'verificarPago'])->name('verificar-pago.submit');
Route::view('/', 'inicio')->name('inicio');
Route::view('/nosotros', 'nosotros')->name('nosotros');
Route::view('/cursos', 'cursos')->name('cursos');



Route::get('/', [WebController::class, 'inicio']);
Route::get('/cursos', [WebControllerCursos::class, 'cursos']);
Route::get('/obras', [WebController::class, 'obras']);

Route::get('/obras', function () {
    $obras = Obra::all(); // Obtiene todas las obras desde la base de datos
    return view('obras', compact('obras'));
})->name('obras');


// Rutas para MercadoPagoController
Route::post('/create-preference', [MercadoPagoController::class, 'createPaymentPreference']);
Route::get('/mercadopago/success', [MercadoPagoController::class, 'handlePaymentSuccess'])->name('mercadopago.success');
Route::get('/mercadopago/failed', [MercadoPagoController::class, 'handlePaymentFailed'])->name('mercadopago.failed');

// Rutas para MercadoPagoController2
Route::post('/create-preference2', [MercadoPagoController2::class, 'createPaymentPreference2']);
Route::get('/mercadopago/success2', [MercadoPagoController2::class, 'handlePaymentSuccess2'])->name('mercadopago.success2');
Route::get('/mercadopago/failed2', [MercadoPagoController2::class, 'handlePaymentFailed2'])->name('mercadopago.failed2');

#test
Route::get('/mercadopago/success', [MercadoPagoController::class, 'handlePaymentSuccess'])->name('mercadopago.success');
Route::get('/mercadopago/failed', [MercadoPagoController::class, 'handlePaymentFailure'])->name('mercadopago.failed');









