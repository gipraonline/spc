<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $table = 'operations';

    protected $primaryKey = 'n_operation_id';

    protected $guarded = [];

    public function pool()
    {
        return $this->belongsTo(PoolMaster::class, 'n_pool_id', 'n_pool_id');
    }

    public function employeeTypes()
    {
        return $this->hasMany(EmployeeType::class, 'n_operation_id', 'n_operation_id');
    }
}
