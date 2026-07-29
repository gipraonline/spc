<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IncentiveSummaryExport implements FromCollection, WithHeadings
{
    protected $summary;

    public function __construct($summary)
    {
        $this->summary = $summary;
    }

 public function collection()
{
    return collect($this->summary)->map(function ($employee) {
        return [
            'Designation'      => $employee['designation'],
            'Employee Code'    => $employee['employee_code'],
            'Employee Name'    => $employee['employee_name'],
            'Store Code'       => $employee['store_code'],
            'Store Name'       => $employee['store_name'],
            'No. of Records'   => $employee['record_count'],
            'No. of Days'      => $employee['days_count'],
            'Total Incentive'  => round($employee['total_incentive'], 2),
        ];
    });
}

    public function headings(): array
    {
        return [
            'Designation',
            'Employee Code',
            'Employee Name',
            'Store Code',
            'Store Name',
            'No. of Records',
            'No. of Days',
            'Total Incentive',
        ];
    }
}