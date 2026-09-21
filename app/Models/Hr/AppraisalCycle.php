<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class AppraisalCycle extends Model
{
    protected $connection = 'hr_spc';

    protected $guarded = [];
    public $timestamps = false;

    public function appraisals()
    {
        return $this->hasMany(Appraisal::class);
    }
}