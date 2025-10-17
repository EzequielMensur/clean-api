<?php

namespace App\Infrastructure\Persistence\User\Repositories;

use App\Domain\User\Repositories\UserRepository;
use App\Domain\User\Entities\User as DomainUser;
use App\Infrastructure\Persistence\User\Mappers\UserMapper;
use App\Models\User as EloquentUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

final class UserRepositoryEloquent implements UserRepository
{
    public function paginate(?string $q, int $page, int $perPage, string $sort): array
    {
        $builder = EloquentUser::query()
            ->when($q !== null && $q !== '', fn ($qq) => $qq->where(fn ($w) => $w
                ->where('name', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")
                ->orWhere('username', 'like', "%{$q}%")
            ));

        $dir = str_starts_with($sort, '-') ? 'desc' : 'asc';
        $col = ltrim($sort, '-');
        if (!in_array($col, ['id','name'], true)) { $col = 'id'; $dir = 'desc'; }

        $p = $builder->orderBy($col, $dir)
                     ->paginate($perPage, ['*'], 'page', $page);

        return [
            'data'         => array_map([UserMapper::class, 'toDomain'], $p->items()),
            'current_page' => $p->currentPage(),
            'per_page'     => $p->perPage(),
            'total'        => $p->total(),
            'last_page'    => $p->lastPage(),
        ];
    }

    public function find(int $id): DomainUser
    {
        $m = EloquentUser::findOrFail($id);
        return UserMapper::toDomain($m);
    }

    public function findByEmail(string $email): ?DomainUser
    {
        $m = EloquentUser::where('email', $email)->first();
        return $m ? UserMapper::toDomain($m) : null;
    }

    public function create(array $data): DomainUser
    {
        [$email, $username] = $this->normalizeIdentity($data);

        $model = DB::transaction(function () use ($data, $email, $username): EloquentUser {
            $existing = $this->findExistingCandidate($email, $username);

            if ($existing && $existing->trashed()) {
                return $this->restoreAndUpdateModel($existing, $data, $email, $username);
            }

            if ($existing instanceof EloquentUser) {
                throw new RuntimeException('El email o username ya está en uso.');
            }

            return $this->createFreshModel($data, $email, $username);
        });

        return UserMapper::toDomain($model);
    }

    public function update(int $id, array $data): DomainUser
    {
        $u = EloquentUser::withTrashed()->findOrFail($id);

        if (array_key_exists('email', $data)) {
            $data['email'] = strtolower(trim((string) $data['email']));
        }
        if (array_key_exists('username', $data)) {
            $data['username'] = trim((string) $data['username']) ?: null;
        }
        if (array_key_exists('password', $data) && $data['password'] !== null) {
            $data['password'] = Hash::make((string) $data['password']);
        } else {
            unset($data['password']);
        }

        $u->fill($data)->save();

        return UserMapper::toDomain($u->fresh());
    }

    public function delete(int $id): void
    {
        $u = EloquentUser::findOrFail($id);
        $u->delete();
    }

    public function restore(int $id): DomainUser
    {
        $u = EloquentUser::withTrashed()->findOrFail($id);
        if ($u->trashed()) {
            $u->restore();
            $u->refresh();
        }
        return UserMapper::toDomain($u);
    }

    private function normalizeIdentity(array $data): array
    {
        $email = strtolower(trim((string) $data['email']));
        $username = isset($data['username']) && trim((string) $data['username']) !== ''
            ? trim((string) $data['username'])
            : null;

        return [$email, $username];
    }

    private function findExistingCandidate(string $email, ?string $username): ?EloquentUser
    {
        $byEmail = EloquentUser::withTrashed()->where('email', $email)->first();
        $byUsername = $username
            ? EloquentUser::withTrashed()->where('username', $username)->first()
            : null;

        if ($byEmail && $byUsername && $byEmail->id !== $byUsername->id) {
            throw new RuntimeException('Conflicto: email y username pertenecen a usuarios distintos.');
        }

        return $byEmail ?: $byUsername;
    }

    private function restoreAndUpdateModel(EloquentUser $user, array $data, string $email, ?string $username): EloquentUser
    {
        $user->restore();

        $payload = [
            'name'     => $data['name'] ?? $user->name,
            'email'    => $email,
            'username' => $username,
        ];

        if (!empty($data['password'])) {
            $payload['password'] = Hash::make((string) $data['password']);
        }

        $user->fill($payload)->save();

        return $user;
    }

    private function createFreshModel(array $data, string $email, ?string $username): EloquentUser
    {
        $u = new EloquentUser;
        $u->name = $data['name'];
        $u->email = $email;
        $u->username = $username;
        $u->password = Hash::make((string) $data['password']); // IMPORTANTÍSIMO
        $u->save();

        return $u;
    }
}
