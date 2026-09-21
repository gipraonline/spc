<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class PayrollRun extends Model
{
    protected $connection = 'spc_hr';

    protected $guarded = [];

    public $timestamps = false;

    public function payslips()
    {
        return $this->hasMany(Payslip::class);
    }

    public function pfContributions()
    {
        return $this->hasMany(PfContribution::class);
    }

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function monthLabel(): string
    {
        return \DateTime::createFromFormat('!m', $this->month)->format('F').' '.$this->year;
    }
}
