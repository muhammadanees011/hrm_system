<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EmployementCheckType;

class EmployementCheckTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmployementCheckType::create([
            'title' => 'ID CHECKS',
            'created_by' => 1,
        ]);

        $employementCheckTypes = [
            [
                'title' => 'ID Checks',
                'created_by' => 1,
            ],
            [
                'title' => 'DBS Checks',
                'created_by' => 1,
            ],
            [
                'title' => 'Right TO Work',
                'created_by' => 1,
            ],
            [
                'title' => 'Employement History',
                'created_by' => 1,
            ],
            [
                'title' => 'References',
                'created_by' => 1,
            ],
            [
                'title' => 'Medical History',
                'created_by' => 1,
            ]
        ];

        EmployementCheckType::insert($employementCheckTypes);
    }
}
