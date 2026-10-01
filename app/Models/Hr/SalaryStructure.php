<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class SalaryStructure extends Model
{
    protected $connection = 'spc_hr';

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'pf_applicable' => 'boolean',
        'esi_applicable' => 'boolean',
        'pt_applicable' => 'boolean',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
