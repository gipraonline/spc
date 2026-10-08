<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    use \App\Models\Concerns\Auditable;

    protected string $auditModule = 'organization';

    protected string $auditEntity = 'Holiday';

    protected array $auditSubjectColumns = ['name'];

    protected $connection = 'spc_hr';

    protected $guarded = [];

    public $timestamps = true;
}
