<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use MercadoPago\Payment;
use App\Models\Obra;
class CompraController extends Controller

{

    public function show($obra_id)

        {
            // Buscar la obra por su ID
            $obra = Obra::findOrFail($obra_id);

            // Retornar una vista donde el usuario pueda completar su compra
            return view('comprar-entradas', compact('obra'));
        }



    public function procesarCompra()
    {
        $paymentId = request('payment_id'); 
        $payment = Payment::find_by_id($paymentId);
    
        if ($payment->status === 'approved') {
            $cantidad = request('cantidad'); // Cantidad de entradas solicitadas
            $obraId = request('obra_id');
    
            for ($i = 0; $i < $cantidad; $i++) {
                Entrada::create([
                    'usuario_id' => auth()->id(),
                    'obra_id' => $obraId,
                    'asiento' => null, // Si necesitas un asiento específico, ajusta la lógica aquí
                    'email' => auth()->user()->email,
                ]);
            }
    
            $obra = Obra::find($obraId);
            $obra->asientos_disponibles -= $cantidad; // Reduce los asientos disponibles
            $obra->save();
    
            $pdf = Pdf::loadView('pdf.entrada', ['obra' => $obra, 'cantidad' => $cantidad]);
    
            Mail::send('emails.confirmacion', ['obra' => $obra, 'cantidad' => $cantidad], function ($message) use ($pdf) {
                $message->to(auth()->user()->email)
                    ->subject('Tus entradas')
                    ->attachData($pdf->output(), 'entradas.pdf');
            });
    
            return response()->json(['message' => 'Compra exitosa y email enviado']);
        } else {
            return response()->json(['message' => 'Pago no aprobado'], 400);
        }
    }
}
