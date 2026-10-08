<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssociateLeaveRequest extends Model
{
    use \App\Models\Concerns\Auditable;

    protected string $auditModule = 'leave';

    protected string $auditEntity = 'Associate leave request';

    public function auditSubject(): ?string
    {
        return EmployeeMaster::where('n_employee_id', $this->n_employee_id)->value('c_employee_name');
    }

    protected $table = 'associate_leave_requests';

    public const TYPES = ['Casual', 'Sick', 'Unpaid', 'Other'];

    protected $fillable = [
        'n_employee_id', 'leave_type', 'start_date', 'end_date', 'days', 'reason',
        'status', 'decided_by', 'decided_by_name', 'decided_at', 'decision_remark',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'decided_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeMaster::class, 'n_employee_id', 'n_employee_id')->withTrashed();
    }
}
