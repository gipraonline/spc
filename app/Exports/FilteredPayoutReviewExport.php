<?php

namespace App\Exports;

use App\Models\EmployeeWallet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FilteredPayoutReviewExport implements FromCollection, WithHeadings
{
    protected $payoutType;
    protected $payoutDate;

    public function __construct($payoutType = null, $payoutDate = null)
    {
        $this->payoutType = $payoutType;
        $this->payoutDate = $payoutDate;
    }

   public function collection()
{
    $query = EmployeeWallet::with([
        'employee.designation',
        'employee.kycSubmission',
        'employee.store'
    ])
    ->where('n_balance', '>', 0)
    ->whereHas('employee', function ($q) {
        $q->where('c_status', 'Y'); // Active employees only
    })
    ->whereHas('employee.kycSubmission', function ($q) {
        $q->where('status', 'approved'); // KYC Approved only
    });

    // Daily Group
    if ($this->payoutType == 'daily') {
        $query->whereHas('employee', function ($q) {
            $q->whereIn('n_designation_id', [1, 2, 3, 4]);
        });
    }

    // Weekly Group
    if ($this->payoutType == 'weekly') {
        $query->whereHas('employee', function ($q) {
            $q->whereIn('n_designation_id', [5, 6, 7,8]);
        });
    }

    return $query->get()->map(function ($wallet) {
        return [
            'Employee Name' => $wallet->employee->c_employee_name ?? 'N/A',
            'Employee Code' => $wallet->employee->c_employee_code ?? 'N/A',
            'Store' => $wallet->employee->store->c_store_code ?? 'N/A',
            'Designation' => $wallet->employee->designation->c_designation ?? 'N/A',
            'Balance' => $wallet->n_balance ?? 'N/A',
            'Account Number' => optional($wallet->employee->kycSubmission)->account_number ?? 'N/A',
            'IFSC' => optional($wallet->employee->kycSubmission)->ifsc_code ?? 'N/A',
            'Bank Name' => optional($wallet->employee->kycSubmission)->bank_name ?? 'N/A',
            'Branch Name' => optional($wallet->employee->kycSubmission)->bank_branch ?? 'N/A',
             ucfirst($wallet->employee->kycSubmission->status ?? 'No KYC')
        ];
    });
}

    public function headings(): array
    {
        return [
            'Employee Name',
            'Employee Code',
            'Store',
            'Designation',
            'Balance',
            'Account Number',
            'IFSC',
            'Bank Name',
            'Bank Branch',
            'KYC Status',
        ];
    }
}