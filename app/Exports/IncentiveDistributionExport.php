<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class IncentiveDistributionExport implements FromArray, WithHeadings
{
    protected $data;

    public function __construct($distribution)
    {
        $this->data = $distribution;
    }

    public function headings(): array
    {
        return [
            'Pool',
            'Code',
            'Name',
            'Designation',
            'Incentive Amount',
        ];
    }
    public function array(): array
    {
        $rows = [];

        foreach ($this->data as $poolName => $distribution) {
            if (!isset($distribution['employees'])) continue;

            foreach ($distribution['employees'] as $emp) {
                $rows[] = [
                    'Pool' => strtoupper(str_replace('_', ' ', $poolName)),
                    'Code' => $emp['code'],
                    'Name' => $emp['name'],
                    'Designation' => $emp['designation'] ?? '',
                    'Incentive Amount' => $emp['incentive'],
                ];
            }
        }

        return $rows;
    }
}
