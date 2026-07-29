<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminSaleUnnormalizedLog extends Model
{
    protected $table = 'admin_sale_unnormalized_logs';

    public $timestamps = false; // We use created_at only (manually or via useCurrent)

    protected $fillable = [
        'admin_sale_draft_id',
        'n_batch_id',
        'd_date',
        'c_store_code',
        'c_billno',
        'c_item_code',
        'n_selling_price',
        'n_quantity',
        'c_status',
        'c_validation_message',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
