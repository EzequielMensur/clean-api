<?php

namespace App\Presentation\Http\Resources;

use App\Application\Post\DTOs\PostOutput as PostDTO;
use App\Models\Post as PostModel;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * @var PostDTO|PostModel
     */
    public $resource;

    public function toArray($request): array
    {
        $p = $this->resource;

        if ($p instanceof PostDTO) {
            return [
                'id' => $p->id,
                'user_id' => $p->userId,
                'title' => $p->title,
                'body' => $p->body,
                'created_at' => $p->createdAt,
                'updated_at' => $p->updatedAt,
                'deleted_at' => $p->deletedAt,
            ];
        }

        if ($p instanceof PostModel) {
            return [
                'id' => (int) $p->id,
                'user_id' => (int) $p->user_id,
                'title' => (string) $p->title,
                'body' => (string) $p->body,
                'created_at' => $p->created_at?->toAtomString(),
                'updated_at' => $p->updated_at?->toAtomString(),
                'deleted_at' => $p->deleted_at?->toAtomString(),
            ];
        }

        return (array) $p;
    }
}
