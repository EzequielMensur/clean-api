<?php

namespace App\Infrastructure\Persistence\User\Repositories;

use App\Domain\User\Repositories\UserRepository;
use App\Models\User as EloquentUser;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

final class UserRepositoryEloquent implements UserRepository
{
    public function findByEmail(string $email): ?EloquentUser
    {
        return EloquentUser::where('email', $email)->first();
    }

    public function paginate(string $q = '', int $perPage = 10): LengthAwarePaginator
    {
        return EloquentUser::query()
            ->when($q !== '', fn ($qq) => $qq->where(fn ($w) => $w
                ->where('name', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")
                ->orWhere('username', 'like', "%{$q}%")
            )
            )
            ->withCount('posts')
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    public function find(int $id): EloquentUser
    {
        return EloquentUser::withCount('posts')->findOrFail($id);
    }

    public function create(array $data): EloquentUser
    {
        [$email, $username] = $this->normalizeIdentity($data);

        return DB::transaction(function () use ($data, $email, $username): \App\Models\User {

            $existing = $this->findExistingCandidate($email, $username);

            if ($existing && $existing->trashed()) {
                return $this->restoreAndUpdate($existing, $data, $email, $username);
            }

            if ($existing instanceof \App\Models\User) {
                throw new \RuntimeException('El email o username ya está en uso.');
            }

            return $this->createFresh($data, $email, $username);
        });
    }

    public function update(int $id, array $data): EloquentUser
    {
        $u = EloquentUser::findOrFail($id);
        $u->fill($data)->save();

        return $u;
    }

    public function delete(int $id): void
    {
        $u = EloquentUser::findOrFail($id);
        $u->delete();
    }

    public function restore(int $id): EloquentUser
    {
        $u = EloquentUser::withTrashed()->findOrFail($id);
        if (! $u->trashed()) {
            return $u;
        }
        $u->restore();

        return $u->fresh();
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
            throw new \RuntimeException('Conflicto: email y username pertenecen a usuarios distintos.');
        }

        return $byEmail ?: $byUsername;
    }

    private function restoreAndUpdate(EloquentUser $user, array $data, string $email, ?string $username): EloquentUser
    {
        $user->restore();

        $user->fill([
            'name' => $data['name'] ?? $user->name,
            'email' => $email,
            'username' => $username,
            'password' => $data['password'] ?? $user->password,
        ])->save();

        return $user;
    }

    private function createFresh(array $data, string $email, ?string $username): EloquentUser
    {
        $u = new EloquentUser;
        $u->name = $data['name'];
        $u->email = $email;
        $u->username = $username;
        $u->password = $data['password'];
        $u->save();

        return $u;
    }
}
