<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $notifications = $user->notifications;
        $unreadNotifications = $user->unreadNotifications;

        if ($notifications->isNotEmpty()) {
            return response()->json([
                'notifications' => $notifications,
                'unread_notifications' => $unreadNotifications,
            ],200);
        }

        return response()->json([
            'message' => 'no available notifications'
        ] , 200);
    }

    public function markAsRead(Request $request, $id)
    {
        $notification = $request->user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();
            return response()->json(['message' => 'Notification marked as read'] , 200);
        }

        return response()->json(['message' => 'Notification not found'], 404);
    }
}
