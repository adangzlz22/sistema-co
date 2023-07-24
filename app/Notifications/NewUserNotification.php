<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class NewUserNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
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
        $img =  asset('/images/avatars/'. Auth::user()->avatar) ;
        $route =  route('users.edit', $this->user->id);
        $_user_name = Auth::user()->name;
        $user_name = $this->user->name;
        return [
            'title' =>"<strong>$_user_name</strong>, a creado un nuevo usuario: <strong>$user_name</strong>.",
            'img' => $img,
            'route' => $route,
        ];
    }
}
