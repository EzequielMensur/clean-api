<?php

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\UserOutput;
use App\Domain\User\Repositories\UserRepository;

final class UpdateUser
{
    public function __construct(private readonly UserRepository $repo) {}

    public function __invoke(int $id, array $data): UserOutput
    {
        $user = $this->repo->update($id, $data);

        return new UserOutput(
            id: $user->id,
            name: $user->name,
            email: $user->email,
            username: $user->username,
            createdAt: $user->createdAt ?? null,
            updatedAt: $user->updatedAt ?? null,
        );
    }
}
