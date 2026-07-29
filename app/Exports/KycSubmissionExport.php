<?php

namespace App\Exports;

use App\Models\KycSubmission;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class KycSubmissionExport implements FromCollection, WithHeadings
{
    protected $status;
    protected $search;

    public function __construct($status = null, $search = null)
    {
        $this->status = $status;
        $this->search = $search;
    }

    public function collection(): Collection
    {
        $query = KycSubmission::with('employee');

        // Status filter
        if ($this->status && $this->status !== 'all') {
            $query->where('status', $this->status);
        }

        // Employee search filter
        if ($this->search) {
            $query->whereHas('employee', function ($q) {
                $q->where('c_employee_name', 'like', '%' . $this->search . '%')
                  ->orWhere('c_employee_code', 'like', '%' . $this->search . '%');
            });
        }

     return $query->latest()->get()->map(function ($sub) {

        return [
            'Employee Name' => filled(optional($sub->employee)->c_employee_name)
                ? optional($sub->employee)->c_employee_name
                : 'Nil',

            'Employee Code' => filled(optional($sub->employee)->c_employee_code)
                ? optional($sub->employee)->c_employee_code
                : 'Nil',

            'Bank Name' => filled($sub->bank_name)
                ? $sub->bank_name
                : 'Nil',

            'Branch' => filled($sub->bank_branch)
                ? $sub->bank_branch
                : 'Nil',

            'Account Number' => filled($sub->account_number)
                ? $sub->account_number
                : 'Nil',

            'IFSC' => filled($sub->ifsc_code)
                ? $sub->ifsc_code
                : 'Nil',

            'Status' => filled($sub->status)
                ? ucfirst($sub->status)
                : 'Nil',

            'Submitted On' => $sub->created_at
                ? $sub->created_at->format('d-m-Y')
                : 'Nil',
        ];
    });
    }

    public function headings(): array
    {
        return [
            'Employee Name',
            'Employee Code',
            'Bank Name',
            'Branch',
            'Account Number',
            'IFSC',
            'Status',
            'Submitted On',
        ];
    }
}