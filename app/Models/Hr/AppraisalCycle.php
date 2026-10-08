<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class AppraisalCycle extends Model
{
    use \App\Models\Concerns\Auditable;

    protected string $auditModule = 'appraisal';

    protected string $auditEntity = 'Appraisal cycle';

    protected array $auditSubjectColumns = ['name'];

    protected $connection = 'spc_hr';

    protected $guarded = [];

    public $timestamps = false;

    public function appraisals()
    {
        return $this->hasMany(Appraisal::class);
    }
}
