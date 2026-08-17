<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panchayat extends Model
{
    protected $table = 'panchayats';

    protected $fillable = [
        'state_id',
        'district_id',
        'panchayat_name',
        'status',
    ];
}