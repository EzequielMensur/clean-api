<?php

namespace App\Application\Post\UseCases;

use App\Application\Post\DTOs\ListPostsQuery;
use App\Application\Post\DTOs\PagedResult;
use App\Application\Post\DTOs\PostOutput;
use App\Domain\Post\Repositories\PostRepository;

final class ListPosts
{
    public function __construct(private readonly PostRepository $repo) {}

    public function __invoke(ListPostsQuery $q): PagedResult
    {
        $page = max(1, $q->page);
        $perPage = max(1, min(100, $q->perPage));

        $res = $this->repo->paginate(
            q: $q->q,
            userId: $q->userId,
            page: $page,
            perPage: $perPage,
            sort: $q->sort,
            withTrashed: $q->withTrashed,
            onlyTrashed: $q->onlyTrashed,
        );
        $items = array_map(
            fn ($domainPost): \App\Application\Post\DTOs\PostOutput => PostOutput::fromDomain($domainPost),
            $res['data']
        );

        return new PagedResult(
            data: $items,
            currentPage: (int) $res['current_page'],
            perPage: (int) $res['per_page'],
            total: (int) $res['total'],
            lastPage: (int) $res['last_page'],
        );
    }
}
