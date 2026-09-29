<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    protected $connection = 'spc_hr';

    protected $guarded = [];

    public $timestamps = false;
}
