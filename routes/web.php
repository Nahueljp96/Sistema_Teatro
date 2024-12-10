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
use App\Http\Controllers\WebControllerCursos;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\MercadoPagoController;
use App\Models\Obra;


#web:
Route::get('/verificar-pago', [PagoController::class, 'showVerificarPago'])->name('verificar-pago');
Route::post('/verificar-pago', [PagoController::class, 'verificarPago'])->name('verificar-pago.submit');
Route::view('/', 'inicio')->name('inicio');
Route::view('/nosotros', 'nosotros')->name('nosotros');
Route::view('/cursos', 'cursos')->name('cursos');



Route::get('/', [WebController::class, 'inicio']);
Route::get('/cursos', [WebControllerCursos::class, 'cursos']);
Route::get('/obras', [WebController::class, 'obras']);
Route::post('/comprar-entrada', [WebController::class, 'comprarEntrada']);
Route::post('/procesar-compra', [CompraController::class, 'procesarCompra'])->name('procesar-compra');
Route::get('/comprar-entradas/{obra_id}', [CompraController::class, 'show'])->name('comprar-entradas');
Route::get('/obras', function () {
    $obras = Obra::all(); // Obtiene todas las obras desde la base de datos
    return view('obras', compact('obras'));
})->name('obras');


Route::post('/create-preference', [MercadoPagoController::class, 'createPaymentPreference']);
Route::post('/guardar-entrada', [MercadoPagoController::class, 'guardarEntrada']);
Route::get('/mercadopago/success', [MercadoPagoController::class, 'handlePaymentSuccess'])->name('mercadopago.success');
Route::get('/mercadopago/failed', [MercadoPagoController::class, 'handlePaymentFailed'])->name('mercadopago.failed');
Route::get('/prueba', function(){ $backUrls = [
    'success' => route('mercadopago.success'),
    'failure' => route('mercadopago.failed')
    
];dd($backUrls);});
#test
Route::get('/mercadopago/success', [MercadoPagoController::class, 'handlePaymentSuccess'])->name('mercadopago.success');
Route::get('/mercadopago/failed', [MercadoPagoController::class, 'handlePaymentFailure'])->name('mercadopago.failed');









