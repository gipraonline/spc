<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class Appraisal extends Model
{
    use \App\Models\Concerns\Auditable;

    protected string $auditModule = 'appraisal';

    protected string $auditEntity = 'Appraisal';

    public function auditSubject(): ?string
    {
        $emp = $this->employee_id ? \App\Models\Hr\Employee::with('user')->find($this->employee_id) : null;

        return $emp?->user?->name;
    }

    protected $connection = 'spc_hr';

    protected $guarded = [];

    public $timestamps = false;

    public function cycle()
    {
        return $this->belongsTo(AppraisalCycle::class, 'appraisal_cycle_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function goals()
    {
        return $this->hasMany(AppraisalGoal::class);
    }
}
