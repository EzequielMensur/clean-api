<?php

namespace App\Application\User\UseCases;

use App\Domain\User\Repositories\UserRepository;

class DeleteUser
{
    public function __construct(private readonly UserRepository $repo) {}

    public function __invoke(int $id): void
    {
        $this->repo->delete($id);
    }
}
