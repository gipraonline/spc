<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $connection = 'hr_spc';

    protected $table = 'attendance';
    protected $guarded = [];
    public $timestamps = true;

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function regularizations()
    {
        return $this->hasMany(AttendanceRegularization::class);
    }
}
