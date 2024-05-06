<?php

namespace App\Console\Commands;

use App\Models\HolidayConfiguration;
use Illuminate\Console\Command;
use Carbon\Carbon;

class UpdateAnnualRenewDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:annual-renew-date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the annual renew date';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get all holiday configurations
        $holidayConfigurations = HolidayConfiguration::all();

        // Loop through each holiday configuration
        foreach ($holidayConfigurations as $config) {
            // Get the current date
            $currentDate = Carbon::now();

            // Get the annual renew date from the configuration
            $annualRenewDate = Carbon::parse($config->annual_renew_date);

            // Check if the current month and day have passed in the current year
            if ($currentDate->month > $annualRenewDate->month || ($currentDate->month == $annualRenewDate->month && $currentDate->day >= $annualRenewDate->day)) {
                // If yes, increment the year by 1
                $annualRenewDate->addYear();

                // Update the annual renew date in the configuration
                $config->annual_renew_date = $annualRenewDate;
                $config->save();
            }
        }

        $this->info('Annual renew dates updated successfully.');
    }
}
