<?php

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\UpdateUserInput;
use App\Application\User\DTOs\UserOutput;
use App\Domain\User\Repositories\UserRepository;

final class UpdateUser
{
    public function __construct(private readonly UserRepository $repo) {}

    public function __invoke(UpdateUserInput $input): UserOutput
    {
        $payload = [];
        if ($input->name !== null)     { $payload['name'] = $input->name; }
        if ($input->email !== null)    { $payload['email'] = $input->email; }
        if ($input->username !== null) { $payload['username'] = $input->username; }
        if ($input->password !== null) { $payload['password'] = $input->password; }

        $user = $this->repo->update($input->id, $payload);

        return UserOutput::fromDomain($user);
    }
}
