<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LeaveType;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LeaveType::create([
            'title' => 'Holiday',
            'days' => 10
        ]);

        LeaveType::create([
            'title' => 'Maternity/Paternity',
            'days' => 10
        ]);

        LeaveType::create([
            'title' => 'Sick Leave',
            'days' => 8
        ]);

        LeaveType::create([
            'title' => 'Casual Leave',
            'days' => 8
        ]);
    }
}
