<?php

namespace App\Exports;

use App\Models\ProductMaster;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductExport implements FromCollection, WithHeadings
{
    protected $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = ProductMaster::query();

        // Search Filter
        if (!empty($this->filters['search'])) {

            $search = $this->filters['search'];

            $query->where(function ($q) use ($search) {

                $q->where('c_product_code', 'LIKE', "%{$search}%")
                  ->orWhere('c_product_name', 'LIKE', "%{$search}%");

            });
        }

        // Status Filter
        if (!empty($this->filters['status'])) {

            $query->where('c_status', $this->filters['status']);
        }

        return $query->get()->map(function ($product) {

            return [

                'Product ID'      => $product->c_product_code,
                'Product Name'    => $product->c_product_name,
                'MRP'             => $product->n_mrp,
                'Selling Price'   => $product->n_selling_price,
                'Purchase Price'  => $product->n_purchase_price,
                'Status'          => $product->c_status == 'Y'
                                        ? 'Active'
                                        : 'Inactive',
            ];
        });
    }

    public function headings(): array
    {
        return [

            'Product ID',
            'Product Name',
            'MRP',
            'Selling Price',
            'Purchase Price',
            'Status',

        ];
    }
}