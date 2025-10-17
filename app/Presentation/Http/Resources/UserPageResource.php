<?php
namespace App\Presentation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Application\User\DTOs\PagedResult;
use App\Application\User\DTOs\UserOutput;

final class UserPageResource extends JsonResource
{
    /** @param PagedResult $resource */
    public function __construct($resource) { parent::__construct($resource); }

    public function toArray($request): array
    {
        $data = array_map(
            fn (UserOutput $u) => $u->toArray(),
            $this->resource->data
        );

        return [
            'data' => $data,
            'meta' => [
                'current_page' => $this->resource->currentPage,
                'per_page'     => $this->resource->perPage,
                'total'        => $this->resource->total,
                'last_page'    => $this->resource->lastPage,
            ],
        ];
    }
}
