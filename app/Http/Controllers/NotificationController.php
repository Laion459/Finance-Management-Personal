<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Log;
use App\Events\UserNotification;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function unreadCount(Request $request)
    {
        $userId = $request->input('user_id');
        $user = User::find($userId);

        if ($user) {
            try {
                $unreadCount = $user->notifications()->whereNull('read_at')->count();
                return response()->json(['unread_count' => $unreadCount]);
            } catch (\Exception $e) {
                Log::error("Error fetching unread notification count: " . $e->getMessage());
                return response()->json(['error' => 'Error fetching notifications.'], 500);
            }
        } else {
            return response()->json(['error' => 'Notification not found'], 404); // Retorna JSON em caso de erro
        }
    }


    // App\Http\Controllers\NotificationController.php

    public function index()
    {
        if (auth()->check()) {
            $notifications = auth()->user()->notifications()->latest()->get();

            return response()->json(['notifications' => $notifications]); // Retorna a coleção de notificações diretamente
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
    public function sendNotification(Request $request)
    {
        $message = $request->input('message');

        // Store in database
        $notification = Notification::create([
            'user_id' => auth()->id(),
            'message' => $message,
        ]);

        event(new UserNotification($notification));

        Log::info('UserNotification event dispatched with message: ' . $message);
        return response()->json(['status' => 'Notification sent!']);
    }








    // Função para marcar notificações como lidas
    public function markAsRead(Request $request)
    {
        $notificationId = $request->input('notification_id');

        if (auth()->check()) {
            if ($notificationId) {
                // Marcar uma única notificação como lida
                $notification = auth()->user()->notifications()->find($notificationId);

                if ($notification) {
                    $notification->markAsRead();
                    return response()->json(['success' => true]);
                } else {
                    return response()->json(['error' => 'Notification not found'], 404);
                }
            } else {
                // Marcar todas as notificações do usuário como lidas
                auth()->user()->unreadNotifications->markAsRead();
                return response()->json(['success' => true]);
            }
        } else {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
