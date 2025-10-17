<?php
namespace App\Application\User\DTOs;

final class ListUsersQuery
{
    public function __construct(
        public readonly ?string $q,
        public readonly int $page,
        public readonly int $perPage,
        public readonly string $sort,
    ) {}

    public static function fromArray(array $a): self
    {
        $q = isset($a['q']) ? trim((string)$a['q']) : '';
        $q = ($q === '') ? null : $q;

        $page    = max(1, (int)($a['page'] ?? 1));
        $perPage = max(1, min(100, (int)($a['per_page'] ?? 10)));

        $sort    = (string)($a['sort'] ?? '-id');
        $allowed = ['-id', 'id', 'name'];
        if (!in_array($sort, $allowed, true)) $sort = '-id';

        return new self($q, $page, $perPage, $sort);
    }
}
