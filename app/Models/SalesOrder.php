<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'farm_care_advisor_id',
        'c_customer_name',
        'c_customer_address',
        'c_customer_email',
        'n_customer_mobile',
        'n_state_id',
        'n_district_id',
        'c_mode_of_payment',
        'nearest_franchise_id',
        'payment_status',
        'delivery_status',
    ];

    protected $casts = [
        'd_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeMaster::class, 'farm_care_advisor_id', 'n_employee_id');

    }

    public function franchise()
    {
        return $this->belongsTo(StoreMaster::class, 'nearest_franchise_id', 'n_store_id');
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'n_order_id', 'n_sl_no');
    }
}
