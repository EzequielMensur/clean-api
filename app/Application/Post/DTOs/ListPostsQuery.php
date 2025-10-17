<?php
namespace App\Application\Post\DTOs;

final class ListPostsQuery
{
    public function __construct(
        public readonly ?string $q,
        public readonly ?int $userId,
        public readonly int $page,
        public readonly int $perPage,
        public readonly string $sort,
        public readonly bool $withTrashed,
        public readonly bool $onlyTrashed,
    ) {}

    public static function fromArray(array $a): self
    {
        $q = isset($a['q']) ? trim((string)$a['q']) : '';
        $q = ($q === '') ? null : $q;

        $userId = isset($a['user_id']) && $a['user_id'] !== '' ? (int)$a['user_id'] : null;
        if ($userId !== null && $userId <= 0) { $userId = null; }

        $page    = max(1, (int)($a['page'] ?? 1));
        $perPage = max(1, min(100, (int)($a['per_page'] ?? 10)));

        $sort    = (string)($a['sort'] ?? '-id');
        $allowed = ['-id', 'id', 'title'];
        if (!in_array($sort, $allowed, true)) { $sort = '-id'; }

        $onlyTrashed = self::toBool($a['only_trashed'] ?? false);
        $withTrashed = $onlyTrashed ? false : self::toBool($a['with_trashed'] ?? false);

        return new self(
            q: $q,
            userId: $userId,
            page: $page,
            perPage: $perPage,
            sort: $sort,
            withTrashed: $withTrashed,
            onlyTrashed: $onlyTrashed,
        );
    }

    private static function toBool(mixed $v): bool
    {
        return filter_var($v, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false;
    }
}
