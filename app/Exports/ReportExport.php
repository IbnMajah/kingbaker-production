<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReportExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithTitle
{
    protected $data;
    protected $type;

    public function __construct($data, $type = 'products')
    {
        $this->data = $data;
        $this->type = $type;
    }

    public function array(): array
    {
        return array_map(function ($label, $value) {
            return [
                $label,
                number_format($value, 2), // Format numbers with 2 decimal places
            ];
        }, $this->data['labels']->toArray(), $this->data['data']->toArray());
    }

    public function headings(): array
    {
        return match($this->type) {
            'products' => ['Product Name', 'Revenue (GMD)'],
            'daily' => ['Date', 'Revenue (GMD)'],
            'summary' => ['Period', 'Revenue (GMD)'],
            default => ['Label', 'Value'],
        };
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F46E5'], // Indigo color
                ],
                'font' => [
                    'color' => ['rgb' => 'FFFFFF'],
                    'bold' => true,
                ],
            ],
            'A1:B1' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
            'B' => [
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
                ],
                'numberFormat' => [
                    'formatCode' => '#,##0.00',
                ],
            ],
        ];
    }

    public function title(): string
    {
        return match($this->type) {
            'products' => 'Product Revenue Report',
            'daily' => 'Daily Revenue Report',
            'summary' => 'Summary Revenue Report',
            default => 'Report',
        };
    }
} 