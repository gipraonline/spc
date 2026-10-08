<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

/**
 * A target for one employee, one appraisal cycle and one metric.
 * Metrics: net_sales (INR), orders, new_leads, field_days.
 */
class SalesTarget extends Model
{
    use \App\Models\Concerns\Auditable;

    protected string $auditModule = 'incentives';

    protected string $auditEntity = 'Sales target';

    public function auditSubject(): ?string
    {
        $emp = $this->employee_id ? \App\Models\Hr\Employee::with('user')->find($this->employee_id) : null;

        return $emp?->user?->name;
    }

    protected $connection = 'spc_hr';

    protected $guarded = [];

    public const METRICS = [
        'net_sales' => ['label' => 'Net sales (₹)', 'short' => 'Sales', 'money' => true],
        'orders' => ['label' => 'Orders', 'short' => 'Orders', 'money' => false],
        'new_leads' => ['label' => 'New leads', 'short' => 'Leads', 'money' => false],
        'field_days' => ['label' => 'Field days', 'short' => 'Field days', 'money' => false],
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function cycle()
    {
        return $this->belongsTo(AppraisalCycle::class, 'appraisal_cycle_id');
    }
}
