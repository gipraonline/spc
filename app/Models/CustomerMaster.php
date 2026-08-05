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
        'c_district',
        'c_state',
        'c_pincode',
        'c_status',
        'created_by',
    ];
}