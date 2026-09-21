<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $connection = 'spc_hr';

    protected $guarded = [];

    public $timestamps = true;
}
