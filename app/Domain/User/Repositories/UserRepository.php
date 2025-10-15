<?php

namespace App\Domain\User\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepository
{
    public function paginate(string $q = '', int $perPage = 10): LengthAwarePaginator;

    public function find(int $id): User;

    public function create(array $data): User;

    public function update(int $id, array $data): User;

    public function delete(int $id): void;

    public function findByEmail(string $email): ?User;
}
