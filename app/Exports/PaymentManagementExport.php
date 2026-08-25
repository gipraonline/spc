<?php

namespace App\Exports;

use App\Models\SalesOrder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PaymentManagementExport extends BaseExport implements FromQuery, WithColumnWidths, WithHeadings, WithMapping, WithStyles
{
    protected $paymentMode;

    protected $status;

    protected $fromDate;

    protected $toDate;

    public function __construct(
        $paymentMode = null,
        $status = null,
        $fromDate = null,
        $toDate = null
    ) {
        $this->paymentMode = $paymentMode;
        $this->status = $status;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    public function query()
    {
        $query = SalesOrder::query();

        // Payment mode filter
        if (! empty($this->paymentMode)) {
            $query->where(
                'c_mode_of_payment',
                $this->paymentMode
            );
        }

        // Payment status filter
        if (! empty($this->status)) {
            $query->where(
                'payment_status',
                $this->status
            );
        }

        // From date
        if (! empty($this->fromDate)) {
            $query->whereDate(
                'd_date',
                '>=',
                $this->fromDate
            );
        }

        // To date
        if (! empty($this->toDate)) {
            $query->whereDate(
                'd_date',
                '<=',
                $this->toDate
            );
        }

        return $query->orderByDesc('n_sl_no');
    }

    public function headings(): array
    {
        return [
            'Sl No',
            'Order No',
            'Date',
            'Customer',
            'Amount',
            'Payment Mode',
            'Status',
        ];
    }

    public function map($order): array
    {
        static $slNo = 0;

        return [
            ++$slNo,
            $order->c_order_no,
            $order->d_date?->format('d-m-Y'),
            $order->c_customer_name,
            $order->n_net_sales_amount,
            $order->c_mode_of_payment,
            ucfirst($order->payment_status ?? 'pending'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();

        /*
        |--------------------------------------------------------------------------
        | Common formatting from BaseExport
        |--------------------------------------------------------------------------
        */

        $this->applyCommonStyles(
            $sheet,
            "A1:G{$lastRow}",
            'A1:G1'
        );

        /*
        |--------------------------------------------------------------------------
        | Sl No alignment
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle("A2:A{$lastRow}")
            ->getAlignment()
            ->setHorizontal('center');

        /*
        |--------------------------------------------------------------------------
        | Date alignment
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle("C2:C{$lastRow}")
            ->getAlignment()
            ->setHorizontal('center');

        /*
        |--------------------------------------------------------------------------
        | Amount formatting
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle("E2:E{$lastRow}")
            ->getNumberFormat()
            ->setFormatCode('₹#,##0.00');

        /*
        |--------------------------------------------------------------------------
        | Status alignment
        |--------------------------------------------------------------------------
        */

        $sheet->getStyle("G2:G{$lastRow}")
            ->getAlignment()
            ->setHorizontal('center');

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 8,   // Sl No
            'B' => 18,  // Order No
            'C' => 15,  // Date
            'D' => 30,  // Customer
            'E' => 18,  // Amount
            'F' => 20,  // Payment Mode
            'G' => 18,  // Status
        ];
    }
}
