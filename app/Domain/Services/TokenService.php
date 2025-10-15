<?php

namespace App\Domain\Services;

use App\Models\User;

interface TokenService
{
    public function fromUser(User $user): string;

    public function attempt(array $credentials): ?string;

    public function refresh(): string;

    public function invalidate(): void;

    public function user(): ?User;
}
