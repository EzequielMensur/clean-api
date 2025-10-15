<?php

namespace App\Domain\User\Entities;

final class User
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?string $username,
        public ?\DateTimeImmutable $emailVerifiedAt,
        public \DateTimeImmutable $createdAt,
        public \DateTimeImmutable $updatedAt,
        public ?\DateTimeImmutable $deletedAt = null,
    ) {}

    public function toArray(): array
    {
        $fmt = fn (?\DateTimeImmutable $d) => $d?->format(DATE_ATOM);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'email_verified_at' => $fmt($this->emailVerifiedAt),
            'created_at' => $fmt($this->createdAt),
            'updated_at' => $fmt($this->updatedAt),
            'deleted_at' => $fmt($this->deletedAt),
        ];
    }
}
