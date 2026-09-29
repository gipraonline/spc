<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class AppraisalCycle extends Model
{
    protected $connection = 'spc_hr';

    protected $guarded = [];

    public $timestamps = false;

    public function appraisals()
    {
        return $this->hasMany(Appraisal::class);
    }
}
