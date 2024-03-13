<?php

namespace Database\Seeders;

use App\Models\JobWordCount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobWordCountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        JobWordCount::create([
            'title' => 'Cover Letter',
            'input_id' => 'coverLetter',
            'limit' => 300,
            'created_by' => 1,
        ]);
    }
}
