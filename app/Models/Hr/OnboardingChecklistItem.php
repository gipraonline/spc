<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class OnboardingChecklistItem extends Model
{
    protected $connection = 'hr_spc';

    protected $guarded = [];
    public $timestamps = false;

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
