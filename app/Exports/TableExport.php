<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Generic, styled Excel export used by the list / summary exports.
 *
 * $textColumns  - column letters kept as text (mobile numbers, codes, coordinates)
 * $moneyColumns - column letters formatted as #,##0.00
 */
class TableExport extends BaseExport implements FromArray, ShouldAutoSize, WithColumnFormatting, WithHeadings, WithStyles
{
    public function __construct(
        protected array $headings,
        protected array $rows,
        protected array $textColumns = [],
        protected array $moneyColumns = []
    ) {}

    public function array(): array
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function columnFormats(): array
    {
        $formats = [];

        foreach ($this->textColumns as $col) {
            $formats[$col] = NumberFormat::FORMAT_TEXT;
        }

        foreach ($this->moneyColumns as $col) {
            $formats[$col] = '#,##0.00';
        }

        return $formats;
    }

    public function styles(Worksheet $sheet)
    {
        $lastCol = Coordinate::stringFromColumnIndex(max(1, count($this->headings)));
        $lastRow = max(1, $sheet->getHighestRow());

        $this->applyCommonStyles($sheet, "A1:{$lastCol}{$lastRow}", "A1:{$lastCol}1");
        $sheet->freezePane('A2');

        return [];
    }
}
