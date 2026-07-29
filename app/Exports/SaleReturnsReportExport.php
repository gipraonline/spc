<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SaleReturnsReportExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = DB::table('employee_sales_drafts as esd')
            ->join('employee_masters as e', 'esd.n_employee_id', '=', 'e.n_employee_id')
            ->join('store_masters as s', 'esd.n_store_id', '=', 's.n_store_id')
            ->join('product_masters as p', 'esd.n_product_id', '=', 'p.n_product_id')
            ->leftJoin('daily_store_sales as dss', function($join) {
                $join->on('esd.c_bill_no', '=', 'dss.c_bill_no')
                     ->on('esd.n_product_id', '=', 'dss.n_product_id')
                     ->on('esd.n_employee_id', '=', 'dss.n_employee_id');
            })
            ->select(
                'esd.d_date',
                'e.c_employee_name',
                's.c_store_name',
                's.c_store_code',
                'p.c_product_name',
                'esd.c_bill_no',
                DB::raw('(esd.n_sold_price * esd.n_quantity) as total_price'),
                'esd.return_status',
                DB::raw('CASE WHEN dss.is_incentive_calculated = 1 THEN "Yes" ELSE "No" END as incentive_calculated')
            )
            ->whereNotNull('esd.return_status');

        // Apply filters
        if (!empty($this->filters['start_date'])) {
            $query->whereDate('esd.d_date', '>=', $this->filters['start_date']);
        }
        if (!empty($this->filters['end_date'])) {
            $query->whereDate('esd.d_date', '<=', $this->filters['end_date']);
        }
        if (!empty($this->filters['employee_id'])) {
            $query->where('esd.n_employee_id', $this->filters['employee_id']);
        }
        if (!empty($this->filters['store_id'])) {
            $query->where('esd.n_store_id', $this->filters['store_id']);
        }
        if (!empty($this->filters['return_status'])) {
            $query->where('esd.return_status', $this->filters['return_status']);
        }

        return $query->orderBy('esd.d_date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Date',
            'Employee',
            'Store',
            'Store Code',
            'Product',
            'Bill No',
            'Total Price',
            'Return Status',
            'Incentive Calculated'
        ];
    }
}