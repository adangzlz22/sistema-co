<?php

namespace App\Mail;

use App\Models\Meeting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MeetingAfterStartNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($meeting)
    {
        $this->meeting = $meeting;
    }

    public function build()
    {
        return $this->subject('Notificación de Reunión')->view('mail.meeting_after',['meeting'=>$this->meeting]);
    }
}
