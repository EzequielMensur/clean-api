<?php

namespace App\Application\Post\DTOs;

use App\Domain\Post\Entities\Post as DomainPost;
final class PostOutput
{
    public function __construct(
        public int $id,
        public int $userId,
        public string $title,
        public string $body,
        public ?string $createdAt,
        public ?string $updatedAt,
        public ?string $deletedAt,
    ) {}

    public static function fromDomain(DomainPost $p): self
    {
        return new self(
            id: $p->id,
            userId: $p->userId,
            title: $p->title,
            body: $p->body,
            createdAt: $p->createdAt->format(\DateTimeInterface::ATOM),
            updatedAt: $p->updatedAt->format(\DateTimeInterface::ATOM),
            deletedAt: $p->deletedAt,
        );
    }
    public function toArray(): array
    {
        return [
            'id'         => $this->id,
            'user_id'    => $this->userId,
            'title'      => $this->title,
            'body'       => $this->body,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'deleted_at' => $this->deletedAt,
        ];
    }
}
