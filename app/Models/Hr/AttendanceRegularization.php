<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class AttendanceRegularization extends Model
{
    use \App\Models\Concerns\Auditable;

    protected string $auditModule = 'attendance';

    protected string $auditEntity = 'Attendance regularization';

    public function auditSubject(): ?string
    {
        $empId = Attendance::where('id', $this->attendance_id)->value('employee_id');
        $emp = $empId ? Employee::with('user')->find($empId) : null;

        return $emp?->user?->name;
    }

    protected $connection = 'spc_hr';

    protected $guarded = [];

    public $timestamps = false;

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
