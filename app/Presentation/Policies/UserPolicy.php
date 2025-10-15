<?php

namespace App\Presentation\Policies;

use App\Models\User;

class UserPolicy
{
    public function update(User $actor, User $target): bool
    {
        if ($actor->id === $target->id) {
            return true;
        }

        return config('features.user_modify_others') === true;
    }

    public function delete(User $actor, User $target): bool
    {
        if ($actor->id === $target->id) {
            return true;
        }

        return config('features.user__others') === true;
    }
}
