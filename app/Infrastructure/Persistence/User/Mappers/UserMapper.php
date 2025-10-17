<?php

namespace App\Infrastructure\Persistence\User\Mappers;

use App\Domain\User\Entities\User as DomainUser;
use App\Models\User as EloquentUser;

final class UserMapper
{
    public static function toDomain(EloquentUser $u): DomainUser
    {
        return new DomainUser(
               id:        (int) $u->id,
            name:      (string) $u->name,
            email:     (string) $u->email,
            username:           $u->username !== null ? (string) $u->username : null,
            createdAt: $u->created_at,
            updatedAt: $u->updated_at,
            deletedAt: $u->deleted_at,
        );
    }
}
