<?php
namespace App\Mail;

use App\Models\Exposicion;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EnviarEmailPredefinido extends Mailable
{
    use Queueable, SerializesModels;

    public $details; // Datos que se pasarán a la vista

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function build()
    {

        $exposicion = Exposicion::find($this->details['idExposicion']);
        $this->details['exposicion'] = $exposicion;

        return $this->from('envase.etif2025@gmail.com', $exposicion['nombre'])
                    ->subject($this->details['title'])
                    ->markdown('emails.templatePredefinido') // El archivo Blade del correo
                    ->with('details', $this->details); // Pasar la variable $details a la vista

    }
}
