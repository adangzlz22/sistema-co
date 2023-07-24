<?php

namespace App\Listeners;

use App\Helpers\HelperApp;
use App\Models\User;
use App\Notifications\MeetingAfterNotification;
use App\Notifications\MeetingCancelNotification;
use App\Notifications\MeetingCreateNotification;
use App\Notifications\MeetingStartNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class SendMeetingNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event,$type=0)
    {
        switch ($type) {
            case 1://startMeeting
                $users = User::whereHas('roles', function ($query) {
                    $query->where('name', HelperApp::$roleSuperUsuario);
                })->orWhere(function ($q) use($event) 
                {
                    $q->whereIn('entity_id',$event->meeting_type->entities->pluck('id'));
                })->get();
                Notification::send($users, new MeetingStartNotification($event,$type));
                break;
            case 2://afterStartMeeting
                $users = User::whereHas('roles', function ($query) {
                    $query->where('name', HelperApp::$roleSuperUsuario);
                })->get();
                Notification::send($users, new MeetingAfterNotification($event,$type));
                break;
            case 3://CreateMeeting
                $users = User::whereHas('roles', function ($query) {
                    $query->where('name', HelperApp::$roleSuperUsuario);
                })->orWhere(function ($q) use($event) 
                {
                    $q->whereIn('entity_id',$event->meeting_type->entities->pluck('id'));
                })->get();
                Notification::send($users, new MeetingCreateNotification($event,$type));
                break;
            case 4://CancelMeeting
                $users = User::whereHas('roles', function ($query) {
                    $query->where('name', HelperApp::$roleSuperUsuario);
                })->orWhere(function ($q) use($event) 
                {
                    $q->whereIn('entity_id',$event->meeting_type->entities->pluck('id'));
                })->get();
                Notification::send($users, new MeetingCancelNotification($event,$type));
                break;
            default:
            
        }

    }
}
