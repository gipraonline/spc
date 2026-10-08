<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use \App\Models\Concerns\Auditable;

    protected string $auditModule = 'support';

    protected string $auditEntity = 'Support ticket';

    protected array $auditSubjectColumns = ['subject'];

    protected $connection = 'spc_hr';

    protected $guarded = [];

    public $timestamps = false;

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function resolvedBy()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
