<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Full list of this user's SPC notifications (sales / order / other).
     * HR notifications have their own "view all" at hr.notifications.index.
     */
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::user()->getKey())
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function read(Notification $notification)
    {
        abort_unless($notification->user_id === Auth::user()->getKey(), 403);

        if (! $notification->read_at) {
            $notification->update(['read_at' => now()]);
        }

        return $notification->link ? redirect($notification->link) : back();
    }
}
