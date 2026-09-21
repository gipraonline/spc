<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $connection = 'hr_spc';

    protected $guarded = [];
    public $timestamps = true;
}
