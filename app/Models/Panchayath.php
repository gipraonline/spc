<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panchayath extends Model
{
    protected $table = 'panchayaths';

    protected $fillable = [
        'state_id',
        'district_id',
        'panchayath_name',
        'panchayath_code',
        'status',
    ];

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
}
