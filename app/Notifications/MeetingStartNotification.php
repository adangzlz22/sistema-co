<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class MeetingStartNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($meeting,$type=0)
    {
        $this->meeting = $meeting;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $folio =  $this->meeting->folio ?? '' ;
        $reunion =  $this->meeting->meeting_type->name ?? '' ;
        $hora =  $this->meeting->meeting_time ?? '' ;
        $parameters = [];
        
        $route =  route('meetings.subject', $this->meeting->id, false);
        $img =  asset('/images/notification/notification-bell.png');
        $parameters = [
            'title' =>"Estimado usuario, se le recuerda que se programó una reunión de $reunion para hoy a las $hora horas.",
            'img' => $img,
            'route' => $route,
        ];
                

        return $parameters;

    }
}
