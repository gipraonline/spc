<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class EmployeeMaster extends Model
{
    use HasFactory;

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
        'n_store_id',
        'n_operations_poolid',
        'n_pool_id',
        'c_status',
    ];

    public function designation()
    {
        return $this->belongsTo(DesignationMaster::class, 'n_designation_id', 'n_designation_id');
    }

    public function store()
    {
        return $this->belongsTo(StoreMaster::class, 'n_store_id', 'n_store_id');
    }

    public function clusters()
    {
        return $this->hasMany(StoreCluster::class, 'n_employee_id', 'n_employee_id');
    }

    public function operationClusters()
    {
        return $this->hasMany(OperationCluster::class, 'n_employee_id', 'n_employee_id');
    }

    public function operationsLink()
    {
        return $this->hasOne(OperationCluster::class, 'n_cluster_manager_id', 'n_employee_id');
    }

    public function wallet()
    {
        return $this->hasOne(EmployeeWallet::class, 'n_employee_id', 'n_employee_id');
    }

    public function walletTransactions()
    {
        return $this->hasMany(EmployeeWalletTransaction::class, 'n_employee_id', 'n_employee_id');
    }

    public function kycSubmission()
    {
        return $this->hasOne(KycSubmission::class, 'n_employee_id', 'n_employee_id');
    }
    

}