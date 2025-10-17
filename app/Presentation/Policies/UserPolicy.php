<?php

namespace App\Presentation\Policies;

use App\Domain\User\Entities\User as DomainUser;
use Illuminate\Contracts\Auth\Authenticatable;

final class UserPolicy
{
    public function update(Authenticatable $auth, DomainUser $target): bool
    {
        return (int) $auth->getAuthIdentifier() === $target->id
            || (bool) config('features.user_modify_others', false);
    }

    public function delete(Authenticatable $auth, DomainUser $target): bool
    {
        return (int) $auth->getAuthIdentifier() === $target->id
            || (bool) config('features.user_modify_others', false);
    }
}
