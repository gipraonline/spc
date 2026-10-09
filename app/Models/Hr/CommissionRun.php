<?php

namespace App\Models\Hr;

use Illuminate\Database\Eloquent\Model;

class CommissionRun extends Model
{
    protected $connection = 'spc_hr';

    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'generated_at' => 'datetime',
        'submitted_at' => 'datetime',
        'coo_at' => 'datetime',
        'md_at' => 'datetime',
        'paid_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function lines()
    {
        return $this->hasMany(CommissionLine::class);
    }

    public function monthLabel(): string
    {
        return \DateTime::createFromFormat('!m', $this->month)->format('F').' '.$this->year;
    }

    /** HR -> COO -> MD -> Finance */
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
}
