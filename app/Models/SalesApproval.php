<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesApproval extends Model
{
    use HasFactory;

    protected $table = 'sales_approvals';

    protected $fillable = [
        'sales_order_id',
        'status',
        'remarks',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Relationship with Sales Order
     */
    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id', 'n_sl_no');
    }

    /**
     * Relationship with Admin
     */
    public function approvedBy()
    {
        return $this->belongsTo(Admin::class, 'approved_by', 'n_role_id');
    }
}
