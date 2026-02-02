<?php

namespace App\Console\Commands\Users;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admins:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create admin user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->ask('What is the admin name?');
        $email = $this->ask('What is the admin email?');
        $password = $this->secret('What is the admin password?');
        $passwordConfirmation = $this->secret('Confirm admin password.');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $passwordConfirmation,
        ], [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            $this->error($validator->errors()->first());
            return Command::FAILURE;
        }

        if ($this->confirm("Are you sure you want to create admin account for {$email}?", false)) {
            $admin = new User();
            $admin->name = $name;
            $admin->email = $email;
            $admin->password = Hash::make($password);
            $admin->type = 'admin';
            $admin->save();

            $this->info($admin);
        }

        return Command::SUCCESS;
    }
}
