<?php

namespace App\Exports;

use App\Models\EmployeeSalesDraft;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesViewReportExport implements FromCollection, WithHeadings
{
    protected $search;
    protected $startDate;
    protected $endDate;

    public function __construct($search = null, $startDate = null, $endDate = null)
    {
        $this->search = $search;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
{
    $query = EmployeeSalesDraft::with(['employee', 'product', 'store']);

    //Search filter
    $search = trim($this->search);

    if (!empty($search)) {
        $query->whereHas('employee', function ($emp) use ($search) {
            $emp->where('c_employee_name', 'like', "%{$search}%")
                ->orWhere('c_employee_code', 'like', "%{$search}%");
        });
    }

    // DATE FILTER 
    if ($this->startDate && $this->endDate) {
        $query->whereBetween('d_date', [$this->startDate, $this->endDate]);

    } elseif ($this->startDate) {
        $query->whereDate('d_date', '>=', $this->startDate);

    } elseif ($this->endDate) {
        $query->whereDate('d_date', '<=', $this->endDate);
    }

    return $query->get()->map(function ($sale, $key) {

        return [
            'Sl No'         => $key + 1,
            'Date'          => \Carbon\Carbon::parse($sale->d_date)->format('d-m-Y'),
            'Store Code'    => $sale->store->c_store_code ?? 'N/A',
            'Employee'      => $sale->employee->c_employee_name ?? 'N/A',
            'Employee Code' => $sale->employee->c_employee_code ?? 'N/A',
            'Product'       => $sale->product->c_product_name ?? 'N/A',
            'Amount'        => $sale->n_quantity * $sale->n_sold_price,
            'Status'        => $sale->status,
        ];
    });
}
public function headings(): array
{
    return [
        'Sl No',
        'Date',
        'Store Code',
        'Employee',
        'Employee Code',
        'Product',
        'Amount',
        'Status',
    ];
}
}