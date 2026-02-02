<?php

namespace App\Console\Commands\Users;

use App\Mail\FollowupMail;
use App\Models\User;
use App\Models\FollowupEmail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class FollowupEmailRegistration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'followup:email-registration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send followup emails to the users for unsuccessful registration';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $this->sendFollowupEmail();

            return Command::SUCCESS;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $this->error($th->getMessage());

            return Command::FAILURE;
        }
    }
    
    private function sendFollowupEmail()
    {
        $subject = 'Unsuccessful Registration in AceADA';

        $users = User::where('created_at', '<',
            Carbon::now()->subHours(1)->toDateTimeString())
                ->where('type', 'client')
                ->whereDoesntHave('subscriptions')
                ->whereDoesntHave('cards')
                ->whereDoesntHave('followupemails', function ($query) {
                    $query->where('type', FollowupEmail::TYPE_REGISTER);
                })->limit(5)->get();
        
        foreach ($users as $user) {
            $recipient = $user->email;
            
            Mail::to($recipient)->send(new FollowupMail($subject, $user));

            FollowupEmail::create(['user_id' => $user->id, 'type' => FollowupEmail::TYPE_REGISTER]);
        }
    }
}