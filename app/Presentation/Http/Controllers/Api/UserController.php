<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Application\User\DTOs\CreateUserInput;
use App\Application\User\UseCases\CreateUser;
use App\Models\User;
use App\Presentation\Http\Requests\UserStoreRequest;
use App\Presentation\Http\Requests\UserUpdateRequest;
use App\Presentation\Http\Resources\UserResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $per = (int) $request->query('per_page', 10);
        $q = trim((string) $request->query('q', ''));

        $users = User::query()
            ->when($q !== '', fn ($qq) => $qq->where(function ($w) use ($q): void {
                $w->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('username', 'like', "%{$q}%");
            })
            )
            ->orderByDesc('id')
            ->paginate($per)
            ->appends($request->query());

        return UserResource::collection($users);
    }

    public function show(int $id): \App\Presentation\Http\Resources\UserResource
    {
        $user = User::findOrFail($id);

        return new UserResource($user);
    }

    public function me(Request $request): \App\Presentation\Http\Resources\UserResource
    {
        $me = $request->user()->loadCount('posts');

        return new UserResource($me);
    }

    public function update(UserUpdateRequest $request, int $id): \App\Presentation\Http\Resources\UserResource
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);
        $data = $request->validated();
        $user->fill($data)->save();

        return new UserResource($user);
    }

    public function destroy(int $id)
    {
        $user = User::findOrFail($id);
        $this->authorize('delete', $user);
        $user->delete();

        return response()->json($user->toArray(), 201);
    }

    public function store(UserStoreRequest $request, CreateUser $create)
    {
        $dto = new CreateUserInput(
            name: $request->string('name'),
            email: $request->string('email'),
            username: $request->filled('username') ? $request->string('username') : null,
            password: $request->string('password'),
        );

        $userOutput = $create($dto);

        return response()->json($userOutput->toArray(), 201);
    }
}
