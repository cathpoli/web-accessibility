<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
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
        // \App\Models\User::factory(10)->create();

        $admin = User::where('email', User::ADMIN_EMAIL)->firstOr(function () {
            return User::factory()->admin()->create([
                'name' => 'Admin',
                'email' => User::ADMIN_EMAIL,
            ]);
        });

        Artisan::call('products:sync');
    }
}
