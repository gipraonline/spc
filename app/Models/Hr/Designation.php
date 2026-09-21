<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $connection = 'spc_hr';

    protected $guarded = [];

    public $timestamps = true;

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
