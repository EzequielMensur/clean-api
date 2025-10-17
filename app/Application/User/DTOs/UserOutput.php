<?php

namespace App\Application\User\DTOs;

use App\Domain\User\Entities\User as DomainUser;

final readonly class UserOutput
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?string $username,
        public string $createdAt,
        public string $updatedAt,
        public ?string $deletedAt
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'username' => $this->username,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'deletedAt' => $this->deletedAt,

        ];
    }

     public static function fromDomain(DomainUser $u): self
    {
         $fmt = static fn (? \DateTimeImmutable $d) => $d?->format(\DateTimeInterface::ATOM);

        return new self(
            id: $u->id,
            name: $u->name,
            email: $u->email,
            username: $u->username,
            createdAt: $u->createdAt->format(\DateTimeInterface::ATOM),
            updatedAt: $u->updatedAt->format(\DateTimeInterface::ATOM),
            deletedAt: $fmt($u->deletedAt),
        );
    }
}
