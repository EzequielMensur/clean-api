<?php

namespace App\Presentation\Http\Resources;

use App\Application\Post\DTOs\PagedResult;
use Illuminate\Http\Resources\Json\JsonResource;

class PostPageResource extends JsonResource
{
    /** @var PagedResult */
    public $resource;

    public function toArray($request): array
    {
        return [
            'data' => PostResource::collection($this->resource->data),
            'meta' => [
                'current_page' => $this->resource->currentPage,
                'per_page' => $this->resource->perPage,
                'total' => $this->resource->total,
                'last_page' => $this->resource->lastPage,
            ],
        ];
    }
}
