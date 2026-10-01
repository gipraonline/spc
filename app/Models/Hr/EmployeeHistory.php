<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class EmployeeHistory extends Model
{
    protected $connection = 'spc_hr';

    protected $table = 'employee_history';

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'effective_date' => 'date',
        'created_at' => 'datetime',
    ];

    /** event_type => [label, pill class] used by the timeline UI */
    public const EVENTS = [
        'joined' => ['Joined', 'pill-ok'],
        'change' => ['Change', 'pill-muted'],
        'promotion' => ['Promotion / designation', 'pill-ok'],
        'transfer' => ['Transfer', 'pill-muted'],
        'status' => ['Status', 'pill-warn'],
        'notice' => ['Notice period', 'pill-warn'],
        'exit' => ['Exit', 'pill-bad'],
        'reinstatement' => ['Reinstated', 'pill-ok'],
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    /**
     * Single entry point for writing a timeline row, so every part of the
     * app (HR screens, SPC employee edit, the sync service, exits) logs in
     * the same shape.
     */
    public static function log(
        int $employeeId,
        string $eventType,
        string $field,
        ?string $old,
        ?string $new,
        ?int $changedBy = null,
        ?string $remarks = null,
        ?string $effectiveDate = null
    ): self {
        return static::create([
            'employee_id' => $employeeId,
            'event_type' => $eventType,
            'field_changed' => $field,
            'old_value' => $old,
            'new_value' => $new,
            'remarks' => $remarks,
            'effective_date' => $effectiveDate ?: now()->toDateString(),
            'changed_by' => $changedBy,
            'created_at' => now(),
        ]);
    }
}
