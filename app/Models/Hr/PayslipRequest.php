<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class PayslipRequest extends Model
{
    protected $connection = 'spc_hr';

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'decided_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function payslip()
    {
        return $this->belongsTo(Payslip::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
