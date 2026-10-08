<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use \App\Models\Concerns\Auditable;

    protected string $auditModule = 'leave';

    protected string $auditEntity = 'Leave request';

    public function auditSubject(): ?string
    {
        $emp = $this->employee_id ? \App\Models\Hr\Employee::with('user')->find($this->employee_id) : null;

        return $emp?->user?->name;
    }

    protected $connection = 'spc_hr';

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
