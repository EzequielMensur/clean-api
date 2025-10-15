<?php

namespace App\Infrastructure\Persistence\Post\Repositories;

use App\Domain\Post\Entities\Post as DomainPost;
use App\Domain\Post\Repositories\PostRepository;
use App\Infrastructure\Persistence\Post\Mappers\PostMapper;
use App\Models\Post as EloquentPost;
use Illuminate\Database\Eloquent\Builder;

final class PostRepositoryEloquent implements PostRepository
{
    public function paginate(
        ?string $q = null,
        ?int $userId = null,
        int $page = 1,
        int $perPage = 10,
        ?string $sort = '-id',
        bool $withTrashed = false,
        bool $onlyTrashed = false,
    ): array {
        /** @var Builder $qb */
        $qb = EloquentPost::query();

        // soft deletes
        if ($onlyTrashed) {
            $qb->onlyTrashed();
        } elseif ($withTrashed) {
            $qb->withTrashed();
        }

        // filtros
        if ($q !== null && $q !== '') {
            $term = trim($q);
            $qb->where(function (Builder $w) use ($term): void {
                $w->where('title', 'like', "%{$term}%")
                    ->orWhere('body', 'like', "%{$term}%");
            });
        }

        if ($userId !== null) {
            $qb->where('user_id', $userId);
        }

        // orden
        $sort = $sort ?: '-id';
        if ($sort === '-id') {
            $qb->orderByDesc('id');
        } elseif ($sort === 'id') {
            $qb->orderBy('id');
        } elseif ($sort === 'title') {
            $qb->orderBy('title');
        } else {
            // fallback
            $qb->orderByDesc('id');
        }

        $paginator = $qb->paginate($perPage, ['*'], 'page', $page);

        $items = array_map(
            fn ($model): \App\Domain\Post\Entities\Post => PostMapper::toDomain($model),
            $paginator->items()
        );

        return [
            'data' => $items,
            'current_page' => $paginator->currentPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'last_page' => $paginator->lastPage(),
        ];
    }

    public function find(int $id, bool $withTrashed = false): DomainPost
    {
        $qb = EloquentPost::query();
        if ($withTrashed) {
            $qb->withTrashed();
        }

        /** @var EloquentPost $post */
        $post = $qb->findOrFail($id);

        return PostMapper::toDomain($post);
    }

    public function create(int $userId, string $title, string $body): DomainPost
    {
        $post = EloquentPost::create([
            'user_id' => $userId,
            'title' => $title,
            'body' => $body,
        ]);

        return PostMapper::toDomain($post);
    }

    public function update(int $id, array $data): DomainPost
    {
        $post = EloquentPost::findOrFail($id);

        $allowed = array_intersect_key($data, array_flip(['title', 'body']));
        $post->fill($allowed);
        $post->save();

        $post->refresh();

        return PostMapper::toDomain($post);
    }

    public function delete(int $id): void
    {
        /** @var EloquentPost $post */
        $post = EloquentPost::findOrFail($id);
        $post->delete();
    }

    public function restore(int $id): void
    {
        $post = EloquentPost::withTrashed()->findOrFail($id);
        if ($post->trashed()) {
            $post->restore();
        }
    }
}
