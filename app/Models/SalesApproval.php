<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesApproval extends Model
{
    use \App\Models\Concerns\Auditable;

    protected string $auditModule = 'sales_orders';

    protected string $auditEntity = 'Order approval';

    protected array $auditIgnore = ['approved_at'];

    public function auditSubject(): ?string
    {
        return SalesOrder::where('n_sl_no', $this->sales_order_id)->value('c_order_no');
    }

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
