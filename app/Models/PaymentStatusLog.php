<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentStatusLog extends Model
{
    protected $fillable = [
        'sales_order_n_sl_no',
        'old_status',
        'new_status',
        'changed_by',
        'remarks',
    ];

    public function salesOrder()
    {
        return $this->belongsTo(
            SalesOrder::class,
            'sales_order_n_sl_no',
            'n_sl_no'
        );
    }
}
