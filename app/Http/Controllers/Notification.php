<?php

namespace App\Http\Controllers;

use App\Models\Notification;


class NotificationController extends Controller
{
    public function unreadCount()
    {
        $unreadCount = Notification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->count();

        return response()->json(['unread_count' => $unreadCount]);
    }
}
