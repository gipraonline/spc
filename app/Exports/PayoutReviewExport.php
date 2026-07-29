<?php

namespace App\Exports;

use App\Models\EmployeeWallet;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PayoutReviewExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
     return EmployeeWallet::with([
        'employee.designation',
        'employee.kycSubmission',
        'employee.store'
    ])
    ->where('n_balance', '>', 0)
    ->whereHas('employee', function ($query) {
        $query->where('c_status', 'Y'); // only active employees
    })
    ->whereHas('employee.kycSubmission', function ($query) {
        $query->where('status', 'approved');// only kyc verified
    })
    ->orderBy('n_balance', 'desc')
    ->get();
    }

    public function headings(): array
    {
        return [
            'Employee Name',
            'Employee Code',
            'Store Code',
            'Designation',
            'Wallet Balance',
            'Bank Account Number',
            'IFSC Code',
            'Bank Name',
            'Bank Branch',
            'KYC Status'
        ];
    }

    public function map($wallet): array
    {
        return [
            $wallet->employee->c_employee_name ?? 'N/A',
            $wallet->employee->c_employee_code ?? 'N/A',
            $wallet->employee->store->c_store_code ?? 'N/A',
            $wallet->employee->designation->c_designation ?? 'N/A',
            $wallet->n_balance,
            $wallet->employee->kycSubmission->account_number ?? 'Not Provided',
            $wallet->employee->kycSubmission->ifsc_code ?? 'N/A',
            $wallet->employee->kycSubmission->bank_name ?? 'N/A',
            $wallet->employee->kycSubmission->bank_branch ?? 'N/A',
            ucfirst($wallet->employee->kycSubmission->status ?? 'No KYC')
        ];
    }
}