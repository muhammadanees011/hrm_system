<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class NotificationComposer
{
    public function compose(View $view)
    {
        if (Auth::check()) {
            $user_id = Auth::id();
            $notifications = Notification::where('receiver_id', $user_id)->with('sender', 'receiver')->orderBy('created_at', 'desc')->get();
            $notifications_count = Notification::where('receiver_id', $user_id)->where('read', false)->with('sender', 'receiver')->count();
            $view->with('notifications_count', $notifications_count);
            $view->with('notifications', $notifications);
        } else {
            $view->with('notifications', collect()); 
            $view->with('notifications_count', 0);
        }
    }
}
