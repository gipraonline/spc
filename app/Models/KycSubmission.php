<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KycSubmission extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'kyc_submissions';

    protected $fillable = [
        'n_employee_id',
        'bank_name',
        'bank_branch',
        'account_number',
        'ifsc_code',
        'document_path',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relationship with Employee
     */
    public function employeeMaster()
    {
        return $this->belongsTo(EmployeeMaster::class, 'n_employee_id');
    }
}