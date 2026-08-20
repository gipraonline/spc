<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

abstract class BaseExport
{
    protected function applyCommonStyles(
        Worksheet $sheet,
        string $range,
        string $headerRange
    ): void {
        // Header formatting
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
            ],

            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'center',
            ],

            'fill' => [
                'fillType' => 'solid',
                'color' => [
                    'rgb' => 'D9EAF7',
                ],
            ],

            'borders' => [
                'allBorders' => [
                    'borderStyle' => 'thin',
                    'color' => [
                        'rgb' => '808080',
                    ],
                ],
            ],
        ]);

        // Table borders
        $sheet->getStyle($range)
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle('thin');

        // Vertical alignment
        $sheet->getStyle($range)
            ->getAlignment()
            ->setVertical('center');

        // Header height
        $sheet->getRowDimension(1)
            ->setRowHeight(25);
    }
}
