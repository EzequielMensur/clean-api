<?php

namespace App\Application\User\DTOs;

use App\Models\User;

final readonly class UserOutput
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?string $username,
        public string $createdAt,
        public string $updatedAt,
    ) {}

    public static function fromModel(User $u): self
    {
        return new self(
            id: $u->id,
            name: $u->name,
            email: $u->email,
            username: $u->username,
            createdAt: $u->created_at?->toISOString() ?? '',
            updatedAt: $u->updated_at?->toISOString() ?? '',
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
        ];
    }
}
