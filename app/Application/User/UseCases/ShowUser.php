<?php

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\UserOutput;
use App\Domain\User\Repositories\UserRepository;

final class ShowUser
{
    public function __construct(private readonly UserRepository $repo) {}

    public function __invoke(int $id): UserOutput
    {
        $user = $this->repo->find($id);

        return new UserOutput(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            username: $user->username,
            createdAt: $user->createdAt?->format('c') ?? null,
            updatedAt: $user->updatedAt?->format('c') ?? null,
        );
    }
}
