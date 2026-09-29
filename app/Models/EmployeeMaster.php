<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class EmployeeMaster extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $table = 'employee_masters';

    protected $primaryKey = 'n_employee_id';

    protected $fillable = [
        'c_employee_code',
        'c_username',
        'c_password',
        'c_employee_name',
        'c_employee_address',
        'c_employee_email',
        'n_employee_phone',
        'n_designation_id',
        'reporting_to',
        'c_status',

        // HR-facing fields (synced into the spc_hr database by
        // EmployeeHrSyncService — see app/Services/Hr/EmployeeHrSyncService.php)
        'department_id',
        'date_of_birth',
        'gender',
        'personal_email',
        'city',
        'date_of_joining',
        'bank_name',
        'bank_account_number',
        'bank_ifsc',
        'c_hr_role',
    ];

    public function designation()
    {
        return $this->belongsTo(DesignationMaster::class, 'n_designation_id', 'n_designation_id');
    }

    public function kycSubmission()
    {
        return $this->hasOne(KycSubmission::class, 'n_employee_id', 'n_employee_id');
    }
    public function reportingManager()
    {
        return $this->belongsTo(EmployeeMaster::class, 'reporting_to');
    }

    public function subordinates()
    {
        return $this->hasMany(EmployeeMaster::class, 'reporting_to');
    }

    

}
