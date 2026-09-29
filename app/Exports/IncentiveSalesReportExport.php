<?php

namespace App\Exports;

use App\Models\District;
use App\Models\Panchayath;
use App\Models\State;

/**
 * Sales Orders export (Sales Orders list > Export).
 *
 * Kept under this name because SalesController already imports it. It receives
 * the already-filtered collection of SalesOrder models (with employee,
 * franchise and customer loaded and a current_order_status attribute).
 */
class IncentiveSalesReportExport extends TableExport
{
    public function __construct($sales)
    {
        $states = State::pluck('name', 'n_state_id');
        $districts = District::pluck('district_name', 'id');
        $panchayaths = Panchayath::pluck('panchayath_name', 'id');

        $rows = [];
        $i = 0;

        foreach ($sales as $sale) {
            $rows[] = [
                ++$i,
                $sale->c_order_no,
                $sale->d_date?->format('d-m-Y'),
                $sale->customer?->c_customer_name,
                $sale->customer?->n_mobile,
                $sale->customer?->c_address,
                $sale->employee?->c_employee_name,
                $sale->employee?->c_employee_code,
                ucfirst((string) $sale->order_type),
                $sale->franchise?->c_store_name,
                $sale->franchise?->c_store_code,
                $states[$sale->n_state_id] ?? null,
                $districts[$sale->n_district_id] ?? null,
                $panchayaths[$sale->n_panchayath_id] ?? null,
                $sale->latitude,
                $sale->longitude,
                $sale->c_mode_of_payment,
                ucfirst($sale->payment_status ?? 'pending'),
                ucfirst($sale->current_order_status ?? $sale->c_order_status ?? 'pending'),
                $sale->c_transaction_id,
                (float) $sale->n_total_sales_amount,
                (float) $sale->n_total_discount + (float) $sale->n_product_discount_total,
                (float) $sale->n_total_gst,
                (float) $sale->n_net_sales_amount,
            ];
        }

        parent::__construct(
            [
                'Sl No', 'Order No', 'Order Date', 'Customer', 'Mobile', 'Address',
                'Farm Care Advisor', 'FCA Code', 'Order Type',
                'Franchise', 'Franchise Code', 'State', 'District', 'Panchayath',
                'Latitude', 'Longitude', 'Payment Mode', 'Payment Status', 'Order Status',
                'Transaction Id', 'Sales Total', 'Discount', 'GST', 'Net Amount',
            ],
            $rows,
            ['E', 'O', 'P', 'T'],
            ['U', 'V', 'W', 'X']
        );
    }
}
