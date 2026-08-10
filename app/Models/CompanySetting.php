<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    protected $table = 'company_settings';

    protected $fillable = [
        'company_name',
        'address',
        'phone',
        'email',
        'website',
        'bank_name',
        'account_name',
        'account_number',
        'ifsc_code',
        'branch',
    ];
}