<?php

namespace App\Services;

use App\Models\Hr\Notification as HrNotification;
use App\Models\Hr\User as HrUser;
use App\Models\Notification as SpcNotification;

/**
 * Builds the merged "hr + sales + orders + others" feed shown in the
 * topbar bell. SPC-side items come from the notifications table (default
 * connection); HR items come from the spc_hr DB, resolved through the same
 * email-based SSO bridge the /hr module itself uses, so a user with no
 * linked HR account just sees their SPC notifications.
 */
class NotificationFeedService
{
    public function forUser($admin, int $limit = 8): array
    {
        $spc = SpcNotification::where('user_id', $admin->getKey())
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->map(fn ($n) => [
                'id' => $n->id,
                'category' => $n->category ?: 'other',
                'message' => $n->message,
                'link' => $n->link,
                'read' => (bool) $n->read_at,
                'created_at' => $n->created_at,
                'read_route' => route('notifications.read', $n),
            ]);

        $hr = collect();
        $hrUser = HrUser::findForSpcAdmin($admin);

        if ($hrUser) {
            $hr = HrNotification::where('user_id', $hrUser->id)
                ->orderByDesc('created_at')
                ->limit($limit)
                ->get()
                ->map(fn ($n) => [
                    'id' => $n->id,
                    'category' => 'hr',
                    'message' => $n->message,
                    'link' => $n->link,
                    'read' => (bool) $n->read_at,
                    'created_at' => $n->created_at,
                    'read_route' => route('hr.notifications.read', $n),
                ]);
        }

        $items = $spc->concat($hr)->sortByDesc('created_at')->take($limit)->values();

        $unreadCount = SpcNotification::where('user_id', $admin->getKey())->whereNull('read_at')->count()
            + ($hrUser ? HrNotification::where('user_id', $hrUser->id)->whereNull('read_at')->count() : 0);

        return [
            'items' => $items,
            'unreadCount' => $unreadCount,
        ];
    }
}
