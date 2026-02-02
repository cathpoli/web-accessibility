<?php

namespace App\Console;

use App\Jobs\Subscriptions\ExpiredSubscriptionsJob;
use App\Models\User;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Users\FollowupEmailRegistration::class,
        Commands\Subscription\UpgradeNotice::class,
        Commands\Subscription\UpgradeAutomatic::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('followup:email-registration')->everyFiveMinutes();
        $schedule->command('subscription:upgrade-notice')->monthlyOn(1, '01:00');
        $schedule->command('subscription:upgrade-automatic')->monthlyOn(2, '01:00');
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    public function subscribedUsersSchedules(Schedule $schedule)
    {
        $subscribedUsers = User::whereNotNull('subscription_plan_id')->pluck('id')->toArray();

        for ($i=0; $i < count($subscribedUsers); $i++) { 
            /** Note: may not actually need this since cashier handles this, not sure yet */
            // $schedule->job(new ExpiredSubscriptionsJob($subscribedUsers[$i]))->daily();
        }
    }
}
