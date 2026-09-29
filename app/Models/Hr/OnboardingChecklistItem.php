<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class OnboardingChecklistItem extends Model
{
    protected $connection = 'spc_hr';

    protected $guarded = [];

    public $timestamps = false;

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
