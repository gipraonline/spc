<?php

namespace App\Exports;

use App\Models\PayoutBatch;
use App\Models\EmployeeWalletTransaction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PayoutBatchExport implements FromCollection, WithHeadings
{
    protected $batch;
    protected $request;

    public function __construct(PayoutBatch $batch, Request $request)
    {
        $this->batch = $batch;
        $this->request = $request;
    }

    public function collection()
    {
        return EmployeeWalletTransaction::with([
                'employee.designation',
                'wallet'
            ])
            ->where('payout_batch_id', $this->batch->id)

            ->when($this->request->filled('employee'), function ($query) {
                $query->whereHas('employee', function ($q) {
                    $q->where('c_employee_name', 'like', '%' . $this->request->employee . '%');
                });
            })

            ->when($this->request->filled('from_date'), function ($query) {
                $query->whereDate('created_at', '>=', $this->request->from_date);
            })

            ->when($this->request->filled('to_date'), function ($query) {
                $query->whereDate('created_at', '<=', $this->request->to_date);
            })

            ->get()

            ->map(function ($txn) {
                return [
                    'Employee'    => $txn->employee->c_employee_name ?? '',
                    'Designation' => $txn->employee->designation->c_designation ?? '',
                    'Wallet ID'   => '#'.$txn->n_wallet_id,
                    'Amount'      => $txn->n_amount,
                    'Status'      => $txn->c_status ?? 'Success',
                    'Description' => $txn->c_description,
                    'Date'        => optional($txn->created_at)->timezone('Asia/Kolkata')->format('d-m-Y h:i A'),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Employee',
            'Designation',
            'Wallet ID',
            'Amount',
            'Status',
            'Description',
            'Date',
        ];
    }
}
