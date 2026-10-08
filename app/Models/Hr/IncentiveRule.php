<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class IncentiveRule extends Model
{
    use \App\Models\Concerns\Auditable;

    protected string $auditModule = 'incentives';

    protected string $auditEntity = 'Incentive rule';

    protected array $auditSubjectColumns = ['name'];

    protected $connection = 'spc_hr';

    protected $guarded = [];

    public $timestamps = false;

    public function payouts()
    {
        return $this->hasMany(IncentivePayout::class);
    }
}
