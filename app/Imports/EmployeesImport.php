<?php

namespace App\Imports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class EmployeesImport implements ToModel, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    private $skipped = 0;
    private $imported = 0;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Skip empty rows
        if (empty($row['nama']) || empty($row['email'])) {
            return null;
        }

        // Check if email already exists and update or skip
        $existingEmployee = Employee::where('email', $row['email'])->first();
        if ($existingEmployee) {
            // Update existing employee
            $existingEmployee->update([
                'nama'          => $row['nama'],
                'departemen'    => $row['departemen'] ?? $existingEmployee->departemen,
                'jabatan'       => $row['jabatan'] ?? $existingEmployee->jabatan,
            ]);
            $this->skipped++;
            return null;
        }

        // Create new employee
        $this->imported++;
        return new Employee([
            'nama'          => $row['nama'],
            'email'         => $row['email'],
            'departemen'    => $row['departemen'] ?? 'Umum',
            'jabatan'       => $row['jabatan'] ?? 'Staff',
        ]);
    }

    public function getImported()
    {
        return $this->imported;
    }

    public function getSkipped()
    {
        return $this->skipped;
    }
}
