<?php

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\CreateUserInput;
use App\Application\User\DTOs\UserOutput;
use App\Domain\User\Repositories\UserRepository;

final class CreateUser
{
    public function __construct(
        private readonly UserRepository $repo
    ) {}

    public function __invoke(CreateUserInput $input): UserOutput
    {
        $userData = [
            'name' => $input->name,
            'email' => $input->email,
            'username' => $input->username,
            'password' => $input->password,
        ];

        $user = $this->repo->create($userData);

        return UserOutput::fromModel($user);
    }
}
