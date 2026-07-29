<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeEditLog extends Model
{
    protected $table = 'employee_edit_logs';

    protected $primaryKey = 'n_log_id';

    protected $fillable = [
        'n_log_id',
        'n_employee_id',
        'n_pre_designation_id',
        'n_new_designation_id',
    ];


}
