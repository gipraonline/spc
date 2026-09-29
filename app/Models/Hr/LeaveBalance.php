<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class LeaveBalance extends Model
{
    protected $connection = 'spc_hr';

    protected $guarded = [];

    public $timestamps = false;

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function getRemainingAttribute(): float
    {
        return (float) $this->opening_balance + (float) $this->accrued + (float) $this->carried_forward - (float) $this->used;
    }
}
