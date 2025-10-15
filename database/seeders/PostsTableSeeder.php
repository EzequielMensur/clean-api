<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    public function run(): void
    {
        $demo = User::where('email', 'demo@example.com')->first();
        if ($demo && ! $demo->posts()->exists()) {
            Post::factory()
                ->count(5)
                ->create(['user_id' => $demo->id]);
        }
        User::where('email', '!=', 'demo@example.com')
            ->inRandomOrder()
            ->take(5)
            ->get()
            ->each(function (User $u): void {
                if (! $u->posts()->exists()) {
                    Post::factory()
                        ->count(3)
                        ->create(['user_id' => $u->id]);
                }
            });

        $faltan = 20 - Post::count();
        if ($faltan > 0) {
            Post::factory()
                ->count($faltan)
                ->create();
        }
    }
}
