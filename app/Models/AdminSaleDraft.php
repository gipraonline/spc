<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminSaleDraft extends Model
{
    protected $table = 'admin_sale_drafts';

    protected $fillable = [
        'n_batch_id',
        'd_date',
        'c_store_code',
        'c_billno',
        'c_item_code',
        'n_selling_price',
        'n_buying_rate',
        'n_quantity',
        'c_status',
        'c_validation_message',
    ];
}
