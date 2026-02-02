<?php

namespace App\Console\Commands\Users;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateSingleSignOnTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:sso-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate single sign on tokens for users.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach (User::whereNull('sso_token')->get() as $key => $user) {
            $user->sso_token = Str::random(30);
            $user->save();
        }

        return Command::SUCCESS;
    }
}
