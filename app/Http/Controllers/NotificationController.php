<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Notifications\SystemNotification;

class NotificationController extends Controller
{
    public function markAsRead(Request $request)
    {
    	SystemNotification::find($id)->markAsRead();
    }

    public function markAllAsRead(Request $request)
    {
    	Auth::user()->unreadNotifications->markAsRead();
    	echo json_encode(['state'=>true]);
    }
}
