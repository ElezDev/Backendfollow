<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrainerNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $trainer;
    public $apprentice;

    public function __construct($trainer, $apprentice)
    {
        $this->trainer = $trainer;
        $this->apprentice = $apprentice;
    }

    public function build()
    {
        return $this->view('mail.assignedtraine')
                    ->subject('Nuevo aprendiz asignado')
                    ->with([
                        'trainerName' => $this->trainer->name,
                        'apprenticeName' => "{$this->apprentice->name} {$this->apprentice->last_name}",
                    ]);
    }
}
