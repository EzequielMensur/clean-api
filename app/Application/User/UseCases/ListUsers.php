<?php

namespace App\Application\User\UseCases;

use App\Application\User\DTOs\UserOutput;
use App\Application\User\DTOs\PagedResult;
use App\Application\User\DTOs\ListUsersQuery;
use App\Domain\User\Repositories\UserRepository;

final class ListUsers
{
    public function __construct(private readonly UserRepository $repo) {}

    public function __invoke(ListUsersQuery $q): PagedResult
    {
        $res = $this->repo->paginate(
            q:       $q->q,
            page:    $q->page,
            perPage: $q->perPage,
            sort:    $q->sort,
        );

        $items = array_map(
            fn ($domainUser) => UserOutput::fromDomain($domainUser),
            $res['data']
        );

        return new PagedResult(
            data:        $items,
            currentPage: (int) $res['current_page'],
            perPage:     (int) $res['per_page'],
            total:       (int) $res['total'],
            lastPage:    (int) $res['last_page'],
        );
    }
}
