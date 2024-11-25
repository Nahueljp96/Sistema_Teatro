<?php

namespace App\Http\Controllers;

use App\Models\Entrada;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use MercadoPago\Payment;

class CompraController extends Controller
{
    public function procesarCompra()
    {
        // Obtener el ID del pago desde la solicitud (enviado por Mercado Pago)
        $paymentId = request('payment_id'); 
        $payment = Payment::find_by_id($paymentId);

        // Verificar el estado del pago
        if ($payment->status === 'approved') {
            // Crear la entrada en la base de datos
            $entrada = Entrada::create([
                'usuario_id' => auth()->id(), // ID del usuario autenticado
                'obra_id' => request('obra_id'), // ID de la obra seleccionada
                'asiento' => request('asiento'), // Número del asiento seleccionado
                'email' => auth()->user()->email, // Email del usuario
            ]);

            // Generar el PDF
            $pdf = Pdf::loadView('pdf.entrada', ['entrada' => $entrada]);

            // Enviar el email con el PDF adjunto
            Mail::send('emails.confirmacion', ['entrada' => $entrada], function ($message) use ($pdf, $entrada) {
                $message->to($entrada->email)
                    ->subject('Tu entrada para ' . $entrada->obra->titulo)
                    ->attachData($pdf->output(), 'entrada.pdf');
            });

            return response()->json(['message' => 'Compra exitosa y email enviado']);
        } else {
            return response()->json(['message' => 'Pago no aprobado'], 400);
        }
    }
}
