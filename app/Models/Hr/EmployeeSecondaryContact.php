<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class EmployeeSecondaryContact extends Model
{
    protected $connection = 'hr_spc';

    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
