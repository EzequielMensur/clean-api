<?php

namespace App\Presentation\Policies;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class PolicyProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Post::class => \App\Presentation\Policies\PostPolicy::class,
        \App\Models\User::class => \App\Presentation\Policies\UserPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
