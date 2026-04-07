<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeeTemplateExport implements FromArray, WithHeadings, WithStyles
{
    /**
     * Return array of template data
     */
    public function array(): array
    {
        return [
            ['John Doe', 'john@example.com', 'IT', 'Developer'],
            ['Jane Smith', 'jane@example.com', 'HR', 'Manager'],
        ];
    }

    /**
     * Define headings
     */
    public function headings(): array
    {
        return [
            'Nama',
            'Email',
            'Departemen',
            'Jabatan',
        ];
    }

    /**
     * Apply styles
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Header row styling
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '4F46E5']],
                'alignment' => ['horizontal' => 'center', 'vertical' => 'center'],
            ],
        ];
    }
}
