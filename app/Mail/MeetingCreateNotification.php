<?php

namespace App\Mail;

use App\Models\Meeting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MeetingCreateNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $entity;
    public $meeting;

    public function __construct($entity,$meeting)
    {
        $this->meeting = $meeting;
        $this->entity = $entity;
    }

    public function build()
    {
        return $this->subject('Notificación de Reunión')->view('mail.meeting_create',[$this->meeting,$this->entity]);
    }
}
