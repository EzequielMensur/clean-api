<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Application\Post\DTOs\CreatePostInput;
use App\Application\Post\DTOs\ListPostsQuery;
use App\Application\Post\DTOs\UpdatePostInput;
use App\Application\Post\UseCases\CreatePost;
use App\Application\Post\UseCases\DeletePost;
use App\Application\Post\UseCases\GetPost;
use App\Application\Post\UseCases\ListPosts;
use App\Application\Post\UseCases\RestorePost;
use App\Application\Post\UseCases\UpdatePost;
use App\Models\Post as EloquentPost;
use App\Presentation\Http\Requests\PostStoreRequest;
use App\Presentation\Http\Requests\PostUpdateRequest;
use App\Presentation\Http\Resources\PostPageResource;
use App\Presentation\Http\Resources\PostResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PostController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request, ListPosts $list): \App\Presentation\Http\Resources\PostPageResource
    {
        $q = $request->query('q');
        $userId = $request->query('user_id') ? (int) $request->query('user_id') : null;
        $page = (int) $request->query('page', 1);
        $perPage = (int) $request->query('per_page', 10);
        $sort = $request->query('sort', '-id');
        $withTrash = (bool) $request->boolean('with_trashed', false);
        $onlyTrash = (bool) $request->boolean('only_trashed', false);

        $query = new ListPostsQuery($q, $userId, $page, $perPage, $sort, $withTrash, $onlyTrash);
        $result = $list($query);

        return new PostPageResource($result);
    }

    public function show(int $id, GetPost $get): \App\Presentation\Http\Resources\PostResource
    {
        $dto = $get($id);

        return new PostResource($dto);
    }

    public function store(PostStoreRequest $request, CreatePost $create): \Symfony\Component\HttpFoundation\Response
    {
        $userId = (int) $request->user()->id;

        $input = new CreatePostInput(
            userId: $userId,
            title: (string) $request->input('title'),
            body: (string) $request->input('body'),
        );

        $dto = $create($input);

        return (new PostResource($dto))->response()->setStatusCode(201);
    }

    public function update(PostUpdateRequest $request, int $id, UpdatePost $update, GetPost $get): \App\Presentation\Http\Resources\PostResource
    {
        $model = EloquentPost::findOrFail($id);
        $this->authorize('update', $model);

        $input = new UpdatePostInput(
            id: $id,
            title: $request->has('title') ? (string) $request->input('title') : null,
            body: $request->has('body') ? (string) $request->input('body') : null,
        );

        $dto = $update($input);

        return new PostResource($dto);
    }

    public function destroy(Request $request, int $id, DeletePost $delete, GetPost $get)
    {
        $model = EloquentPost::findOrFail($id);
        $this->authorize('delete', $model);

        $delete($id);

        return response()->noContent();
    }

    public function restore(Request $request, int $id, RestorePost $restore, GetPost $get)
    {
        $model = EloquentPost::withTrashed()->findOrFail($id);
        $this->authorize('restore', $model);

        $restore($id);

        return response()->json(['message' => 'restored']);
    }
}
