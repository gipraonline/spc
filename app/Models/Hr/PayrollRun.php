<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class PayrollRun extends Model
{
    protected $connection = 'spc_hr';

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'processed_at' => 'datetime',
        'paid_at' => 'datetime',
        'submitted_at' => 'datetime',
        'coo_at' => 'datetime',
        'md_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    /** Where the run is in the approval chain: HR -> COO -> MD -> Finance. */
    public function stageLabel(): string
    {
        return match ($this->approval_stage) {
            'pending_coo' => 'Awaiting COO',
            'pending_md' => 'Awaiting MD',
            'pending_finance' => 'Awaiting Finance',
            'returned' => 'Returned to HR',
            'completed' => 'Paid',
            default => 'With HR',
        };
    }

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
