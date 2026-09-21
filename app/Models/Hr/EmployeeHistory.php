<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class EmployeeHistory extends Model
{
    protected $connection = 'spc_hr';

    protected $table = 'employee_history';

    protected $guarded = [];

    public $timestamps = false;

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
