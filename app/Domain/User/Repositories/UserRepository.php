<?php

namespace App\Domain\User\Repositories;

use App\Domain\User\Entities\User as DomainUser;

interface UserRepository
{
    public function paginate(?string $q, int $page, int $perPage, string $sort): array;

    public function find(int $id): DomainUser;

    public function findByEmail(string $email): ?DomainUser;

    public function create(array $data): DomainUser;

    public function update(int $id, array $data): DomainUser;

    public function restore(int $id): DomainUser;

    public function delete(int $id): void;
}
