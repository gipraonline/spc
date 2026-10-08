<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use \App\Models\Concerns\Auditable;

    protected string $auditModule = 'organization';

    protected string $auditEntity = 'Department';

    protected array $auditSubjectColumns = ['name'];

    protected $connection = 'spc_hr';

    protected $guarded = [];

    public $timestamps = true;

    public function designations()
    {
        return $this->hasMany(Designation::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
