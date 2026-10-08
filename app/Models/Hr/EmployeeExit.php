<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class EmployeeExit extends Model
{
    use \App\Models\Concerns\Auditable;

    protected string $auditModule = 'exits';

    protected string $auditEntity = 'Exit record';

    protected array $auditIgnore = ['snapshot'];

    public function auditSubject(): ?string
    {
        $emp = $this->employee_id ? \App\Models\Hr\Employee::with('user')->find($this->employee_id) : null;

        return $emp?->user?->name;
    }

    protected $connection = 'spc_hr';

    protected $table = 'employee_exits';

    protected $guarded = [];

    protected $casts = [
        'notice_date' => 'date',
        'last_working_day' => 'date',
        'notice_waived' => 'boolean',
        'exit_interview_done' => 'boolean',
        'clearance' => 'array',
        'snapshot' => 'array',
        'reinstated_at' => 'datetime',
    ];

    public const TYPES = [
        'resignation' => 'Resignation',
        'termination' => 'Termination',
        'retirement' => 'Retirement',
        'end_of_contract' => 'End of contract',
        'absconding' => 'Absconding',
        'death' => 'Death',
        'other' => 'Other',
    ];

    public const CLEARANCE_ITEMS = [
        'assets_returned' => 'Company assets returned (laptop, phone, ID card, etc.)',
        'accounts_revoked' => 'System / SPC / HR accounts revoked',
        'handover_done' => 'Work, leads and customers handed over',
        'documents_issued' => 'Relieving / experience letter issued',
        'dues_cleared' => 'Final settlement & dues cleared',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->exit_type] ?? ucfirst($this->exit_type);
    }
}
