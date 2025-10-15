<?php

namespace App\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Domain\User\Repositories\UserRepository::class,
            \App\Infrastructure\Persistence\User\Repositories\UserRepositoryEloquent::class
        );
        $this->app->bind(
            \App\Domain\Post\Repositories\PostRepository::class,
            \App\Infrastructure\Persistence\Post\Repositories\PostRepositoryEloquent::class
        );
    }
}
