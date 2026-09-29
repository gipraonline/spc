<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class AppraisalGoal extends Model
{
    protected $connection = 'spc_hr';

    protected $guarded = [];

    public $timestamps = false;

    public function appraisal()
    {
        return $this->belongsTo(Appraisal::class);
    }
}
