<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class IncentiveRule extends Model
{
    protected $connection = 'hr_spc';

    protected $guarded = [];
    public $timestamps = false;

    public function payouts()
    {
        return $this->hasMany(IncentivePayout::class);
    }
}
