<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class CommissionRate extends Model
{
    protected $connection = 'spc_hr';

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = ['effective_from' => 'date', 'created_at' => 'datetime'];
}
