<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
class OperationIncentivesExport implements FromCollection, WithHeadings
{
 protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'Date',
            'Store Code',
            'Store Name',
            'Employee Code',
            'Employee Name',
            'Designation',
            'Incentive Amount'
        ];
    }
}