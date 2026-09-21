<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class GratuityRecord extends Model
{
    protected $connection = 'hr_spc';

    protected $guarded = [];
    public $timestamps = false;

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
