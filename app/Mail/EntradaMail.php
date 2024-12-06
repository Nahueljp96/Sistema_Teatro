<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EntradaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $entrada;

    public function __construct($entrada)
    {
        $this->entrada = $entrada;
    }

    public function build()
    {
        return $this->view('emails.entrada')
                    ->subject('Tu entrada para la obra de teatro')
                    ->with([
                        'entrada' => $this->entrada,
                    ]);
    }
}
