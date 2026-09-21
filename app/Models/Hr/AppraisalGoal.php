<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class AppraisalGoal extends Model
{
    protected $connection = 'hr_spc';

    protected $guarded = [];
    public $timestamps = false;

    public function appraisal()
    {
        return $this->belongsTo(Appraisal::class);
    }
}
