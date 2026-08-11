<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'leads';

    protected $primaryKey = 'n_lead_id';

    protected $fillable = [
        'n_fca_id',
        'c_customer_type',
        'c_customer_name',
        'n_mobile',
        'c_email',
        'c_address',
        'n_state_id',
        'n_district_id',
        'd_visit_date',
        'c_lead_status',
        'd_expected_availability_date',
        'next_followup_date',
        'next_followup_time',
        'followup_type',
        'priority',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'd_visit_date' => 'date',
        'd_expected_availability_date' => 'date',
        'next_followup_date' => 'date',
        'next_followup_time' => 'datetime:H:i',
    ];

    public function state()
    {
        return $this->belongsTo(State::class, 'n_state_id', 'n_state_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'n_district_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Admin::class, 'created_by', 'n_role_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(Admin::class, 'updated_by', 'n_role_id');
    }

    public function fca()
    {
        return $this->hasOne(EmployeeMaster::class, 'n_employee_id', 'n_fca_id');
    }
}
