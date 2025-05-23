<?php

namespace Database\Seeders;

use App\Models\Utility;
use Database\Seeders\EclaimSeeder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('module:migrate LandingPage');
        Artisan::call('module:seed LandingPage');
        
        // if (\Request::route()->getName() != 'LaravelUpdater::database') {
            $this->call(UsersTableSeeder::class);
            $this->call(NotificationSeeder::class);
            $this->call(AiTemplateSeeder::class);
            $this->call(EclaimSeeder::class);
            $this->call(JobWordCountSeeder::class);
            $this->call(LeaveTypeSeeder::class);
        // }else {
        //     Utility::languagecreate();
        // }
    }
}
