<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
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
