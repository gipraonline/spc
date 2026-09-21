<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    protected $connection = 'hr_spc';

    protected $guarded = [];
    public $timestamps = false;
    protected $dates = ['start_date', 'end_date', 'approved_at', 'created_at'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
