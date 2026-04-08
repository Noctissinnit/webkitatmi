<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            ['name' => 'Director', 'description' => 'Direktur Perusahaan'],
            ['name' => 'Manager', 'description' => 'Manajer Departemen'],
            ['name' => 'Senior Developer', 'description' => 'Developer Senior'],
            ['name' => 'Junior Developer', 'description' => 'Developer Junior'],
            ['name' => 'QA Engineer', 'description' => 'Quality Assurance'],
            ['name' => 'UI/UX Designer', 'description' => 'Designer Desain User Interface'],
            ['name' => 'HR Officer', 'description' => 'Officer Sumber Daya Manusia'],
            ['name' => 'Finance Officer', 'description' => 'Officer Keuangan'],
        ];

        foreach ($positions as $position) {
            Position::create($position);
        }
    }
}
