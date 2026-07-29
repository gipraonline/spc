<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesReportExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

   public function collection()
{
    $query = DB::table('daily_store_sales as dss')
        ->join('employee_masters as e', 'dss.n_employee_id', '=', 'e.n_employee_id')
        ->join('store_masters as s', 'dss.n_store_id', '=', 's.n_store_id')
        ->join('product_masters as p', 'dss.n_product_id', '=', 'p.n_product_id')
        ->select(
            'dss.d_date as Date',
            'e.c_employee_name as Employee',
            's.c_store_name as Store',
            's.c_store_code as Store Code',
            'p.c_product_name as Product',
            'dss.c_bill_no as Bill_No',
            'dss.n_sold_price as Sold_Price',
            'dss.n_quantity as Quantity',
            DB::raw('(dss.n_sold_price * dss.n_quantity) as Total'),
            DB::raw('CASE WHEN dss.is_incentive_calculated = 1 THEN "Done" ELSE "Pending" END as Incentive_Status')
        );

    //  Filters
    if (!empty($this->filters['employee_id'])) {
        $query->where('dss.n_employee_id', $this->filters['employee_id']);
    }

    if (!empty($this->filters['store_id'])) {
        $query->where('dss.n_store_id', $this->filters['store_id']);
    }

    if (!empty($this->filters['start_date'])) {
        $query->whereDate('dss.d_date', '>=', $this->filters['start_date']);
    }

    if (!empty($this->filters['end_date'])) {
        $query->whereDate('dss.d_date', '<=', $this->filters['end_date']);
    }

    if (isset($this->filters['incentive_status']) && $this->filters['incentive_status'] !== "") {
        $query->where('dss.is_incentive_calculated', $this->filters['incentive_status']);
    }

    // ✅ Optional row export filters
    if (!empty($this->filters['date'])) {
        $query->where('dss.d_date', $this->filters['date']);
    }

    if (!empty($this->filters['bill_no'])) {
        $query->where('dss.c_bill_no', $this->filters['bill_no']);
    }

    return $query
        ->orderBy('dss.d_date', 'desc')
        ->get();
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
            'Sold Price',
            'Quantity',
            'Total Price',
            'Incentive Status'
        ];
    }
}