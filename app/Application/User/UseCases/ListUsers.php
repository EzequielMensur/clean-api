<?php

namespace App\Application\User\UseCases;

use App\Domain\User\Repositories\UserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListUsers
{
    public function __construct(private readonly UserRepository $repo) {}

    public function __invoke(string $q = '', int $perPage = 10): LengthAwarePaginator
    {
        return $this->repo->paginate($q, $perPage);
    }
}
