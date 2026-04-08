<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Technology', 'description' => 'Departemen Teknologi dan Pengembangan'],
            ['name' => 'Human Resources', 'description' => 'Departemen Sumber Daya Manusia'],
            ['name' => 'Finance', 'description' => 'Departemen Keuangan'],
            ['name' => 'Marketing', 'description' => 'Departemen Pemasaran'],
            ['name' => 'Operations', 'description' => 'Departemen Operasional'],
            ['name' => 'Quality Assurance', 'description' => 'Departemen Jaminan Kualitas'],
            ['name' => 'Design', 'description' => 'Departemen Desain'],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
