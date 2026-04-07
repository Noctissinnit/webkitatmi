<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeesExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Employee::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'Email',
            'Departemen',
            'Jabatan',
            'Dibuat',
            'Diupdate',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->nama,
            $row->email,
            $row->departemen,
            $row->jabatan,
            $row->created_at,
            $row->updated_at,
        ];
    }
}
