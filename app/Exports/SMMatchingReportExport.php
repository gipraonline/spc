<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SMMatchingReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
{
    protected Collection $reports;

    public function __construct(Collection $reports)
    {
        $this->reports = $reports;
    }

    public function collection()
    {
        return $this->reports;
    }

    public function headings(): array
    {
        return [

            'Sl No',

            'Bill Date',

            'Bill No',

            'Employee Code',

            'Employee Name',

            'Store Code',

            'Store Name',

            'Product',

            'Quantity',

            'Amount',

            'Status'

        ];
    }

    public function map($report): array
    {
        static $slNo = 0;

        return [

            ++$slNo,

            date('d-m-Y', strtotime($report->d_bill_date)),

            $report->c_bill_no,

            $report->c_employee_code,

            $report->c_employee_name,

            $report->c_store_code,

            $report->c_store_name,

            $report->c_product_name,

            $report->n_quantity,

            number_format($report->n_sold_price,2),

            $this->getStatus($report)

        ];
    }

    private function getStatus($report)
    {
        if($report->status == 'verified')
        {
            return 'SM Verified';
        }

        if($report->status == 'rejected')
        {
            return 'SM Rejected';
        }

        if($report->return_status == 'approved')
        {
            return 'Return Approved';
        }

        if($report->return_status == 'requested')
        {
            return 'Return Requested';
        }

        return '-';
    }
}