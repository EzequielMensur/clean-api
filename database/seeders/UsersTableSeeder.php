<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\User::firstOrCreate(
            ['email' => 'demo@example.com'],
            ['name' => 'Demo', 'password' => 'secret', 'username' => 'demo', 'email_verified_at' => now()]
        );

        if (\App\Models\User::count() < 10) {
            \App\Models\User::factory(10 - \App\Models\User::count())->create();
        }
    }
}
