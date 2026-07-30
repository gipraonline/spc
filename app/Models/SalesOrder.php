<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;

    protected $table = 'sales_orders'; // Change if your table name is different

    protected $primaryKey = 'n_sl_no';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'd_date',
        'c_bill_no',
        'n_sold_price',
        'farm_care_advisor_id',
        'c_customer_name',
        'c_customer_address',
        'c_customer_email',
        'n_customer_mobile',
        'c_state',
        'c_district',
        'c_mode_of_payment',
        'nearest_franchise',
        'payment_status',
        'delivery_status',
    ];

    protected $casts = [
        'd_date' => 'date',
    ];
}
