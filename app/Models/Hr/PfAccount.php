<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class PfAccount extends Model
{
    protected $connection = 'spc_hr';

    protected $guarded = [];

    public $timestamps = false;

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
