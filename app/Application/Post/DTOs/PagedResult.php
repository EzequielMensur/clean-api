<?php

namespace App\Application\Post\DTOs;

final class PagedResult
{
    /** @param PostOutput[] $data */
    public function __construct(
        public array $data,
        public int $currentPage,
        public int $perPage,
        public int $total,
        public int $lastPage,
    ) {}

    public function toArray(): array
    {
        return [
            'data' => array_map(fn (PostOutput $o): array => $o->toArray(), $this->data),
            'meta' => [
                'current_page' => $this->currentPage,
                'per_page' => $this->perPage,
                'total' => $this->total,
                'last_page' => $this->lastPage,
            ],
        ];
    }
}
