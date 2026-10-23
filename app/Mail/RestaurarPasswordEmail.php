<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RestaurarPasswordEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $details; // Datos que se pasarán a la vista

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function build()
    {
        return $this->from('envase.etif2025@gmail.com')
                    ->subject('Restauración de contraseña')
                    ->markdown('emails.restaurarPassword') // El archivo Blade del correo
                    ->with('details', $this->details); // Pasar la variable $details a la vista

    }
}
