<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MensajeRecibido extends Mailable
{
    use Queueable, SerializesModels;

    public $subject='Mensaje Recibido';
    public $mensaje;

    public function __construct($mensaje)
    {
        $this->mensaje= $mensaje;
    }


    public function build(){
        return $this->view('emails.mensaje-recibido');
    }
}
