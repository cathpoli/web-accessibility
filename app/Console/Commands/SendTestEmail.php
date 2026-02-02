<?php

namespace App\Console\Commands;

use App\Mail\TestMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SendTestEmail extends Command
{
    /**
     * String $env
     */
    private $env;

    /**
     * String $recipient
     */
    private $recipient;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email-configuration:test {recipient}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email for testing mail configuration';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->env = config('app.env');

        $this->recipient = $this->argument('recipient');
        if (! $this->validRecipient()) return Command::FAILURE;

        $confirmation = $this->confirm("You are about to send a TEST EMAIL to {$this->recipient} on {$this->env} environment. Do you wish to continue?", false);
        if (! $confirmation) return Command::SUCCESS;

        try {
            $this->info('Sending test email...');
            Mail::to($this->recipient)->send(new TestMail);

            return Command::SUCCESS;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            $this->error($th->getMessage());

            return Command::FAILURE;
        }
    }

    public function validRecipient()
    {
        $validator = Validator::make(['recipient' => $this->recipient], ['recipient' => 'required|email']);

        if ($validator->fails()) {
            $this->error($validator->errors()->first());
            return false;
        }

        return true;
    }
}
