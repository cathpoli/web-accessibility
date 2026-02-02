<?php

namespace App\Console\Commands;

use App\Jobs\TestCronJob;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class TestCronCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test crontab configuration';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        TestCronJob::dispatch(Str::random())->delay(now()->addMinute());

        return Command::SUCCESS;
    }
}
