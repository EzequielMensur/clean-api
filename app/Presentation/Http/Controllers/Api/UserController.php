<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Application\User\DTOs\CreateUserInput;
use App\Application\User\DTOs\ListUsersQuery;
use App\Application\User\DTOs\UpdateUserInput;
use App\Application\User\UseCases\CreateUser;
use App\Application\User\UseCases\DeleteUser;
use App\Application\User\UseCases\ListUsers;
use App\Application\User\UseCases\ShowUser;
use App\Application\User\UseCases\UpdateUser;
use App\Presentation\Http\Requests\UserStoreRequest;
use App\Presentation\Http\Requests\UserUpdateRequest;
use App\Presentation\Http\Resources\UserPageResource;
use App\Presentation\Http\Resources\UserResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Domain\User\Repositories\UserRepository;

use App\Domain\User\Entities\User as DomainUser;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request, ListUsers $list): UserPageResource
    {
        $q = ListUsersQuery::fromArray($request->query());
        $result = $list($q);
        return new UserPageResource($result);
    }

    public function show(int $id, ShowUser $show): UserResource
    {
        $dto = $show($id);
        return new UserResource($dto);
    }

    public function me(Request $request, ShowUser $show): UserResource
    {
        $dto = $show((int) $request->user()->id);
        return new UserResource($dto);
    }

    public function store(UserStoreRequest $request, CreateUser $create): Response
    {
        $input = new CreateUserInput(
            name:     (string) $request->input('name'),
            email:    (string) $request->input('email'),
            username: $request->filled('username') ? (string) $request->input('username') : null,
            password: (string) $request->input('password'),
        );

        $dto = $create($input);

        return (new UserResource($dto))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UserUpdateRequest $request, int $id, UpdateUser $update, UserRepository $users): UserResource
    {
        $entity = $users->find($id);
        $this->authorize('update', $entity);

        $input = new UpdateUserInput(
            id:       $id,
            name:     $request->has('name')     ? (string) $request->input('name')     : null,
            email:    $request->has('email')    ? (string) $request->input('email')    : null,
            username: $request->has('username') ? (string) $request->input('username') : null,
            password: $request->has('password') ? (string) $request->input('password') : null,
        );

        $dto = $update($input);

        return new UserResource($dto);
    }

    public function destroy(int $id, DeleteUser $delete, UserRepository $users)
    {
        $entity = $users->find($id);
        $this->authorize('delete', $entity);

        $delete($id);

        return response()->json([
            'message'     => 'Usuario eliminado',
            'id'          => $id,
            'deleted_at'  => now()->toAtomString(),
        ], 200);
    }
}
