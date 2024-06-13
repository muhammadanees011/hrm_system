<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\HolidaySetting;


class HolidaySettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HolidaySetting::create([
            'name' => 'book_same_day',
            'value' => 'yes'
        ]);
    }
}
