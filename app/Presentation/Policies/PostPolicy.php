<?php

namespace App\Presentation\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function update(User $user, Post $post): bool
    {
        if ($user->id === $post->user_id) {
            return true;
        }

        return (bool) config('features.post_modify_others');
    }

    public function delete(User $user, Post $post): bool
    {
        if ($user->id === $post->user_id) {
            return true;
        }

        return (bool) config('features.post_modify_others');
    }

    public function restore(User $user, Post $post): bool
    {
        if ($user->id === $post->user_id) {
            return true;
        }

        return (bool) config('features.post_modify_others');
    }
}
