<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = null;
        try{
           $notifications =  Auth::user()->notifications()->orderBy('created_at', 'desc')->paginate(10);
           Auth::user()->unreadNotifications->markAsRead();
        }catch (\Exception $ex){

        }

        $view =  view('notifications._list')
            ->with(compact("notifications"))
            ->render();

        return response()->json(['html'=>$view,'has_more_pages' => $notifications->hasMorePages()]);

    }
}
