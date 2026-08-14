<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    use HasFactory;

    protected $table = 'sales_orders';

    protected $primaryKey = 'n_sl_no';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'd_date',
        'c_order_no',

        'farm_care_advisor_id',

        // Customer ID
        'n_customer_id',

        // Customer details
        'c_customer_name',
        'c_customer_address',
        'c_customer_email',
        'n_customer_mobile',

        'n_state_id',
        'n_district_id',

        'c_mode_of_payment',
        'c_order_status',
        'payment_image',
        'c_transaction_id',
        'nearest_franchise_id',
        'payment_status',
        'booklet_image',
        'delivery_status',

        'invoice_no',
    ];

    protected $casts = [
        'd_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(
            EmployeeMaster::class,
            'farm_care_advisor_id',
            'n_employee_id'
        );
    }

    public function customer()
    {
        return $this->hasOne(
            CustomerMaster::class,
            'n_customer_id',
            'n_customer_id'
        );
    }

    public function franchise()
    {
        return $this->belongsTo(
            StoreMaster::class,
            'nearest_franchise_id',
            'n_store_id'
        );
    }

    public function orderProducts()
    {
        return $this->hasMany(
            OrderProduct::class,
            'n_order_id',
            'n_sl_no'
        );
    }

    // public static function generateOrderNo()
    // {
    //     $lastOrder = self::orderByDesc('n_sl_no')->first();

    //     if (! $lastOrder || ! $lastOrder->c_order_no) {
    //         return 'ORD1';
    //     }

    //     $lastNumber = (int) str_replace('ORD', '', $lastOrder->c_order_no);

    //     return 'ORD'.($lastNumber + 1);
    // }
}
