<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class WfhRequest extends Model
{
    use \App\Models\Concerns\Auditable;

    protected string $auditModule = 'wfh';

    protected string $auditEntity = 'Work-from-home request';

    public function auditSubject(): ?string
    {
        $emp = $this->employee_id ? \App\Models\Hr\Employee::with('user')->find($this->employee_id) : null;

        return $emp?->user?->name;
    }

    protected $connection = 'spc_hr';

    protected $guarded = [];

    public $timestamps = false;

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
