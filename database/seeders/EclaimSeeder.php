<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\EclaimType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EclaimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branche = [
            'name' => 'Training and development',
            'created_by' => 1
        ];

        Branch::create($branche);

        $department = [
            'branch_id' => 1,
            'name' => 'Development',
            'created_by' => 1
        ];

        Department::create($department);

        $designation = [
            'department_id' => 1,
            'name' => 'Developer',
            'created_by' => 1
        ];

        Designation::create($designation);

        $eClaimTypes = [
            [
                'title' => 'Mileage',
                'description' => 'Monthly Mileage',
                'created_by' => 1
            ],
            [
                'title' => 'Travel Allowance',
                'description' => 'Allowance for traveling',
                'created_by' => 1
            ],
        ];

        EclaimType::insert($eClaimTypes);
    }
}
