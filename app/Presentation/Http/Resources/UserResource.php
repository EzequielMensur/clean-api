<?php

namespace App\Presentation\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{    public $resource;

    public function toArray($request): array
    {
        unset($request);
        $u = $this->resource;

        return [
            'id'         => $u->id,
            'name'       => $u->name,
            'email'      => $u->email,
            'username'   => $u->username,
            'created_at' => $u->createdAt,
            'updated_at' => $u->updatedAt,
            'deleted_at' => $u->deletedAt,
        ];
    }
}
