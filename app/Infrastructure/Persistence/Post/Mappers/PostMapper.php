<?php

namespace App\Infrastructure\Persistence\Post\Mappers;

use App\Domain\Post\Entities\Post as DomainPost;
use App\Models\Post as EloquentPost;

final class PostMapper
{
    public static function toDomain(EloquentPost $p): DomainPost
    {
        return new DomainPost(
            id: (int) $p->id,
            userId: (int) $p->user_id,
            title: (string) $p->title,
            body: (string) $p->body,
            createdAt: $p->created_at,
            updatedAt: $p->updated_at,
            deletedAt: $p->deleted_at,
        );
    }
}
