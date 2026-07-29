<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IncentiveSalesReportExport implements FromCollection,WithHeadings
{
     protected $sales;

    public function __construct($sales)
    {
        $this->sales = $sales;
    }

    public function collection()
    {
        return $this->sales->map(function ($sale) {
            return [
                'Date' => \Carbon\Carbon::parse($sale->d_date)->format('d-m-Y'),
                'Employee' => $sale->employee?->c_employee_name,
                'Employee Code' => $sale->employee?->c_employee_code,
                'Store' => $sale->store?->c_store_name,
                'Product' => $sale->product?->c_product_name,
                'Quantity' => $sale->n_quantity,
                'Price' => $sale->product?->n_selling_price,
                'Purchase' => $sale->product?->n_purchase_price,
                'Total Sales' => $sale->total_sales_amount,
                'Margin' => $sale->total_margin_amount,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Date',
            'Employee',
            'Employee Code',
            'Store',
            'Product',
            'Quantity',
            'Price',
            'Purchase',
            'Total Sales',
            'Margin',
        ];
    }
}