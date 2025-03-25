<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CursoVentaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $cursoVenta;

    public function __construct($cursoVenta)
    {
        $this->cursoVenta = $cursoVenta;
    }

    public function build()
    {
        return $this->view('emails.cursoventa')
                    ->subject('Tu comprobante de pago al curso!')
                    ->with([
                        'cursoVenta' => $this->cursoVenta,
                    ]);
    }
}
