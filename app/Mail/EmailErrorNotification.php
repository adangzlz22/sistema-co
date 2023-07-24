<?php

namespace App\Mail;

use App\Models\Meeting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailErrorNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $tema;
    public $correos;
    public $meeting;

    public function __construct($tema,$correos,$meeting)
    {
        $this->tema = $tema;
        $this->meeting = $meeting;
        $this->correos = $correos;
    }

    public function build()
    {
        return $this->subject('Notificación de error')->view('mail.email_error',[$this->tema,$this->meeting,$this->correos]);
    }
}
