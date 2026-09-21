<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class SystemSetting extends Model
{
    protected $connection = 'hr_spc';

    protected $guarded = [];
    public $timestamps = false;
}
