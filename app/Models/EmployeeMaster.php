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