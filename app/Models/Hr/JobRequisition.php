<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class JobRequisition extends Model
{
    use \App\Models\Concerns\Auditable;

    protected string $auditModule = 'recruitment';

    protected string $auditEntity = 'Job requisition';

    protected array $auditSubjectColumns = ['title'];

    protected $connection = 'spc_hr';

    protected $guarded = [];

    public $timestamps = false;

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class, 'requisition_id');
    }
}
