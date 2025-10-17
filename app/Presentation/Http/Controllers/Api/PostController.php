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
use App\Domain\Post\Repositories\PostRepository;
use App\Presentation\Http\Requests\PostStoreRequest;
use App\Presentation\Http\Requests\PostUpdateRequest;
use App\Presentation\Http\Resources\PostPageResource;
use App\Presentation\Http\Resources\PostResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\Response;

class PostController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request, ListPosts $list): PostPageResource
    {
        $query  = ListPostsQuery::fromArray($request->query());
        $result = $list($query);

        return new PostPageResource($result);
    }

    public function show(int $id, GetPost $get): PostResource
    {
        $dto = $get($id);
        return new PostResource($dto);
    }

    public function store(PostStoreRequest $request, CreatePost $create): Response
    {
        $input = new CreatePostInput(
            userId: (int) $request->user()->id,
            title:  (string) $request->input('title'),
            body:   (string) $request->input('body'),
        );

        $dto = $create($input);

        return (new PostResource($dto))
            ->response()
            ->setStatusCode(201);
    }

    public function update(
        PostUpdateRequest $request,
        int $id,
        UpdatePost $update,
        PostRepository $posts
    ): PostResource {
        $entity = $posts->find($id, false);
        $this->authorize('update', $entity);

        $input = new UpdatePostInput(
            id:    $id,
            title: $request->has('title') ? (string) $request->input('title') : null,
            body:  $request->has('body')  ? (string) $request->input('body')  : null,
        );

        $dto = $update($input);

        return new PostResource($dto);
    }

    public function destroy(
        Request $request,
        int $id,
        DeletePost $delete,
        PostRepository $posts
    ) {
        $entity = $posts->find($id, false);
        $this->authorize('delete', $entity);

        $delete($id);

        return response()->json(['message' => 'deleted'], 200);
    }

    public function restore(
        Request $request,
        int $id,
        RestorePost $restore,
        PostRepository $posts
    ) {
        $entity = $posts->find($id, true);
        $this->authorize('restore', $entity);

        $restore($id);

        return response()->json(['message' => 'restored']);
    }
}
