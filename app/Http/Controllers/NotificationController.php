<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;
use Auth;

class NotificationController extends Controller
{
    //get all notifications for exact user
    public function get(){
        $notification = Auth::user()->unreadNotifications;
        return $notification;
    }

    //read notification
    public function read(Request $request){
        Auth::user()->unreadNotifications()->find($request->id)->markAsRead();
        return 'success';
    }

    //read all notifications
    public function readAll(Request $request){
        Auth::user()->unreadNotifications->markAsRead();
        return 'success';
    }


}
