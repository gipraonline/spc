<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerMaster extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'n_customer_id';

    protected $fillable = [

        'c_customer_code',
        'c_customer_name',
        'n_mobile',
        'n_whatsapp',
        'c_email',
        'c_address',
        'n_state_id',
        'n_district_id',
        'c_pincode',
        'c_status',
        'created_by',
    ];

    public function state()
{
    return $this->belongsTo(
        State::class,
        'n_state_id',
        'n_state_id'
    );
}

public function district()
{
    return $this->belongsTo(
        District::class,
        'n_district_id',
        'id'
    );
}
public static function generateCustomerCode()
{
    $lastCustomer = self::orderByDesc('n_customer_id')->first();

    if (!$lastCustomer || !$lastCustomer->c_customer_code) {
        return 'CUS0001';
    }

    $lastNumber = (int) str_replace('CUS', '', $lastCustomer->c_customer_code);

    return 'CUS' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
}
}