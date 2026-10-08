<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class PfAccount extends Model
{
    use \App\Models\Concerns\Auditable;

    protected string $auditModule = 'pf';

    protected string $auditEntity = 'PF account';

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
}
