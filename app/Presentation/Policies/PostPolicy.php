<?php

namespace App\Presentation\Policies;

use App\Domain\Post\Entities\Post as DomainPost;
use Illuminate\Contracts\Auth\Authenticatable;

final class PostPolicy
{
    public function update(Authenticatable $auth, DomainPost $post): bool
    {
        return (int) $auth->getAuthIdentifier() === $post->userId
            || (bool) config('features.post_modify_others', false);
    }

    public function delete(Authenticatable $auth, DomainPost $post): bool
    {
        return (int) $auth->getAuthIdentifier() === $post->userId
            || (bool) config('features.post_modify_others', false);
    }

    public function restore(Authenticatable $auth, DomainPost $post): bool
    {
        return (int) $auth->getAuthIdentifier() === $post->userId;
    }
}
