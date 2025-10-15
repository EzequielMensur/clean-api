<?php

namespace App\Infrastructure\Persistence\User\Mappers;

use App\Application\User\DTOs\UserInput as DomainUser;
use App\Models\User as EloquentUser;

final class UserMapper
{
    public static function toDomain(EloquentUser $u): DomainUser
    {
        return new DomainUser(
            name: $u->name,
            email: $u->email,
            username: $u->username
        );
    }
}
