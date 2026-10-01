<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = [];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(Admin::class, 'user_id', 'n_role_id');
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Fire-and-forget helper for sales/order/other events — call this from
     * wherever the underlying event actually happens (order approved,
     * dispatched, a new lead assigned, etc.), e.g.:
     *
     *   \App\Models\Notification::send(
     *       $admin->n_role_id,
     *       'order',
     *       "Order #{$order->n_sl_no} was approved",
     *       route('admin.salesorders.show', $order->n_sl_no)
     *   );
     *
     * $userId is the admins.n_role_id of the person who should see it (the
     * same value Auth::id() returns), not an employee id.
     */
    public static function send(int $userId, string $category, string $message, ?string $link = null, ?string $title = null): void
    {
        static::create([
            'user_id' => $userId,
            'category' => $category,
            'title' => $title,
            'message' => $message,
            'link' => $link,
        ]);
    }
}
